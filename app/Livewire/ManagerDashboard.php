<?php

namespace App\Livewire;

use App\Models\Manager;
use App\Models\Ticket;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ManagerDashboard extends Component
{
    /**
     * Manager response counts for last 7 days.
     */
    #[Computed]
    public function responsesLast7Days(): array
    {
        try {
            $managerIds = Ticket::managerResponseCounts(7);
            $managers = Manager::whereIn('manager_id', array_keys($managerIds))->get()->keyBy('manager_id');

            $result = [];
            foreach ($managerIds as $managerId => $count) {
                $manager = $managers[$managerId] ?? null;
                $result[] = [
                    'manager_id' => $managerId,
                    'name' => $manager?->full_name ?? 'Unknown Manager',
                    'email' => $manager?->email,
                    'phone' => $manager?->phone,
                    'responses' => $count,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Manager response counts for last 30 days.
     */
    #[Computed]
    public function responsesLast30Days(): array
    {
        try {
            $managerIds = Ticket::managerResponseCounts(30);
            $managers = Manager::whereIn('manager_id', array_keys($managerIds))->get()->keyBy('manager_id');

            $result = [];
            foreach ($managerIds as $managerId => $count) {
                $manager = $managers[$managerId] ?? null;
                $result[] = [
                    'manager_id' => $managerId,
                    'name' => $manager?->full_name ?? 'Unknown Manager',
                    'email' => $manager?->email,
                    'phone' => $manager?->phone,
                    'responses' => $count,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Manager response counts for last 365 days (1 year).
     */
    #[Computed]
    public function responsesLastYear(): array
    {
        try {
            $managerIds = Ticket::managerResponseCounts(365);
            $managers = Manager::whereIn('manager_id', array_keys($managerIds))->get()->keyBy('manager_id');

            $result = [];
            foreach ($managerIds as $managerId => $count) {
                $manager = $managers[$managerId] ?? null;
                $result[] = [
                    'manager_id' => $managerId,
                    'name' => $manager?->full_name ?? 'Unknown Manager',
                    'email' => $manager?->email,
                    'phone' => $manager?->phone,
                    'responses' => $count,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function render()
    {
        return view('livewire.manager-dashboard');
    }
}
