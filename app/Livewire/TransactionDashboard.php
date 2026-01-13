<?php

namespace App\Livewire;

use App\Models\TransactionAnalyticsCache;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionDashboard extends Component
{
    public $selectedPeriod = 'daily';

    public $showTopTransactions = true;

    public $error = null;

    // Insights filters
    public $dateRange = '30';

    public $topLimit = 10;

    public function mount()
    {
        // Check if we have fresh data, if not suggest sync
        $hasRecentData = $this->checkForRecentData();
        if (! $hasRecentData) {
            session()->flash('sync_needed', 'Transaction data is stale or missing. Consider running: php artisan transactions:sync');
        }
    }

    public function updatedDateRange()
    {
        // Trigger re-render when date range changes
    }

    public function updatedTopLimit()
    {
        // Trigger re-render when top limit changes
    }

    #[Computed]
    public function depositsApproved()
    {
        return TransactionAnalyticsCache::getAnalyticsData('deposit', 'approved', $this->selectedPeriod) ?? (object) ['total_count' => 0, 'total_amount' => 0];
    }

    #[Computed]
    public function depositsDeclined()
    {
        return TransactionAnalyticsCache::getAnalyticsData('deposit', 'declined', $this->selectedPeriod) ?? (object) ['total_count' => 0, 'total_amount' => 0];
    }

    #[Computed]
    public function withdrawalsApproved()
    {
        return TransactionAnalyticsCache::getAnalyticsData('withdrawal', 'approved', $this->selectedPeriod) ?? (object) ['total_count' => 0, 'total_amount' => 0];
    }

    #[Computed]
    public function withdrawalsDeclined()
    {
        return TransactionAnalyticsCache::getAnalyticsData('withdrawal', 'declined', $this->selectedPeriod) ?? (object) ['total_count' => 0, 'total_amount' => 0];
    }

    #[Computed]
    public function summaryStats()
    {
        return TransactionAnalyticsCache::getSummaryStats();
    }

    #[Computed]
    public function withdrawalDepositRatio()
    {
        return TransactionAnalyticsCache::getWithdrawalDepositRatio();
    }

    #[Computed]
    public function chartData()
    {
        $types = ['deposit', 'withdrawal'];
        $statuses = ['approved', 'declined'];
        $chartData = [];

        foreach ($types as $type) {
            foreach ($statuses as $status) {
                $data = TransactionAnalyticsCache::getAnalyticsData($type, $status, $this->selectedPeriod);
                if ($data) {
                    $chartData["{$type}_{$status}"] = [
                        'chart_data' => $data->chart_data, // This is already an array due to casting
                        'total_count' => $data->total_count,
                        'total_amount' => $data->total_amount,
                        'last_sync' => $data->synced_at,
                        'top_transactions' => $data->top_transactions,
                    ];
                }
            }
        }

        return $chartData;
    }

    #[Computed]
    public function transactionStats()
    {
        // Cache for 15 minutes to avoid heavy queries on every page load
        return Cache::remember('transaction_stats', 900, function () {
            try {
                // Get real transaction data from TransactionDetail model
                $depositApproved = TransactionDetail::selectRaw('
                    COUNT(*) as count,
                    SUM(processed_amount_usd) as total_amount,
                    AVG(processed_amount_usd) as avg_amount
                ')
                    ->where('transaction_type', 'deposit')
                    ->where('status', 'approved')
                    ->first();

                $withdrawalApproved = TransactionDetail::selectRaw('
                    COUNT(*) as count,
                    SUM(processed_amount_usd) as total_amount,
                    AVG(processed_amount_usd) as avg_amount
                ')
                    ->where('transaction_type', 'withdrawal')
                    ->where('status', 'approved')
                    ->first();

                $depositDeclined = TransactionDetail::selectRaw('COUNT(*) as count, SUM(processed_amount_usd) as total_amount')
                    ->where('transaction_type', 'deposit')
                    ->where('status', 'declined')
                    ->first();

                $withdrawalDeclined = TransactionDetail::selectRaw('COUNT(*) as count, SUM(processed_amount_usd) as total_amount')
                    ->where('transaction_type', 'withdrawal')
                    ->where('status', 'declined')
                    ->first();

                return [
                    'deposit_approved' => $depositApproved,
                    'withdrawal_approved' => $withdrawalApproved,
                    'deposit_declined' => $depositDeclined,
                    'withdrawal_declined' => $withdrawalDeclined,
                    'total_approved_amount' => ($depositApproved->total_amount ?? 0) + ($withdrawalApproved->total_amount ?? 0),
                    'total_approved_count' => ($depositApproved->count ?? 0) + ($withdrawalApproved->count ?? 0),
                    'total_declined_count' => ($depositDeclined->count ?? 0) + ($withdrawalDeclined->count ?? 0),
                    'total_all_count' => ($depositApproved->count ?? 0) + ($withdrawalApproved->count ?? 0) + ($depositDeclined->count ?? 0) + ($withdrawalDeclined->count ?? 0),
                ];
            } catch (\Exception $e) {
                return [
                    'deposit_approved' => null,
                    'withdrawal_approved' => null,
                    'deposit_declined' => null,
                    'withdrawal_declined' => null,
                    'total_approved_amount' => 0,
                    'total_approved_count' => 0,
                    'total_declined_count' => 0,
                    'total_all_count' => 0,
                ];
            }
        });
    }

    #[Computed]
    public function volumeMetrics()
    {
        $stats = $this->transactionStats;
        $approvedDeposits = $stats['deposit_approved']->total_amount ?? 0;
        $approvedWithdrawals = $stats['withdrawal_approved']->total_amount ?? 0;
        $netFlow = $approvedDeposits - $approvedWithdrawals;

        return [
            'deposit_volume' => $approvedDeposits,
            'withdrawal_volume' => $approvedWithdrawals,
            'net_flow' => $netFlow,
            'volume_ratio' => $approvedDeposits > 0 ? round(($approvedWithdrawals / $approvedDeposits) * 100, 2) : 0,
        ];
    }

    #[Computed]
    public function successRates()
    {
        $depositApproved = $this->depositsApproved->total_count ?? 0;
        $depositDeclined = $this->depositsDeclined->total_count ?? 0;
        $withdrawalApproved = $this->withdrawalsApproved->total_count ?? 0;
        $withdrawalDeclined = $this->withdrawalsDeclined->total_count ?? 0;

        $totalDeposits = $depositApproved + $depositDeclined;
        $totalWithdrawals = $withdrawalApproved + $withdrawalDeclined;
        $totalApproved = $depositApproved + $withdrawalApproved;
        $totalAll = $totalDeposits + $totalWithdrawals;

        $depositSuccessRate = $totalDeposits > 0 ? round(($depositApproved / $totalDeposits) * 100, 2) : 0;
        $withdrawalSuccessRate = $totalWithdrawals > 0 ? round(($withdrawalApproved / $totalWithdrawals) * 100, 2) : 0;
        $overallSuccessRate = $totalAll > 0 ? round(($totalApproved / $totalAll) * 100, 2) : 0;

        return [
            'deposit_success_rate' => $depositSuccessRate,
            'deposit_approved' => $depositApproved,
            'deposit_total' => $totalDeposits,
            'withdrawal_success_rate' => $withdrawalSuccessRate,
            'withdrawal_approved' => $withdrawalApproved,
            'withdrawal_total' => $totalWithdrawals,
            'overall_success_rate' => $overallSuccessRate,
            'overall_approved' => $totalApproved,
            'overall_total' => $totalAll,
        ];
    }

    #[Computed]
    public function transactionDistribution()
    {
        $data = [];
        $labels = [];
        $colors = [];

        if ($this->depositsApproved->total_count ?? 0 > 0) {
            $labels[] = 'Approved Deposits';
            $data[] = $this->depositsApproved->total_count;
            $colors[] = '#10b981';
        }

        if ($this->withdrawalsApproved->total_count ?? 0 > 0) {
            $labels[] = 'Approved Withdrawals';
            $data[] = $this->withdrawalsApproved->total_count;
            $colors[] = '#3b82f6';
        }

        if ($this->depositsDeclined->total_count ?? 0 > 0) {
            $labels[] = 'Declined Deposits';
            $data[] = $this->depositsDeclined->total_count;
            $colors[] = '#f59e0b';
        }

        if ($this->withdrawalsDeclined->total_count ?? 0 > 0) {
            $labels[] = 'Declined Withdrawals';
            $data[] = $this->withdrawalsDeclined->total_count;
            $colors[] = '#ef4444';
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
        ];
    }

    #[Computed]
    public function topTransactionsList()
    {
        $allTopTransactions = [];

        // Get top transactions from each category
        foreach (['depositsApproved', 'depositsDeclined', 'withdrawalsApproved', 'withdrawalsDeclined'] as $property) {
            $data = $this->$property;
            if ($data && property_exists($data, 'top_transactions') && $data->top_transactions) {
                $transactions = json_decode($data->top_transactions, true);
                if (is_array($transactions)) {
                    foreach ($transactions as $tx) {
                        if (isset($tx['amount']) && $tx['amount'] > 0) {
                            $tx['type'] = strpos($property, 'deposit') !== false ? 'deposit' : 'withdrawal';
                            $tx['status'] = strpos($property, 'Approved') !== false ? 'approved' : 'declined';
                            $allTopTransactions[] = $tx;
                        }
                    }
                }
            }
        }

        // Sort by amount descending and take top 10
        usort($allTopTransactions, function ($a, $b) {
            return ($b['amount'] ?? 0) <=> ($a['amount'] ?? 0);
        });

        return array_slice($allTopTransactions, 0, 10);
    }

    #[Computed]
    public function monthlyVolumeData()
    {
        // Cache for 15 minutes since this queries cached analytics data
        return Cache::remember('monthly_volume_data', 900, function () {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();

            return $this->getVolumeDataForPeriod($startDate, $endDate, 'daily');
        });
    }

    #[Computed]
    public function weeklyVolumeData()
    {
        // Cache for 15 minutes since this queries cached analytics data
        return Cache::remember('weekly_volume_data', 900, function () {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();

            return $this->getVolumeDataForPeriod($startDate, $endDate, 'daily');
        });
    }

    private function getVolumeDataForPeriod($startDate, $endDate, $groupBy = 'daily')
    {
        // Determine the period type based on the date range
        $periodType = $this->determinePeriodType($startDate, $endDate);

        // Fetch data from cache for each transaction type and status
        $depositApproved = TransactionAnalyticsCache::where('transaction_type', 'deposit')
            ->where('status', 'approved')
            ->where('period_type', $periodType)
            ->orderBy('synced_at', 'desc')
            ->first();

        $depositDeclined = TransactionAnalyticsCache::where('transaction_type', 'deposit')
            ->where('status', 'declined')
            ->where('period_type', $periodType)
            ->orderBy('synced_at', 'desc')
            ->first();

        $withdrawalApproved = TransactionAnalyticsCache::where('transaction_type', 'withdrawal')
            ->where('status', 'approved')
            ->where('period_type', $periodType)
            ->orderBy('synced_at', 'desc')
            ->first();

        $withdrawalDeclined = TransactionAnalyticsCache::where('transaction_type', 'withdrawal')
            ->where('status', 'declined')
            ->where('period_type', $periodType)
            ->orderBy('synced_at', 'desc')
            ->first();

        // Extract chart data or use empty arrays as fallback
        $labels = [];
        $depositApprovedData = [];
        $depositDeclinedData = [];
        $withdrawalApprovedData = [];
        $withdrawalDeclinedData = [];

        // Use the first available dataset for labels
        $firstData = $depositApproved ?? $depositDeclined ?? $withdrawalApproved ?? $withdrawalDeclined;
        if ($firstData && $firstData->chart_data) {
            $chartData = $firstData->chart_data;
            if (isset($chartData['labels'])) {
                $labels = array_map(function ($date) {
                    return Carbon::parse($date)->format('M d');
                }, $chartData['labels']);
            }
        }

        // Extract transaction count data for each type
        if ($depositApproved && $depositApproved->chart_data && isset($depositApproved->chart_data['datasets'][0]['data'])) {
            $depositApprovedData = $depositApproved->chart_data['datasets'][0]['data'];
        }

        if ($depositDeclined && $depositDeclined->chart_data && isset($depositDeclined->chart_data['datasets'][0]['data'])) {
            $depositDeclinedData = $depositDeclined->chart_data['datasets'][0]['data'];
        }

        if ($withdrawalApproved && $withdrawalApproved->chart_data && isset($withdrawalApproved->chart_data['datasets'][0]['data'])) {
            $withdrawalApprovedData = $withdrawalApproved->chart_data['datasets'][0]['data'];
        }

        if ($withdrawalDeclined && $withdrawalDeclined->chart_data && isset($withdrawalDeclined->chart_data['datasets'][0]['data'])) {
            $withdrawalDeclinedData = $withdrawalDeclined->chart_data['datasets'][0]['data'];
        }

        // Ensure all arrays have the same length
        $dataLength = count($labels);
        $depositApprovedData = array_pad($depositApprovedData, $dataLength, 0);
        $depositDeclinedData = array_pad($depositDeclinedData, $dataLength, 0);
        $withdrawalApprovedData = array_pad($withdrawalApprovedData, $dataLength, 0);
        $withdrawalDeclinedData = array_pad($withdrawalDeclinedData, $dataLength, 0);

        return [
            'labels' => $labels,
            'depositApproved' => $depositApprovedData,
            'depositDeclined' => $depositDeclinedData,
            'withdrawalApproved' => $withdrawalApprovedData,
            'withdrawalDeclined' => $withdrawalDeclinedData,
        ];
    }

    private function determinePeriodType($startDate, $endDate)
    {
        // Check if it's current month
        if ($startDate->isSameMonth(now()) && $startDate->day === 1) {
            return 'current_month';
        }

        // Check if it's last 7 days
        if ($startDate->diffInDays(now()) <= 7 && $endDate->isToday()) {
            return 'last_7_days';
        }

        // Default fallback
        return 'daily';
    }

    private function checkForRecentData()
    {
        $recentData = TransactionAnalyticsCache::where('synced_at', '>', now()->subHours(2))->first();

        return $recentData !== null;
    }

    #[Computed]
    public function topDeposits()
    {
        // Cache based on date range and limit to avoid heavy queries
        $cacheKey = "top_deposits_{$this->dateRange}_{$this->topLimit}";

        return Cache::remember($cacheKey, 900, function () {
            try {
                $endDate = Carbon::now();
                $startDate = $endDate->copy()->subDays((int) $this->dateRange);

                return TransactionDetail::getTopTransactions('deposit', $this->topLimit, $startDate, $endDate);
            } catch (\Exception $e) {
                return collect();
            }
        });
    }

    #[Computed]
    public function topWithdrawals()
    {
        // Cache based on date range and limit to avoid heavy queries
        $cacheKey = "top_withdrawals_{$this->dateRange}_{$this->topLimit}";

        return Cache::remember($cacheKey, 900, function () {
            try {
                $endDate = Carbon::now();
                $startDate = $endDate->copy()->subDays((int) $this->dateRange);

                return TransactionDetail::getTopTransactions('withdrawal', $this->topLimit, $startDate, $endDate);
            } catch (\Exception $e) {
                return collect();
            }
        });
    }

    #[Computed]
    public function repeatDepositUsers()
    {
        // Cache based on date range and limit to avoid heavy GROUP BY queries
        $cacheKey = "repeat_deposit_users_{$this->dateRange}_{$this->topLimit}";

        return Cache::remember($cacheKey, 900, function () {
            try {
                $endDate = Carbon::now();
                $startDate = $endDate->copy()->subDays((int) $this->dateRange);

                return TransactionDetail::getRepeatTransactionUsers('deposit', $this->topLimit, $startDate, $endDate);
            } catch (\Exception $e) {
                return collect();
            }
        });
    }

    #[Computed]
    public function repeatWithdrawalUsers()
    {
        // Cache based on date range and limit to avoid heavy GROUP BY queries
        $cacheKey = "repeat_withdrawal_users_{$this->dateRange}_{$this->topLimit}";

        return Cache::remember($cacheKey, 900, function () {
            try {
                $endDate = Carbon::now();
                $startDate = $endDate->copy()->subDays((int) $this->dateRange);

                return TransactionDetail::getRepeatTransactionUsers('withdrawal', $this->topLimit, $startDate, $endDate);
            } catch (\Exception $e) {
                return collect();
            }
        });
    }

    public function render()
    {
        return view('livewire.transaction-dashboard');
    }
}
