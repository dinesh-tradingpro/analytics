<?php

namespace App\Livewire;

use App\Http\Controllers\MarketingController;
use Livewire\Component;

class Dashboard extends Component
{
    public $newUsersData = [];

    public $activeUsersData = [];

    public $inactiveUsersData = [];

    public $loading = false;

    public $loadingProgress = [];

    public $error = null;

    // Time period filters - individual for each chart
    public $newUsersTimePeriod = 'monthly';
    public $activeUsersTimePeriod = 'weekly'; 
    public $inactiveUsersTimePeriod = 'all';

    // Chart type preferences - individual for each chart
    public $newUsersChartType = 'line';
    public $activeUsersChartType = 'area';
    public $inactiveUsersChartType = 'doughnut';

    // Auto-scaling for charts
    public $autoScale = true;

    // Public properties for chart data to ensure JavaScript access
    public $newUsersChartLabels = [];

    public $newUsersChartValues = [];

    public $activeUsersChartLabels = [];

    public $activeUsersChartValues = [];

    public $inactiveUsersChartLabels = [];

    public $inactiveUsersChartValues = [];

    protected $marketingController;

    public function boot()
    {
        $this->marketingController = new MarketingController;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function updatedNewUsersTimePeriod()
    {
        logger('New users time period updated to: '.$this->newUsersTimePeriod);
        $this->prepareNewUsersChartData();
        $this->dispatch('newUsersChartUpdated');
    }

    public function updatedActiveUsersTimePeriod()
    {
        logger('Active users time period updated to: '.$this->activeUsersTimePeriod);
        $this->prepareActiveUsersChartData();
        $this->dispatch('activeUsersChartUpdated');
    }

    public function updatedInactiveUsersTimePeriod()
    {
        logger('Inactive users time period updated to: '.$this->inactiveUsersTimePeriod);
        $this->prepareInactiveUsersChartData();
        $this->dispatch('inactiveUsersChartUpdated');
    }

    public function updateNewUsersChartType($type)
    {
        $this->newUsersChartType = $type;
        $this->dispatch('newUsersChartUpdated');
    }

    public function updateActiveUsersChartType($type)
    {
        $this->activeUsersChartType = $type;
        $this->dispatch('activeUsersChartUpdated');
    }

    public function updateInactiveUsersChartType($type)
    {
        $this->inactiveUsersChartType = $type;
        $this->dispatch('inactiveUsersChartUpdated');
    }

    public function refreshData()
    {
        logger('Refreshing dashboard data...');

        // Run the sync command to refresh cache
        \Artisan::call('analytics:sync');

        // Reload the dashboard
        $this->loadDashboardData();

        // Show notification that data was refreshed
        session()->flash('message', 'Dashboard data refreshed successfully!');
    }

    public function toggleAutoScale()
    {
        $this->autoScale = ! $this->autoScale;
        $this->dispatch('scaleUpdated', $this->autoScale);
    }

    protected function prepareChartData()
    {
        logger('Preparing all chart data...');
        $this->prepareNewUsersChartData();
        $this->prepareActiveUsersChartData();
        $this->prepareInactiveUsersChartData();
    }

    protected function prepareNewUsersChartData()
    {
        logger('Preparing new users chart data...');
        $newChart = $this->getNewUsersChartDataProperty();
        $this->newUsersChartLabels = $newChart['labels'];
        $this->newUsersChartValues = $newChart['data'];
        logger('New users chart prepared: '.count($this->newUsersChartLabels).' labels, '.count($this->newUsersChartValues).' values');
    }

    protected function prepareActiveUsersChartData()
    {
        logger('Preparing active users chart data...');
        $activeChart = $this->getActiveUsersChartDataProperty();
        $this->activeUsersChartLabels = $activeChart['labels'];
        $this->activeUsersChartValues = $activeChart['data'];
        logger('Active users chart prepared: '.count($this->activeUsersChartLabels).' labels, '.count($this->activeUsersChartValues).' values');
    }

    protected function prepareInactiveUsersChartData()
    {
        logger('Preparing inactive users chart data...');
        $inactiveChart = $this->getInactiveUsersChartDataProperty();
        $this->inactiveUsersChartLabels = $inactiveChart['labels'];
        $this->inactiveUsersChartValues = $inactiveChart['data'];
        logger('Inactive users chart prepared: '.count($this->inactiveUsersChartLabels).' labels, '.count($this->inactiveUsersChartValues).' values');
    }

    public function loadDashboardData()
    {
        $this->loading = true;
        $this->error = null;
        $this->loadingProgress = [
            'new_users' => 'Loading...',
            'active_users' => 'Loading...',
            'inactive_users' => 'Loading...',
        ];

        logger('Loading dashboard data from cache...');

        try {
            // Load cached data from database
            $newUsersCache = \App\Models\UserAnalyticsCache::where('metric_type', 'new_users')
                ->latest('data_date')
                ->first();

            $activeUsersCache = \App\Models\UserAnalyticsCache::where('metric_type', 'active_users')
                ->latest('data_date')
                ->first();

            $inactiveUsersCache = \App\Models\UserAnalyticsCache::where('metric_type', 'inactive_users')
                ->latest('data_date')
                ->first();

            if ($newUsersCache && $activeUsersCache && $inactiveUsersCache) {
                // Extract chart data from cache with proper structure for view
                $this->newUsersData = [
                    'total_count' => $newUsersCache->total_count,
                    'chart_data' => $newUsersCache->chart_data,
                    'cached_at' => $newUsersCache->synced_at,
                    'date_range' => [
                        'start' => $newUsersCache->data_date->format('Y-m-d'),
                        'end' => $newUsersCache->data_date->format('Y-m-d'),
                    ],
                ];

                $this->activeUsersData = [
                    'total_count' => $activeUsersCache->total_count,
                    'chart_data' => $activeUsersCache->chart_data,
                    'cached_at' => $activeUsersCache->synced_at,
                ];

                $this->inactiveUsersData = [
                    'total_count' => $inactiveUsersCache->total_count,
                    'chart_data' => $inactiveUsersCache->chart_data,
                    'cached_at' => $inactiveUsersCache->synced_at,
                    'breakdown' => [
                        'dormant_3_6_months' => $inactiveUsersCache->chart_data['dormant 3-6 months'] ?? 0,
                        'dormant_6_12_months' => $inactiveUsersCache->chart_data['dormant 6-12 months'] ?? 0,
                        'dormant_1_year_plus' => $inactiveUsersCache->chart_data['dormant more than 1 year'] ?? 0,
                    ],
                ];

                logger('Cached data loaded - New: '.$newUsersCache->total_count.', Active: '.$activeUsersCache->total_count.', Inactive: '.$inactiveUsersCache->total_count);

                // Prepare chart data as public properties
                $this->prepareChartData();

                $this->loadingProgress = [
                    'new_users' => 'Complete',
                    'active_users' => 'Complete',
                    'inactive_users' => 'Complete',
                ];

                // Dispatch event to update charts
                $this->dispatch('dashboardUpdated');
            } else {
                // Fallback to API if no cached data
                logger('No cached data found, running sync command...');

                // Run the sync command to populate cache
                \Artisan::call('analytics:sync');

                // Reload from cache after sync
                $this->loadDashboardData();

                return;
            }
        } catch (\Exception $e) {
            $this->error = 'An error occurred while loading dashboard data: '.$e->getMessage();
            logger('Dashboard data exception: '.$e->getMessage());

            $this->loadingProgress = [
                'new_users' => 'Error',
                'active_users' => 'Error',
                'inactive_users' => 'Error',
            ];
        }

        $this->loading = false;
        logger('Dashboard loading complete');
    }

    public function getNewUsersChartDataProperty()
    {
        if (empty($this->newUsersData['chart_data'])) {
            return ['labels' => [], 'data' => []];
        }

        return $this->aggregateDataByTimePeriod($this->newUsersData['chart_data'], $this->newUsersTimePeriod);
    }

    public function getActiveUsersChartDataProperty()
    {
        if (empty($this->activeUsersData['chart_data'])) {
            return ['labels' => [], 'data' => []];
        }

        return $this->aggregateDataByTimePeriod($this->activeUsersData['chart_data'], $this->activeUsersTimePeriod);
    }

    public function getInactiveUsersChartDataProperty()
    {
        if (empty($this->inactiveUsersData['chart_data'])) {
            return ['labels' => [], 'data' => []];
        }

        // For inactive users, if time period is 'all', show categorical data
        if ($this->inactiveUsersTimePeriod === 'all') {
            return [
                'labels' => array_keys($this->inactiveUsersData['chart_data']),
                'data' => array_values($this->inactiveUsersData['chart_data']),
            ];
        }

        // Otherwise aggregate by time period
        return $this->aggregateDataByTimePeriod($this->inactiveUsersData['chart_data'], $this->inactiveUsersTimePeriod);
    }

    /**
     * Aggregate data based on selected time period
     */
    private function aggregateDataByTimePeriod($chartData, $timePeriod = 'all')
    {
        if ($timePeriod === 'all') {
            logger("Time period 'all' - showing ".count($chartData).' data points');

            return [
                'labels' => array_keys($chartData),
                'data' => array_values($chartData),
            ];
        }

        $aggregated = [];
        $now = new \DateTime;

        // Define date ranges for each period
        $dateRange = $this->getDateRangeForPeriod($now, $timePeriod);

        logger("Time period '{$timePeriod}' - filtering from {$dateRange['start']->format('Y-m-d')} to {$dateRange['end']->format('Y-m-d')}");

        $originalCount = count($chartData);
        $filteredCount = 0;

        foreach ($chartData as $date => $value) {
            if ($date === 'never') {
                $aggregated['Never logged in'] = ($aggregated['Never logged in'] ?? 0) + $value;

                continue;
            }

            try {
                $dateObj = new \DateTime($date);

                // Skip dates outside the relevant range
                if ($dateObj < $dateRange['start'] || $dateObj > $dateRange['end']) {
                    continue;
                }

                $filteredCount++;
                $key = $this->getAggregationKey($dateObj, $timePeriod);
                $aggregated[$key] = ($aggregated[$key] ?? 0) + $value;
            } catch (\Exception $e) {
                // Skip invalid dates
                continue;
            }
        }

        logger("Filtered {$filteredCount} out of {$originalCount} data points, aggregated to ".count($aggregated).' periods');

        // Sort by the actual date value for proper chronological order
        if ($timePeriod === 'weekly') {
            // Special handling for weekly data to sort by week number
            uksort($aggregated, function ($a, $b) {
                // Extract week numbers from labels like "Nov 10 (Week 46)"
                preg_match('/Week (\d+)/', $a, $matchesA);
                preg_match('/Week (\d+)/', $b, $matchesB);
                if (isset($matchesA[1]) && isset($matchesB[1])) {
                    return intval($matchesA[1]) <=> intval($matchesB[1]);
                }

                return strcmp($a, $b);
            });
        } else {
            // Regular sorting for other time periods
            ksort($aggregated);
        }

        return [
            'labels' => array_keys($aggregated),
            'data' => array_values($aggregated),
        ];
    }

    /**
     * Get date range for the selected time period
     */
    private function getDateRangeForPeriod(\DateTime $now, $timePeriod = 'all')
    {
        $start = clone $now;
        $end = clone $now;

        switch ($timePeriod) {
            case 'daily':
                // Show this week (Monday to Sunday)
                $start->modify('monday this week')->setTime(0, 0, 0);
                $end->modify('sunday this week')->setTime(23, 59, 59);
                break;

            case 'weekly':
                // Show last 4 weeks
                $start->modify('-4 weeks monday')->setTime(0, 0, 0);
                $end->setTime(23, 59, 59);
                break;

            case 'monthly':
                // Show last 12 months
                $start->modify('first day of -11 months')->setTime(0, 0, 0);
                $end->modify('last day of this month')->setTime(23, 59, 59);
                break;

            case 'yearly':
                // Show last 12 months (yearly view)
                $start->modify('first day of -11 months')->setTime(0, 0, 0);
                $end->modify('last day of this month')->setTime(23, 59, 59);
                break;

            default:
                // All time - no filtering
                $start = new \DateTime('2000-01-01');
                $end = new \DateTime('2099-12-31');
                break;
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Get aggregation key based on time period
     */
    private function getAggregationKey(\DateTime $date, $timePeriod = 'all')
    {
        switch ($timePeriod) {
            case 'daily':
                return $date->format('D, M j'); // Mon, Nov 11
            case 'weekly':
                $weekStart = clone $date;
                $weekStart->modify('monday this week');

                return $weekStart->format('M j').' (Week '.$weekStart->format('W').')';
            case 'monthly':
                return $date->format('M Y'); // Nov 2025
            case 'yearly':
                return $date->format('Y'); // 2025
            default:
                return $date->format('Y-m-d');
        }
    }

    public function render()
    {
        return view('livewire.dashboard-new');
    }
}
