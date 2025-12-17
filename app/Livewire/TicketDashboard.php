<?php

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TicketDashboard extends Component
{
    public $error = null;

    public $dateRange = 30;

    public $categoryLimit = 9;

    #[Computed]
    public function statusCounts(): array
    {
        try {
            return Ticket::statusCounts();
        } catch (\Exception $e) {
            $this->error = 'Failed to load ticket status counts: '.$e->getMessage();

            return ['open' => 0, 'closed' => 0, 'other' => 0, 'total' => 0];
        }
    }

    #[Computed]
    public function dailyChartData(): array
    {
        try {
            return Ticket::dailyCounts((int) $this->dateRange);
        } catch (\Exception $e) {
            $this->error = 'Failed to load daily ticket data: '.$e->getMessage();

            return ['labels' => [], 'open' => [], 'closed' => []];
        }
    }

    #[Computed]
    public function dailyChartData7(): array
    {
        try {
            return Ticket::dailyCounts(7);
        } catch (\Exception $e) {
            $this->error = 'Failed to load 7-day ticket data: '.$e->getMessage();

            return ['labels' => [], 'total' => []];
        }
    }

    #[Computed]
    public function dailyChartData30(): array
    {
        try {
            return Ticket::dailyCounts(30);
        } catch (\Exception $e) {
            $this->error = 'Failed to load 30-day ticket data: '.$e->getMessage();

            return ['labels' => [], 'total' => []];
        }
    }

    #[Computed]
    public function dailyChartDataAll(): array
    {
        try {
            return Ticket::dailyCountsAll();
        } catch (\Exception $e) {
            $this->error = 'Failed to load all-time ticket data: '.$e->getMessage();

            return ['labels' => [], 'total' => []];
        }
    }

    #[Computed]
    public function categoryChartData(): array
    {
        try {
            return Ticket::categoryCounts((int) $this->categoryLimit);
        } catch (\Exception $e) {
            $this->error = 'Failed to load category ticket data: '.$e->getMessage();

            return ['labels' => [], 'open' => [], 'closed' => [], 'totals' => []];
        }
    }

    #[Computed]
    public function latestCreatedAt()
    {
        try {
            return Ticket::latestTicketCreatedAt();
        } catch (\Exception $e) {
            return null;
        }
    }

    #[Computed]
    public function averageProcessingTime(): ?string
    {
        try {
            $hours = Ticket::averageProcessingTime();
            if ($hours === null) {
                return null;
            }
            return number_format($hours, 1).' hours';
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.ticket-dashboard');
    }
}
