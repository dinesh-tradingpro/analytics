<?php

namespace App\Livewire;

use App\Models\TransactionAnalyticsCache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionDashboard extends Component
{
    public $selectedPeriod = 'daily';

    public $showTopTransactions = true;

    public $error = null;

    public function mount()
    {
        // Check if we have fresh data, if not suggest sync
        $hasRecentData = $this->checkForRecentData();
        if (! $hasRecentData) {
            session()->flash('sync_needed', 'Transaction data is stale or missing. Consider running: php artisan transactions:sync');
        }
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
    public function volumeMetrics()
    {
        $approvedDeposits = $this->depositsApproved->total_amount ?? 0;
        $approvedWithdrawals = $this->withdrawalsApproved->total_amount ?? 0;
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
        $totalDeposits = ($this->depositsApproved->total_count ?? 0) + ($this->depositsDeclined->total_count ?? 0);
        $totalWithdrawals = ($this->withdrawalsApproved->total_count ?? 0) + ($this->withdrawalsDeclined->total_count ?? 0);

        $depositSuccessRate = $totalDeposits > 0 ? round((($this->depositsApproved->total_count ?? 0) / $totalDeposits) * 100, 2) : 0;
        $withdrawalSuccessRate = $totalWithdrawals > 0 ? round((($this->withdrawalsApproved->total_count ?? 0) / $totalWithdrawals) * 100, 2) : 0;

        return [
            'deposit_success_rate' => $depositSuccessRate,
            'withdrawal_success_rate' => $withdrawalSuccessRate,
            'overall_success_rate' => ($totalDeposits + $totalWithdrawals) > 0 ?
                round(((($this->depositsApproved->total_count ?? 0) + ($this->withdrawalsApproved->total_count ?? 0)) / ($totalDeposits + $totalWithdrawals)) * 100, 2) : 0,
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
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        return $this->getVolumeDataForPeriod($startDate, $endDate, 'daily');
    }

    #[Computed]
    public function weeklyVolumeData()
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        
        return $this->getVolumeDataForPeriod($startDate, $endDate, 'daily');
    }

    private function getVolumeDataForPeriod($startDate, $endDate, $groupBy = 'daily')
    {
        // This is a simplified version - in production you'd query your actual transaction data
        // For now, we'll generate sample data based on the period
        $data = [];
        $labels = [];
        $depositApprovedData = [];
        $depositDeclinedData = [];
        $withdrawalApprovedData = [];
        $withdrawalDeclinedData = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $labels[] = $current->format('M d');
            // Generate sample data - replace with actual database queries
            $depositApprovedData[] = rand(800, 40000);
            $depositDeclinedData[] = rand(200, 8000);
            $withdrawalApprovedData[] = rand(600, 35000);
            $withdrawalDeclinedData[] = rand(150, 6000);
            $current->addDay();
        }
        
        return [
            'labels' => $labels,
            'depositApproved' => $depositApprovedData,
            'depositDeclined' => $depositDeclinedData,
            'withdrawalApproved' => $withdrawalApprovedData,
            'withdrawalDeclined' => $withdrawalDeclinedData
        ];
    }

    public function toggleTopTransactions()
    {
        $this->showTopTransactions = ! $this->showTopTransactions;
    }

    private function checkForRecentData()
    {
        $recentData = TransactionAnalyticsCache::where('synced_at', '>', now()->subHours(2))->first();

        return $recentData !== null;
    }

    public function render()
    {
        return view('livewire.transaction-dashboard');
    }
}
