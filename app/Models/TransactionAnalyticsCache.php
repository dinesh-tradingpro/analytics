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
}
