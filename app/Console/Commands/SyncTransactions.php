<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\TransactionAnalyticsCache;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:transactions
                            {--force : Force refresh even if cache is fresh}
                            {--type= : Specific transaction type (deposit/withdrawal)}
                            {--status= : Specific status (approved/declined)}
                            {--start-date= : Start date (default: 2025-01-01)}
                            {--end-date= : End date (default: yesterday)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync daily transaction counts from API and cache the analytics results';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting transaction count sync...');

        $startTime = now();

        try {
            $controller = new class extends Controller
            {
                public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
                {
                    return parent::callApiEndpointSingle($endpoint, $params, $method);
                }
            };

            // Define date range
            $startDate = $this->option('start-date') ? Carbon::parse($this->option('start-date')) : Carbon::parse('2025-01-01');
            $endDate = $this->option('end-date') ? Carbon::parse($this->option('end-date')) : Carbon::yesterday();

            $this->info("📅 Syncing data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

            // Sync all 4 combinations: approved/declined deposits and withdrawals
            $combinations = [
                ['deposit', 'approved'],
                ['deposit', 'declined'],
                ['withdrawal', 'approved'],
                ['withdrawal', 'declined'],
            ];

            // Filter combinations based on options
            if ($this->option('type') && $this->option('status')) {
                $combinations = [[$this->option('type'), $this->option('status')]];
            } elseif ($this->option('type')) {
                $combinations = array_filter($combinations, fn ($c) => $c[0] === $this->option('type'));
            } elseif ($this->option('status')) {
                $combinations = array_filter($combinations, fn ($c) => $c[1] === $this->option('status'));
            }

            foreach ($combinations as [$transactionType, $status]) {
                try {
                    $this->syncDailyCounts($controller, $transactionType, $status, $startDate, $endDate);
                } catch (\Exception $e) {
                    $this->error("❌ Failed to sync {$status} {$transactionType}s: ".$e->getMessage());

                    // Continue with next combination instead of failing entirely
                    continue;
                }
            }

            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ Transaction count sync completed successfully in {$duration} seconds!");

        } catch (\Exception $e) {
            $this->error('❌ Transaction count sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }

        return 0;
    }

    private function syncDailyCounts($controller, $transactionType, $status, $startDate, $endDate)
    {
        $this->info("📊 Syncing daily counts for {$status} {$transactionType}s...");

        // Check if we need to refresh
        $existingCache = TransactionAnalyticsCache::getAnalyticsData($transactionType, $status, 'daily');
        if ($existingCache && $existingCache->isFresh() && ! $this->option('force')) {
            $this->info("⏭️  {$status} {$transactionType}s data is fresh, skipping...");

            return;
        }

        $dailyCounts = [];
        $totalCount = 0;
        $consecutiveErrors = 0;
        $maxConsecutiveErrors = 5;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $this->info("📥 Fetching count for {$dateStr}...");

            try {
                $response = $controller->callApiEndpointSingle('transactions/count', [
                    'transactionTypes' => [$transactionType],
                    'statuses' => [$status],
                    'createdAt' => [
                        'begin' => $currentDate->format('Y-m-d 00:00:00'),
                        'end' => $currentDate->format('Y-m-d 23:59:59'),
                    ],
                ], 'POST');

                if (! $response['success']) {
                    $consecutiveErrors++;
                    $this->warn("⚠️ API call failed for {$dateStr}: ".($response['error'] ?? 'Unknown error'));

                    if ($consecutiveErrors >= $maxConsecutiveErrors) {
                        $this->error('❌ Too many consecutive API errors. Stopping.');
                        break;
                    }

                    $this->info('🔄 Retrying in 2 seconds...');
                    sleep(2);

                    continue;
                }

                $count = $response['data']['total'] ?? 0;
                $dailyCounts[$dateStr] = $count;
                $totalCount += $count;

                // Reset consecutive error counter on success
                $consecutiveErrors = 0;

                $this->info("✅ {$dateStr}: {$count} {$status} {$transactionType}s");

                // Add a small delay between requests
                usleep(100000); // 0.1 second delay

            } catch (\Exception $e) {
                $consecutiveErrors++;
                $this->warn("⚠️ Exception for {$dateStr}: ".$e->getMessage());

                if ($consecutiveErrors >= $maxConsecutiveErrors) {
                    $this->error('❌ Too many consecutive errors. Stopping.');
                    break;
                }

                $this->info('🔄 Retrying in 2 seconds...');
                sleep(2);

                continue;
            }

            $currentDate->addDay();
        }

        // Process and cache the collected data
        $this->processAndCacheDailyCounts($dailyCounts, $transactionType, $status, $totalCount, $startDate, $endDate);

        $this->info("🔍 Completed: {$totalCount} total {$status} {$transactionType} transactions across ".count($dailyCounts).' days');
        $this->info("✅ {$status} {$transactionType}s daily counts cached successfully");
    }

    private function processAndCacheDailyCounts($dailyCounts, $transactionType, $status, $totalCount, $startDate, $endDate)
    {
        // Initialize data structures for different time periods
        $periodData = [
            'all_time' => $this->initializePeriodData(),
            'yearly' => $this->initializePeriodData(),
            'monthly' => $this->initializePeriodData(),
            'weekly' => $this->initializePeriodData(),
            'daily' => $this->initializePeriodData(),
            'current_month' => $this->initializePeriodData(),
            'last_7_days' => $this->initializePeriodData(),
        ];

        // Process daily counts and aggregate into different time periods
        foreach ($dailyCounts as $dateStr => $count) {
            $date = Carbon::parse($dateStr);
            $amount = 0; // Count API doesn't provide amounts

            // Process for different time periods
            $this->addToTimeSeriesData($periodData['daily'], $date, 'Y-m-d', $amount, $count);
            $this->addToTimeSeriesData($periodData['weekly'], $date, 'Y-\\WW', $amount, $count);
            $this->addToTimeSeriesData($periodData['monthly'], $date, 'Y-m', $amount, $count);
            $this->addToTimeSeriesData($periodData['yearly'], $date, 'Y', $amount, $count);

            // Current month data (November 2025)
            if ($date->month === now()->month && $date->year === now()->year) {
                $this->addToTimeSeriesData($periodData['current_month'], $date, 'Y-m-d', $amount, $count);
            }

            // Last 7 days data (before today)
            if ($date->isAfter(now()->subDays(8)->startOfDay()) && $date->isBefore(now()->startOfDay())) {
                $this->addToTimeSeriesData($periodData['last_7_days'], $date, 'Y-m-d', $amount, $count);
            }

            // All time data
            $periodData['all_time']['total_count'] += $count;
            $periodData['all_time']['total_amount'] += $amount;
        }

        // Cache data for each period type
        foreach ($periodData as $periodType => $data) {
            $chartData = $this->prepareChartData($data['time_series'], $periodType);

            $cacheData = [
                'chart_data' => $chartData,
                'total_count' => $periodType === 'all_time' ? $totalCount : $data['total_count'],
                'total_amount' => 0, // Count API doesn't provide amounts
                'top_transactions' => [], // Count API doesn't provide transaction details
                'metadata' => [
                    'date_range' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => $endDate->format('Y-m-d'),
                    ],
                    'avg_transaction_amount' => 0,
                    'total_days' => count($dailyCounts),
                    'avg_daily_count' => count($dailyCounts) > 0 ? round($totalCount / count($dailyCounts), 2) : 0,
                    'is_complete' => true,
                ],
                'total_records_fetched' => $totalCount,
                'period_start' => $this->getPeriodStart($periodType, $startDate),
                'period_end' => $this->getPeriodEnd($periodType, $endDate),
                'synced_at' => now(),
            ];

            TransactionAnalyticsCache::updateOrCreate(
                [
                    'transaction_type' => $transactionType,
                    'status' => $status,
                    'period_type' => $periodType,
                ],
                $cacheData
            );
        }
    }

    private function initializePeriodData()
    {
        return [
            'time_series' => [],
            'total_count' => 0,
            'total_amount' => 0,
        ];
    }

    private function addToTimeSeriesData(&$periodData, $date, $format, $amount, $count = 1)
    {
        $key = $date->format($format);

        if (! isset($periodData['time_series'][$key])) {
            $periodData['time_series'][$key] = ['count' => 0, 'amount' => 0];
        }

        $periodData['time_series'][$key]['count'] += $count;
        $periodData['time_series'][$key]['amount'] += $amount;
        $periodData['total_count'] += $count;
        $periodData['total_amount'] += $amount;
    }

    private function prepareChartData($timeSeries, $periodType)
    {
        ksort($timeSeries);

        $labels = array_keys($timeSeries);
        $counts = array_column($timeSeries, 'count');
        $amounts = array_column($timeSeries, 'amount');

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Transaction Count',
                    'data' => $counts,
                    'type' => 'bar',
                ],
                [
                    'label' => 'Transaction Amount',
                    'data' => $amounts,
                    'type' => 'line',
                    'yAxisID' => 'amount',
                ],
            ],
        ];
    }

    private function getPeriodStart($periodType, $customStart = null)
    {
        return match ($periodType) {
            'daily' => $customStart ? $customStart->copy()->subDays(30)->startOfDay() : now()->subDays(30)->startOfDay(),
            'weekly' => $customStart ? $customStart->copy()->subWeeks(12)->startOfWeek() : now()->subWeeks(12)->startOfWeek(),
            'monthly' => $customStart ? $customStart->copy()->subMonths(12)->startOfMonth() : now()->subMonths(12)->startOfMonth(),
            'yearly' => $customStart ? $customStart->copy()->subYears(5)->startOfYear() : now()->subYears(5)->startOfYear(),
            'current_month' => now()->startOfMonth(),
            'last_7_days' => now()->subDays(7)->startOfDay(),
            'all_time' => $customStart,
        };
    }

    private function getPeriodEnd($periodType, $customEnd = null)
    {
        return match ($periodType) {
            'daily' => $customEnd ? $customEnd->copy()->endOfDay() : now()->endOfDay(),
            'weekly' => $customEnd ? $customEnd->copy()->endOfWeek() : now()->endOfWeek(),
            'monthly' => $customEnd ? $customEnd->copy()->endOfMonth() : now()->endOfMonth(),
            'yearly' => $customEnd ? $customEnd->copy()->endOfYear() : now()->endOfYear(),
            'current_month' => now()->endOfMonth(),
            'last_7_days' => now()->subDay()->endOfDay(),
            'all_time' => $customEnd,
        };
    }
}
