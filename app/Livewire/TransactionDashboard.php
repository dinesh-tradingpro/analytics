<?php

namespace App\Livewire;

use App\Models\TransactionAnalyticsCache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionDashboard extends Component
{
    public $selectedPeriod = 'all_time';

    public $showTopTransactions = true;

    public function mount()
    {
        // Check if we have fresh data, if not suggest sync
        $hasRecentData = $this->checkForRecentData();
        if (! $hasRecentData) {
            session()->flash('sync_needed', 'Transaction data is stale or missing. Consider running: php artisan transactions:sync');
        }
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
                        'chart_data' => $data->chart_data,
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

    public function updatedSelectedPeriod()
    {
        $this->dispatch('period-changed', $this->selectedPeriod);
    }

    public function toggleTopTransactions()
    {
        $this->showTopTransactions = ! $this->showTopTransactions;
    }

    public function refresh()
    {
        $this->dispatch('refresh-charts');
        session()->flash('message', 'Dashboard refreshed successfully!');
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
