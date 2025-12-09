<?php

namespace App\Livewire;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Livewire\Component;

class TransactionInsights extends Component
{
    public $topDeposits = [];

    public $topWithdrawals = [];

    public $repeatDepositUsers = [];

    public $repeatWithdrawalUsers = [];

    public $loading = false;

    public $error = null;

    // Filter options
    public $dateRange = '30'; // days

    public $topLimit = 10;

    public function mount()
    {
        $this->loadInsights();
    }

    public function updatedDateRange()
    {
        $this->loadInsights();
    }

    public function updatedTopLimit()
    {
        $this->loadInsights();
    }

    public function loadInsights()
    {
        $this->loading = true;
        $this->error = null;

        try {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays((int) $this->dateRange);

            // Get top transactions by amount
            $this->topDeposits = TransactionDetail::getTopTransactions('deposit', $this->topLimit, $startDate, $endDate);
            $this->topWithdrawals = TransactionDetail::getTopTransactions('withdrawal', $this->topLimit, $startDate, $endDate);

            // Get repeat transaction users
            $this->repeatDepositUsers = TransactionDetail::getRepeatTransactionUsers('deposit', $this->topLimit, $startDate, $endDate);
            $this->repeatWithdrawalUsers = TransactionDetail::getRepeatTransactionUsers('withdrawal', $this->topLimit, $startDate, $endDate);

        } catch (\Exception $e) {
            $this->error = 'Failed to load insights: '.$e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.transaction-insights')->layout('components.layouts.modern', ['current' => 'transactions']);
    }
}
