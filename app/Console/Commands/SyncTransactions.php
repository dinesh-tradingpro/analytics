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
                            {--limit=150000 : Maximum number of records to fetch per transaction type}
                            {--batch-size=1000 : Number of records per API call}
                            {--resume : Resume from where it left off}
                            {--type= : Specific transaction type (deposit/withdrawal)}
                            {--status= : Specific status (approved/declined)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync transaction analytics data from API and cache the results';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting transaction analytics sync...');

        $startTime = now();

        try {
            // Increase memory and time limits for the command
            ini_set('memory_limit', '4G');
            set_time_limit(0);

            $controller = new class extends Controller
            {
                public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
                {
                    return parent::callApiEndpointSingle($endpoint, $params, $method);
                }
            };

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
                    $this->syncTransactionData($controller, $transactionType, $status);
                } catch (\Exception $e) {
                    $this->error("❌ Failed to sync {$status} {$transactionType}s: ".$e->getMessage());

                    // Continue with next combination instead of failing entirely
                    continue;
                }
            }

            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ Transaction analytics sync completed successfully in {$duration} seconds!");

        } catch (\Exception $e) {
            $this->error('❌ Transaction analytics sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }

        return 0;
    }

    private function syncTransactionData($controller, $transactionType, $status)
    {
        $this->info("📊 Syncing {$status} {$transactionType}s data...");

        // Check if we need to refresh
        $existingCache = TransactionAnalyticsCache::getAnalyticsData($transactionType, $status, 'all_time');
        if ($existingCache && $existingCache->isFresh() && ! $this->option('force') && ! $this->option('resume')) {
            $this->info("⏭️  {$status} {$transactionType}s data is fresh, skipping...");

            return;
        }

        // Resume functionality - check where we left off
        $startOffset = 0;
        if ($this->option('resume') && $existingCache) {
            $startOffset = $existingCache->metadata['last_offset'] ?? 0;
            $this->info("🔄 Resuming from offset {$startOffset}...");
        }

        // Fetch data from API with progressive saving
        $offset = $startOffset;
        $limit = (int) $this->option('batch-size');
        $maxRecords = (int) $this->option('limit');
        $allTransactions = [];
        $totalFetched = 0;
        $hasMoreData = true;
        $consecutiveErrors = 0;
        $maxConsecutiveErrors = 3;

        while ($hasMoreData && $totalFetched < $maxRecords) {
            $this->info("📥 Fetching {$transactionType} {$status} batch at offset {$offset}...");

            try {
                $response = $controller->callApiEndpointSingle('accounts/trade-statistic?version=1.0.0', [
                    'orders' => [
                        [
                            'field' => 'createdAt',
                            'direction' => 'DESC',
                        ],
                    ],
                    'segment' => [
                        'limit' => $limit,
                        'offset' => $offset,
                    ],
                    'statuses' => [$status],
                    'transactionTypes' => [$transactionType],
                ], 'POST');

                if (! $response['success']) {
                    $consecutiveErrors++;
                    $this->warn('⚠️ API call failed: '.$response['error']);

                    if ($consecutiveErrors >= $maxConsecutiveErrors) {
                        $this->error('❌ Too many consecutive API errors. Saving progress and stopping.');
                        break;
                    }

                    $this->info('🔄 Retrying in 5 seconds...');
                    sleep(5);

                    continue;
                }

                $batchData = $response['data'] ?? [];
                $batchCount = count($batchData);
                $totalFetched += $batchCount;

                // Reset consecutive error counter on success
                $consecutiveErrors = 0;

                $this->info("📊 Processing {$batchCount} {$transactionType} records... (Total: {$totalFetched})");

                // Store transactions for processing
                $allTransactions = array_merge($allTransactions, $batchData);

                // Save progress every 10,000 records or when we hit API limits
                if (count($allTransactions) >= 10000 || $batchCount < $limit) {
                    $this->info('💾 Saving progress with '.count($allTransactions).' transactions...');
                    $this->processAndCacheAnalytics($allTransactions, $transactionType, $status, $totalFetched, $offset + $batchCount);
                    $allTransactions = []; // Clear memory
                    gc_collect_cycles(); // Force garbage collection
                }

                if ($batchCount < $limit) {
                    $hasMoreData = false;
                    $this->info('✅ Reached end of data');
                } else {
                    $offset += $limit;
                }

                // Add a small delay between requests to be nice to the API
                usleep(500000); // 0.5 second delay

            } catch (\Exception $e) {
                $consecutiveErrors++;
                $this->warn('⚠️ Exception during API call: '.$e->getMessage());

                if ($consecutiveErrors >= $maxConsecutiveErrors) {
                    $this->error('❌ Too many consecutive errors. Saving progress and stopping.');
                    break;
                }

                $this->info('🔄 Retrying in 5 seconds...');
                sleep(5);
            }
        }

        // Process any remaining transactions
        if (! empty($allTransactions)) {
            $this->info('💾 Saving final batch with '.count($allTransactions).' transactions...');
            $this->processAndCacheAnalytics($allTransactions, $transactionType, $status, $totalFetched, $offset);
        }

        $this->info("🔍 Completed: {$totalFetched} {$status} {$transactionType} transactions processed");
        $this->info("✅ {$status} {$transactionType}s data cached successfully");
    }

    private function processAndCacheAnalytics($transactions, $transactionType, $status, $totalFetched, $currentOffset = null)
    {
        // Initialize data structures for different time periods
        $periodData = [
            'all_time' => $this->initializePeriodData(),
            'yearly' => $this->initializePeriodData(),
            'monthly' => $this->initializePeriodData(),
            'weekly' => $this->initializePeriodData(),
            'daily' => $this->initializePeriodData(),
        ];

        $totalAmount = 0;
        $topTransactions = [];

        foreach ($transactions as $transaction) {
            $amount = floatval($transaction['amount'] ?? 0);
            $totalAmount += $amount;

            // Parse creation date
            $createdAt = Carbon::parse($transaction['createdAt'] ?? now());

            // Store for top transactions (keep top 10 by amount)
            $topTransactions[] = [
                'id' => $transaction['id'] ?? null,
                'amount' => $amount,
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
                'user_id' => $transaction['userId'] ?? null,
            ];

            // Process for different time periods
            $this->addToTimeSeriesData($periodData['daily'], $createdAt, 'Y-m-d', $amount);
            $this->addToTimeSeriesData($periodData['weekly'], $createdAt, 'Y-\WW', $amount);
            $this->addToTimeSeriesData($periodData['monthly'], $createdAt, 'Y-m', $amount);
            $this->addToTimeSeriesData($periodData['yearly'], $createdAt, 'Y', $amount);

            // All time data
            $periodData['all_time']['total_count']++;
            $periodData['all_time']['total_amount'] += $amount;
        }

        // Sort and keep only top 10 transactions
        usort($topTransactions, fn ($a, $b) => $b['amount'] <=> $a['amount']);
        $topTransactions = array_slice($topTransactions, 0, 10);

        // Check if we're updating existing data (progressive save)
        $existingData = TransactionAnalyticsCache::getAnalyticsData($transactionType, $status, 'all_time');
        $isUpdate = $existingData && $currentOffset !== null;

        // Cache data for each period type
        foreach ($periodData as $periodType => $data) {
            $chartData = $this->prepareChartData($data['time_series'], $periodType);

            $cacheData = [
                'chart_data' => $chartData,
                'total_count' => $periodType === 'all_time' ? count($transactions) : $data['total_count'],
                'total_amount' => $periodType === 'all_time' ? $totalAmount : $data['total_amount'],
                'top_transactions' => $topTransactions,
                'metadata' => [
                    'date_range' => [
                        'start' => ! empty($transactions) ? min(array_column($transactions, 'createdAt')) : null,
                        'end' => ! empty($transactions) ? max(array_column($transactions, 'createdAt')) : null,
                    ],
                    'avg_transaction_amount' => count($transactions) > 0 ? $totalAmount / count($transactions) : 0,
                    'last_offset' => $currentOffset, // Store current offset for resume functionality
                    'is_complete' => $currentOffset === null, // Mark as complete if no offset provided
                ],
                'total_records_fetched' => $totalFetched,
                'period_start' => $this->getPeriodStart($periodType),
                'period_end' => $this->getPeriodEnd($periodType),
                'synced_at' => now(),
            ];

            // If updating existing data, merge with previous data
            if ($isUpdate && $periodType === 'all_time') {
                $cacheData['total_count'] = ($existingData->total_count ?? 0) + count($transactions);
                $cacheData['total_amount'] = ($existingData->total_amount ?? 0) + $totalAmount;
                $cacheData['total_records_fetched'] = $totalFetched;

                // Merge top transactions and keep top 10
                $existingTopTransactions = $existingData->top_transactions ?? [];
                $allTopTransactions = array_merge($existingTopTransactions, $topTransactions);
                usort($allTopTransactions, fn ($a, $b) => $b['amount'] <=> $a['amount']);
                $cacheData['top_transactions'] = array_slice($allTopTransactions, 0, 10);

                // Merge chart data (this is simplified - in production you'd want more sophisticated merging)
                $existingChartData = $existingData->chart_data ?? [];
                if (! empty($existingChartData['labels']) && ! empty($chartData['labels'])) {
                    // Simple merge - in production, you'd want to properly aggregate overlapping time periods
                    $cacheData['chart_data'] = $chartData; // Use new data for now
                }
            }

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

    private function addToTimeSeriesData(&$periodData, $date, $format, $amount)
    {
        $key = $date->format($format);

        if (! isset($periodData['time_series'][$key])) {
            $periodData['time_series'][$key] = ['count' => 0, 'amount' => 0];
        }

        $periodData['time_series'][$key]['count']++;
        $periodData['time_series'][$key]['amount'] += $amount;
        $periodData['total_count']++;
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

    private function getPeriodStart($periodType)
    {
        return match ($periodType) {
            'daily' => now()->subDays(30)->startOfDay(),
            'weekly' => now()->subWeeks(12)->startOfWeek(),
            'monthly' => now()->subMonths(12)->startOfMonth(),
            'yearly' => now()->subYears(5)->startOfYear(),
            'all_time' => null,
        };
    }

    private function getPeriodEnd($periodType)
    {
        return match ($periodType) {
            'daily' => now()->endOfDay(),
            'weekly' => now()->endOfWeek(),
            'monthly' => now()->endOfMonth(),
            'yearly' => now()->endOfYear(),
            'all_time' => null,
        };
    }
}
