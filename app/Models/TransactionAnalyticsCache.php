<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAnalyticsCache extends Model
{
    protected $table = 'transaction_analytics_cache';

    protected $fillable = [
        'transaction_type',
        'status',
        'period_type',
        'chart_data',
        'total_count',
        'total_amount',
        'top_transactions',
        'metadata',
        'total_records_fetched',
        'period_start',
        'period_end',
        'synced_at',
    ];

    protected $casts = [
        'chart_data' => 'array',
        'top_transactions' => 'array',
        'metadata' => 'array',
        'total_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'synced_at' => 'datetime',
    ];

    /**
     * Get analytics data for a specific transaction type and status
     */
    public static function getAnalyticsData(string $transactionType, string $status, string $periodType = 'all_time')
    {
        return static::where('transaction_type', $transactionType)
            ->where('status', $status)
            ->where('period_type', $periodType)
            ->orderBy('synced_at', 'desc')
            ->first();
    }

    /**
     * Get summary statistics for all transaction types and statuses
     */
    public static function getSummaryStats()
    {
        $summary = [];

        $types = ['deposit', 'withdrawal'];
        $statuses = ['approved', 'declined'];

        foreach ($types as $type) {
            foreach ($statuses as $status) {
                $data = static::getAnalyticsData($type, $status);
                $summary[$type][$status] = [
                    'total_count' => $data->total_count ?? 0,
                    'total_amount' => $data->total_amount ?? 0,
                    'last_sync' => $data->synced_at ?? null,
                ];
            }
        }

        return $summary;
    }

    /**
     * Get withdrawal to deposit ratio
     */
    public static function getWithdrawalDepositRatio()
    {
        $approvedWithdrawals = static::getAnalyticsData('withdrawal', 'approved');
        $approvedDeposits = static::getAnalyticsData('deposit', 'approved');

        $withdrawalAmount = $approvedWithdrawals->total_amount ?? 0;
        $depositAmount = $approvedDeposits->total_amount ?? 0;

        if ($depositAmount == 0) {
            return $withdrawalAmount > 0 ? 'N/A (No deposits)' : 0;
        }

        return round($withdrawalAmount / $depositAmount, 3);
    }

    /**
     * Check if cache is fresh (less than 1 hour old)
     */
    public function isFresh(): bool
    {
        return $this->synced_at && $this->synced_at->gt(now()->subHour());
    }

    /**
     * Get cached analytics data with filtering
     */
    public static function getCachedAnalytics($transactionType = 'all', $status = 'all', $period = 'daily')
    {
        $query = self::query();

        if ($transactionType !== 'all') {
            $query->where('transaction_type', $transactionType);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($period !== 'all') {
            $query->where('period_type', $period);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return [
                'chart_data' => [],
                'total_count' => 0,
                'total_amount' => 0,
                'last_updated' => null,
                'period' => $period,
            ];
        }

        // For specific periods like current_month and last_7_days, we might need to filter data from daily records
        if (in_array($period, ['current_month', 'last_7_days'])) {
            return self::getFilteredPeriodData($transactionType, $status, $period);
        }

        // Aggregate data from multiple records
        $totalCount = $records->sum('total_count');
        $totalAmount = $records->sum('total_amount');
        $chartData = [];
        $lastUpdated = $records->max('updated_at');

        foreach ($records as $record) {
            if (! empty($record->chart_data)) {
                $chartData = array_merge_recursive($chartData, $record->chart_data);
            }
        }

        return [
            'chart_data' => $chartData,
            'total_count' => $totalCount,
            'total_amount' => $totalAmount,
            'last_updated' => $lastUpdated,
            'period' => $period,
        ];
    }

    /**
     * Get filtered data for specific period types
     */
    private static function getFilteredPeriodData($transactionType, $status, $period)
    {
        // Get daily data and filter it for the specific period
        $query = self::query()->where('period_type', 'daily');

        if ($transactionType !== 'all') {
            $query->where('transaction_type', $transactionType);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return [
                'chart_data' => [],
                'total_count' => 0,
                'total_amount' => 0,
                'last_updated' => null,
                'period' => $period,
            ];
        }

        $filteredData = [];
        $totalCount = 0;
        $totalAmount = 0;

        foreach ($records as $record) {
            if (! empty($record->chart_data) && ! empty($record->chart_data['time_series'])) {
                foreach ($record->chart_data['time_series'] as $dateKey => $data) {
                    $date = \Carbon\Carbon::parse($dateKey);
                    $includeData = false;

                    if ($period === 'current_month') {
                        $includeData = $date->month === now()->month && $date->year === now()->year;
                    } elseif ($period === 'last_7_days') {
                        $includeData = $date->isAfter(now()->subDays(8)->startOfDay()) && $date->isBefore(now()->startOfDay());
                    }

                    if ($includeData) {
                        $filteredData['time_series'][$dateKey] = $data;
                        $totalCount += $data['count'] ?? 0;
                        $totalAmount += $data['amount'] ?? 0;
                    }
                }
            }
        }

        return [
            'chart_data' => $filteredData,
            'total_count' => $totalCount,
            'total_amount' => $totalAmount,
            'last_updated' => $records->max('updated_at'),
            'period' => $period,
        ];
    }
}
