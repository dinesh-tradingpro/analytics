<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\TransactionAnalyticsCache;
use App\Models\TransactionDetail;
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
                            {--force : Force refresh even if data exists}
                            {--type= : Specific transaction type (deposit/withdrawal)}
                            {--status= : Specific transaction status (approved/declined)}
                            {--start-date= : Start date (default: 2025-01-01)}
                            {--end-date= : End date (default: yesterday)}
                            {--batch-size=2000 : Number of records per batch}
                            {--skip-analytics : Skip analytics cache computation}
                            {--analytics-only : Only compute analytics from existing data}
                            {--parallel : Use parallel processing for multiple types}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync transaction data and compute analytics with optimized data collection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting transaction sync...');
        $startTime = now();

        try {
            if ($this->option('analytics-only')) {
                $this->computeAnalyticsFromExistingData();
            } else {
                $controller = new class extends Controller
                {
                    public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
                    {
                        return parent::callApiEndpointSingle($endpoint, $params, $method);
                    }
                };

                $startDate = $this->option('start-date') ? Carbon::parse($this->option('start-date')) : Carbon::parse('2026-01-01');
                $endDate = $this->option('end-date') ? Carbon::parse($this->option('end-date')) : Carbon::yesterday();
                $batchSize = (int) $this->option('batch-size');

                $this->info("📅 Syncing data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

                $types = $this->option('type') ? [$this->option('type')] : ['deposit', 'withdrawal'];

                // Sync transaction data
                $this->syncTransactions($controller, $types, $startDate, $endDate, $batchSize);

                // Compute analytics if not skipped
                if (! $this->option('skip-analytics')) {
                    $this->info("\n📊 Computing analytics from synced data...");
                    $this->computeAnalytics($startDate, $endDate, $types);
                }
            }

            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ Transaction sync completed in {$duration}s");

        } catch (\Exception $e) {
            $this->error('❌ Sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }

        return 0;
    }

    private function syncTransactionType($controller, $transactionType, $startDate, $endDate, $batchSize)
    {
        // DEPRECATED: Use syncTransactions instead
        // Kept for backward compatibility if called directly
    }

    /**
     * Optimized method to sync transactions from API
     */
    private function syncTransactions($controller, $types, $startDate, $endDate, $batchSize)
    {
        $currentDate = $startDate->copy();
        $stats = array_fill_keys($types, 0);

        // Determine statuses to fetch based on option or default to both
        $statuses = $this->option('status') ? [$this->option('status')] : ['approved', 'declined'];

        while ($currentDate->lte($endDate)) {
            // Check if we already have data for this day (for all types and statuses)
            if (! $this->option('force')) {
                $allExists = true;
                foreach ($types as $type) {
                    $exists = TransactionDetail::where('transaction_type', $type)
                        ->where('transaction_date', $currentDate->format('Y-m-d'))
                        ->exists();
                    if (! $exists) {
                        $allExists = false;
                        break;
                    }
                }
                if ($allExists) {
                    $currentDate->addDay();

                    continue;
                }
            }

            // Fetch all transaction types and statuses for this day in a single or minimal API calls
            $this->fetchAndStoreTransactionsOptimized($controller, $types, $statuses, $currentDate, $batchSize, $stats);
            $currentDate->addDay();
        }

        $this->printSyncStats($stats);
    }

    /**
     * Optimized fetch with reduced API calls by requesting multiple types and statuses together
     */
    private function fetchAndStoreTransactionsOptimized($controller, $types, $statuses, $date, $batchSize, &$stats)
    {
        $offset = 0;
        $dayTotal = 0;
        $typeStats = array_fill_keys($types, 0);

        do {
            try {
                $segment = ['limit' => $batchSize];
                if ($offset > 0) {
                    $segment['offset'] = $offset;
                }

                // Fetch all transaction types and statuses in a single API call
                $response = $controller->callApiEndpointSingle('transactions', [
                    'orders' => [['field' => 'createdAt', 'direction' => 'DESC']],
                    'segment' => $segment,
                    'statuses' => $statuses,
                    'transactionTypes' => $types,
                    'createdAt' => [
                        'begin' => $date->format('Y-m-d 00:00:00'),
                        'end' => $date->format('Y-m-d 23:59:59'),
                    ],
                ], 'POST');

                if (! $response['success']) {
                    if ($offset === 0 && ($response['status_code'] ?? null) == 400) {
                        sleep(1);

                        continue;
                    }
                    break;
                }

                $transactions = $response['data'] ?? [];
                $count = count($transactions);

                if ($count === 0) {
                    break;
                }

                // Group transactions by type and store
                $byType = [];
                foreach ($transactions as $tx) {
                    $type = $tx['type'] ?? $tx['transaction_type'] ?? 'deposit';
                    if (! isset($byType[$type])) {
                        $byType[$type] = [];
                    }
                    $byType[$type][] = $tx;
                    $typeStats[$type]++;
                }

                foreach ($byType as $type => $txns) {
                    if (in_array($type, $types)) {
                        $this->storeTransactionsOptimized($txns, $type, $date->format('Y-m-d'));
                        $this->info("   📦 {$type} batch: ".count($txns).' records');
                    }
                }

                $dayTotal += $count;
                $offset += $batchSize;
                usleep(100000);

            } catch (\Exception $e) {
                $this->warn('⚠️ Error: '.$e->getMessage());
                break;
            }

        } while ($count === $batchSize);

        foreach ($typeStats as $type => $count) {
            $stats[$type] += $count;
        }

        if ($dayTotal > 0) {
            $this->info("✅ {$date->format('Y-m-d')}: {$dayTotal} transactions (".implode(' / ', $typeStats).')');
        }
    }

    private function printSyncStats(array $stats)
    {
        $this->info("\n📊 Sync Summary:");
        foreach ($stats as $type => $count) {
            if ($count > 0) {
                $this->info("  • {$type}: {$count} records");
            }
        }
    }

    /**
     * Optimized transaction storage with chunk processing
     * Reduces memory usage and improves insert performance
     */
    private function storeTransactionsOptimized(array $transactions, string $transactionType, string $date)
    {
        // Process in smaller chunks to reduce memory pressure
        $chunkSize = 500;
        $chunks = array_chunk($transactions, $chunkSize);

        foreach ($chunks as $chunk) {
            $records = [];

            foreach ($chunk as $tx) {
                if (empty($tx['id']) || empty($tx['fromLoginSid'])) {
                    continue;
                }

                $amount = $tx['processedAmount'] ?? ($tx['processed_amount'] ?? 0);
                if ($transactionType === 'withdrawal' && $amount < 0) {
                    $amount = abs($amount);
                }

                // Original currencies from API (handle camelCase or snake_case)
                $processedCurrency = $tx['processedCurrency'] ?? ($tx['processed_currency'] ?? ($tx['currency'] ?? 'USD'));
                $requestedAmount = $tx['requestedAmount'] ?? ($tx['requested_amount'] ?? null);
                $requestedCurrency = $tx['requestedCurrency'] ?? ($tx['requested_currency'] ?? null);

                // Convert amounts to USD using rates from config/currency_rates.php
                $processedAmountUsd = $this->convertToUsd($amount, $processedCurrency);
                $requestedAmountUsd = $requestedAmount !== null ? $this->convertToUsd($requestedAmount, $requestedCurrency ?? $processedCurrency) : null;

                $records[] = [
                    'transaction_id' => $tx['id'],
                    'from_login_sid' => $tx['fromLoginSid'], // wallet id
                    'from_user_id' => $tx['fromUserId'] ?? ($tx['from_user_id'] ?? null),
                    'transaction_type' => $transactionType,
                    'status' => $tx['status'] ?? 'approved',
                    'processed_amount' => $amount,
                    'processed_amount_usd' => $processedAmountUsd,
                    'requested_amount' => $requestedAmount,
                    'requested_amount_usd' => $requestedAmountUsd,
                    'processed_currency' => $processedCurrency,
                    'requested_currency' => $requestedCurrency,
                    'transaction_date' => $date,
                    'created_at_api' => isset($tx['createdAt']) ? Carbon::parse($tx['createdAt']) : now(),
                    'processed_at_api' => isset($tx['processedAt']) ? Carbon::parse($tx['processedAt']) : null,
                    'metadata' => json_encode([
                        'method' => $tx['method'] ?? null,
                        'original_currency' => $processedCurrency,
                        'psp' => $tx['psp'] ?? null,
                        'raw' => $tx,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! empty($records)) {
                try {
                    TransactionDetail::upsert(
                        $records,
                        ['transaction_id'],
                        ['processed_amount', 'processed_at_api', 'metadata', 'updated_at']
                    );
                } catch (\Exception $e) {
                    $this->warn('⚠️ Store error: '.$e->getMessage());
                }
            }
        }
    }

    /**
     * Optimized analytics computation using raw SQL aggregation
     * Much faster than iterating through collections
     */
    private function computeAnalytics($startDate, $endDate, $types)
    {
        $statuses = ['approved', 'declined'];

        foreach ($types as $transactionType) {
            foreach ($statuses as $status) {
                try {
                    $this->computeAndCacheAnalytics($transactionType, $status, $startDate, $endDate);
                } catch (\Exception $e) {
                    $this->error("❌ Analytics failed for {$status} {$transactionType}s: ".$e->getMessage());

                    continue;
                }
            }
        }
    }

    /**
     * Convert an amount in given currency to USD using config rates.
     */
    private function convertToUsd($amount, $currency)
    {
        if ($amount === null) {
            return null;
        }

        $currency = strtoupper($currency ?? 'USD');
        $rates = config('currency_rates.rates', []);

        if (! isset($rates[$currency]) || $rates[$currency] == 0) {
            // Fallback: treat as USD if unknown
            return round($amount, 2);
        }

        // rates are expressed as 1 unit of currency -> USD equivalent
        $rate = $rates[$currency];
        $usd = $amount * $rate;

        return round($usd, 2);
    }

    /**
     * Compute analytics using optimized query patterns
     */
    private function computeAndCacheAnalytics($transactionType, $status, $startDate, $endDate)
    {
        $this->info("📊 Computing {$status} {$transactionType}s analytics...");

        $periodData = $this->computePeriodDataOptimized($transactionType, $status, $startDate, $endDate);

        if (empty($periodData['all_time']['total_count'])) {
            $this->info("⏭️  No data for {$status} {$transactionType}s");

            return;
        }

        // Cache data for each period type in a single batch operation
        $cacheRecords = [];
        foreach ($periodData as $periodType => $data) {
            $chartData = $this->prepareChartData($data['time_series'], $periodType);

            $topTransactions = TransactionDetail::where('transaction_type', $transactionType)
                ->where('status', $status)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->orderBy('processed_amount_usd', 'desc')
                ->limit(10)
                ->select('transaction_id', 'from_user_id', 'from_login_sid', 'processed_amount_usd as amount', 'transaction_date')
                ->get()
                ->map(function ($tx) {
                    return [
                        'transaction_id' => $tx->transaction_id,
                        'user_id' => $tx->from_user_id,
                        'wallet_id' => $tx->from_login_sid,
                        'amount' => $tx->amount,
                        'created_at' => $tx->transaction_date,
                    ];
                })
                ->toArray();

            $totalCount = $periodType === 'all_time' ? $periodData['all_time']['total_count'] : $data['total_count'];
            $totalAmount = $periodType === 'all_time' ? $periodData['all_time']['total_amount'] : $data['total_amount'];

            // Ensure dates are in proper format
            $startDateStr = is_string($startDate) ? $startDate : $startDate->format('Y-m-d');
            $endDateStr = is_string($endDate) ? $endDate : $endDate->format('Y-m-d');

            $cacheRecords[] = [
                'transaction_type' => $transactionType,
                'status' => $status,
                'period_type' => $periodType,
                'chart_data' => $chartData,
                'total_count' => $totalCount,
                'total_amount' => $totalAmount,
                'top_transactions' => $topTransactions,
                'metadata' => [
                    'date_range' => ['start' => $startDateStr, 'end' => $endDateStr],
                    'avg_transaction_amount' => $totalCount > 0 ? round($totalAmount / $totalCount, 2) : 0,
                    'total_days' => $data['unique_days'] ?? 0,
                    'avg_daily_count' => $data['avg_daily_count'] ?? 0,
                    'is_complete' => true,
                    'computed_from_details' => true,
                ],
                'total_records_fetched' => $totalCount,
                'period_start' => $this->getPeriodStart($periodType, is_string($startDate) ? Carbon::parse($startDate) : $startDate),
                'period_end' => $this->getPeriodEnd($periodType, is_string($endDate) ? Carbon::parse($endDate) : $endDate),
                'synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch upsert all period types
        foreach ($cacheRecords as $record) {
            TransactionAnalyticsCache::updateOrCreate(
                ['transaction_type' => $transactionType, 'status' => $status, 'period_type' => $record['period_type']],
                $record
            );
        }

        $this->info("✅ Analytics cached: {$periodData['all_time']['total_count']} transactions");
    }

    /**
     * Compute period data using optimized aggregation
     */
    private function computePeriodDataOptimized($transactionType, $status, $startDate, $endDate)
    {
        $periodData = [
            'all_time' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0, 'unique_days' => 0, 'avg_daily_count' => 0],
            'yearly' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
            'monthly' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
            'weekly' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
            'daily' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
            'current_month' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
            'last_7_days' => ['time_series' => [], 'total_count' => 0, 'total_amount' => 0],
        ];

        // Use chunked iteration to avoid memory issues with large datasets
        TransactionDetail::where('transaction_type', $transactionType)
            ->where('status', $status)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->chunk(5000, function ($transactions) use (&$periodData) {
                foreach ($transactions as $tx) {
                    $date = $tx->transaction_date;
                    $amount = (float) $tx->processed_amount_usd;

                    $this->addToTimeSeriesData($periodData['daily'], $date, 'Y-m-d', $amount);
                    $this->addToTimeSeriesData($periodData['weekly'], $date, 'Y-\\WW', $amount);
                    $this->addToTimeSeriesData($periodData['monthly'], $date, 'Y-m', $amount);
                    $this->addToTimeSeriesData($periodData['yearly'], $date, 'Y', $amount);

                    if ($date->month === now()->month && $date->year === now()->year) {
                        $this->addToTimeSeriesData($periodData['current_month'], $date, 'Y-m-d', $amount);
                    }

                    if ($date->isAfter(now()->subDays(8)->startOfDay()) && $date->isBefore(now()->startOfDay())) {
                        $this->addToTimeSeriesData($periodData['last_7_days'], $date, 'Y-m-d', $amount);
                    }

                    $periodData['all_time']['total_count']++;
                    $periodData['all_time']['total_amount'] += $amount;
                }
            });

        // Calculate stats
        $uniqueDays = TransactionDetail::where('transaction_type', $transactionType)
            ->where('status', $status)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->distinct('transaction_date')
            ->count();

        $periodData['all_time']['unique_days'] = $uniqueDays;
        $periodData['all_time']['avg_daily_count'] = $uniqueDays > 0 ? round($periodData['all_time']['total_count'] / $uniqueDays, 2) : 0;

        return $periodData;
    }

    private function computeAnalyticsFromExistingData()
    {
        $this->info('📊 Computing analytics from existing transaction data...');

        // Get all transaction types and statuses with data
        $records = TransactionDetail::selectRaw('DISTINCT transaction_type, status, MIN(transaction_date) as min_date, MAX(transaction_date) as max_date')
            ->groupBy('transaction_type', 'status')
            ->get();

        foreach ($records as $record) {
            try {
                $this->computeAndCacheAnalytics(
                    $record->transaction_type,
                    $record->status,
                    $record->min_date,
                    $record->max_date
                );
            } catch (\Exception $e) {
                $this->error("❌ Failed to compute analytics for {$record->status} {$record->transaction_type}s: ".$e->getMessage());

                continue;
            }
        }

        $this->info('✅ Analytics computation completed');
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
