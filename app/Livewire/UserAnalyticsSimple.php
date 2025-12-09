<?php

namespace App\Livewire;

use App\Models\UserAnalyticsCache;
use Livewire\Component;

class UserAnalyticsSimple extends Component
{
    public $activeUsers = [];

    public $newUsers = [];

    public $inactiveUsers = [];

    public $loading = false;

    public $error = null;

    // Chart data for frontend - separate charts for new users
    public $activeUsersChartData = [];

    public $inactiveUsersChartData = [];

    public $inactiveUsersStatusBreakdownData = [];

    // New users charts by different time periods
    public $newUsersWeeklyChartData = [];

    public $newUsersMonthlyChartData = [];

    public $newUsersEachMonthChartData = [];

    public $newUsersYearlyChartData = [];

    public $newUsersEachYearChartData = [];

    public $newUsersAllTimeChartData = [];

    // Computed metrics
    public $newUsersToday = 0;

    public $newUsersLast7DaysAvg = 0;

    public $newUsersThisMonthAvg = 0;

    public function mount()
    {
        $this->loadAnalyticsData();
    }

    public function refreshData()
    {
        $this->loading = true;
        $this->error = null;

        try {
            // Run the sync command to refresh cache with new criteria
            \Artisan::call('analytics:sync-new');

            // Reload the analytics data
            $this->loadAnalyticsData();

            session()->flash('message', 'User analytics data refreshed successfully!');
        } catch (\Exception $e) {
            $this->error = 'Failed to refresh data: '.$e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    protected function loadAnalyticsData()
    {
        $this->loading = true;

        try {
            // Load daily snapshots for active users (last 365 days)
            $activeUsersData = UserAnalyticsCache::getCombinedDailyChartData('active_users_daily', null, null, 365);
            $this->activeUsers = (object) [
                'chart_data' => $activeUsersData['chart_data'],
                'total_count' => $activeUsersData['total_count'],
                'metadata' => ['snapshot_count' => $activeUsersData['record_count']],
            ];

            // Load daily snapshots for inactive users (last 365 days)
            $inactiveUsersData = UserAnalyticsCache::getCombinedDailyChartData('inactive_users_daily', null, null, 365);
            $this->inactiveUsers = (object) [
                'chart_data' => $inactiveUsersData['chart_data'],
                'total_count' => $inactiveUsersData['total_count'],
                'metadata' => ['snapshot_count' => $inactiveUsersData['record_count']],
            ];

            // Load cached data for other metrics
            $this->newUsers = UserAnalyticsCache::getLatest('new_users_new') ?? [];

            // Calculate new users metrics
            $this->calculateNewUsersMetrics();

            // Prepare chart data for each section
            $this->prepareActiveUsersChart();
            $this->prepareInactiveUsersChart();
            $this->prepareNewUsersCharts();

        } catch (\Exception $e) {
            $this->error = 'Failed to load analytics data: '.$e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    protected function prepareActiveUsersChart()
    {
        $data = $this->activeUsers->chart_data ?? [];
        // For daily snapshots, show each day individually
        $this->activeUsersChartData = [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];
    }

    protected function prepareInactiveUsersChart()
    {
        $data = $this->inactiveUsers->chart_data ?? [];
        // For daily snapshots, show each day individually
        $this->inactiveUsersChartData = [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];

        // Get the latest snapshot's status breakdown for the pie chart
        $latestSnapshot = UserAnalyticsCache::where('metric_type', 'inactive_users_daily')
            ->orderBy('data_date', 'desc')
            ->first();

        $metadata = $latestSnapshot->metadata ?? [];
        $statusBreakdown = $metadata['trading_status_breakdown'] ?? [];

        $this->inactiveUsersStatusBreakdownData = [
            'labels' => array_keys($statusBreakdown),
            'data' => array_values($statusBreakdown),
        ];
    }

    protected function calculateNewUsersMetrics()
    {
        $data = $this->newUsers->chart_data ?? [];
        $today = \Carbon\Carbon::now()->format('Y-m-d');

        // Get today's count
        $this->newUsersToday = $data[$today] ?? 0;

        // Calculate last 7 days average
        $last7DaysTotal = 0;
        $last7DaysCount = 0;
        for ($i = 0; $i < 7; $i++) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            if (isset($data[$date])) {
                $last7DaysTotal += $data[$date];
                $last7DaysCount++;
            }
        }
        $this->newUsersLast7DaysAvg = $last7DaysCount > 0 ? round($last7DaysTotal / $last7DaysCount, 1) : 0;

        // Calculate this month average
        $currentMonth = \Carbon\Carbon::now()->format('Y-m');
        $thisMonthTotal = 0;
        $thisMonthCount = 0;
        foreach ($data as $date => $count) {
            $month = \Carbon\Carbon::parse($date)->format('Y-m');
            if ($month === $currentMonth) {
                $thisMonthTotal += $count;
                $thisMonthCount++;
            }
        }
        $this->newUsersThisMonthAvg = $thisMonthCount > 0 ? round($thisMonthTotal / $thisMonthCount, 1) : 0;
    }

    protected function prepareNewUsersCharts()
    {
        $data = $this->newUsers->chart_data ?? [];

        // Prepare different time period charts
        $this->newUsersWeeklyChartData = $this->formatNewUsersWeekly($data);
        $this->newUsersMonthlyChartData = $this->formatNewUsersMonthly($data);
        $this->newUsersEachMonthChartData = $this->formatNewUsersEachMonth($data);
        $this->newUsersYearlyChartData = $this->formatNewUsersYearly($data);
        $this->newUsersEachYearChartData = $this->formatNewUsersEachYear($data);
        $this->newUsersAllTimeChartData = $this->formatNewUsersAllTime($data);
    }

    protected function formatChartData($rawData, $period)
    {
        if (empty($rawData)) {
            return ['labels' => [], 'data' => []];
        }

        // Group data based on the selected time period
        $groupedData = [];

        foreach ($rawData as $date => $count) {
            $groupKey = $this->getGroupKey($date, $period);
            $groupedData[$groupKey] = ($groupedData[$groupKey] ?? 0) + $count;
        }

        // Sort by date
        ksort($groupedData);

        return [
            'labels' => array_keys($groupedData),
            'data' => array_values($groupedData),
        ];
    }

    protected function getGroupKey($date, $period)
    {
        $dateObj = \Carbon\Carbon::parse($date);

        return match ($period) {
            'daily' => $dateObj->format('Y-m-d'),
            'weekly' => $dateObj->format('Y-\WW'),
            'monthly' => $dateObj->format('Y-m'),
            'yearly' => $dateObj->format('Y'),
            'all' => 'all-time',
            default => $dateObj->format('Y-m-d'),
        };
    }

    protected function formatNewUsersWeekly($rawData)
    {
        $last7Days = [];
        $now = \Carbon\Carbon::now();

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $last7Days[$date] = $rawData[$date] ?? 0;
        }

        return [
            'labels' => array_keys($last7Days),
            'data' => array_values($last7Days),
        ];
    }

    protected function formatNewUsersMonthly($rawData)
    {
        $currentMonth = \Carbon\Carbon::now()->format('Y-m');
        $monthlyData = [];

        foreach ($rawData as $date => $count) {
            $month = \Carbon\Carbon::parse($date)->format('Y-m');
            if ($month === $currentMonth) {
                $monthlyData[$date] = $count;
            }
        }

        ksort($monthlyData);

        return [
            'labels' => array_keys($monthlyData),
            'data' => array_values($monthlyData),
        ];
    }

    protected function formatNewUsersEachMonth($rawData)
    {
        $monthlyTotals = [];

        foreach ($rawData as $date => $count) {
            $month = \Carbon\Carbon::parse($date)->format('Y-m');
            $monthlyTotals[$month] = ($monthlyTotals[$month] ?? 0) + $count;
        }

        // Get last 12 months
        $last12Months = [];
        $now = \Carbon\Carbon::now();

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->format('Y-m');
            $last12Months[$month] = $monthlyTotals[$month] ?? 0;
        }

        return [
            'labels' => array_keys($last12Months),
            'data' => array_values($last12Months),
        ];
    }

    protected function formatNewUsersYearly($rawData)
    {
        $currentYear = \Carbon\Carbon::now()->format('Y');
        $yearlyData = [];

        foreach ($rawData as $date => $count) {
            $year = \Carbon\Carbon::parse($date)->format('Y');
            if ($year === $currentYear) {
                $yearlyData[$date] = $count;
            }
        }

        ksort($yearlyData);

        return [
            'labels' => array_keys($yearlyData),
            'data' => array_values($yearlyData),
        ];
    }

    protected function formatNewUsersEachYear($rawData)
    {
        $yearlyTotals = [];

        foreach ($rawData as $date => $count) {
            $year = \Carbon\Carbon::parse($date)->format('Y');
            $yearlyTotals[$year] = ($yearlyTotals[$year] ?? 0) + $count;
        }

        ksort($yearlyTotals);

        return [
            'labels' => array_keys($yearlyTotals),
            'data' => array_values($yearlyTotals),
        ];
    }

    protected function formatNewUsersAllTime($rawData)
    {
        ksort($rawData);
        $data = array_values($rawData);

        // Limit y-axis to 500 by capping values
        $cappedData = array_map(function ($value) {
            return min($value, 500);
        }, $data);

        return [
            'labels' => array_keys($rawData),
            'data' => $cappedData,
        ];
    }

    public function render()
    {
        return view('livewire.user-analytics-simple');
    }
}
