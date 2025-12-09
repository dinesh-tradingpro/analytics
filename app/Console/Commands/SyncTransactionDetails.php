<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncTransactionDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:transaction-details
                            {--force : Force refresh even if data exists}
                            {--type= : Specific transaction type (deposit/withdrawal)}
                            {--start-date= : Start date (default: 2025-01-01)}
                            {--end-date= : End date (default: yesterday)}
                            {--batch-size=2000 : Number of records per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync detailed transaction data including amounts and user IDs from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting transaction details sync...');

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
            $batchSize = (int) $this->option('batch-size');

            $this->info("📅 Syncing data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

            // Sync both deposit and withdrawal
            $types = ['deposit', 'withdrawal'];

            // Filter by type if specified
            if ($this->option('type')) {
                $types = [$this->option('type')];
            }

            foreach ($types as $transactionType) {
                try {
                    $this->syncTransactionType($controller, $transactionType, $startDate, $endDate, $batchSize);
                } catch (\Exception $e) {
                    $this->error("❌ Failed to sync {$transactionType}s: ".$e->getMessage());
                    $this->error('Stack trace: '.$e->getTraceAsString());

                    // Continue with next type instead of failing entirely
                    continue;
                }
            }

            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ Transaction details sync completed successfully in {$duration} seconds!");

        } catch (\Exception $e) {
            $this->error('❌ Transaction details sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }

        return 0;
    }

    private function syncTransactionType($controller, $transactionType, $startDate, $endDate, $batchSize)
    {
        $this->info("📊 Syncing {$transactionType} details...");

        $currentDate = $startDate->copy();
        $totalSynced = 0;

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');

            // Check if we already have data for this date
            if (! $this->option('force')) {
                $existingCount = TransactionDetail::where('transaction_type', $transactionType)
                    ->where('transaction_date', $dateStr)
                    ->count();

                if ($existingCount > 0) {
                    $this->info("⏭️  {$dateStr}: Already have {$existingCount} records, skipping...");
                    $currentDate->addDay();

                    continue;
                }
            }

            $this->info("📥 Fetching {$transactionType}s for {$dateStr}...");

            try {
                $offset = 0;
                $dayTotal = 0;

                do {
                    $segment = ['limit' => $batchSize];
                    if ($offset > 0) {
                        $segment['offset'] = $offset;
                    }

                    $response = $controller->callApiEndpointSingle('transactions', [
                        'orders' => [
                            [
                                'field' => 'createdAt',
                                'direction' => 'DESC',
                            ],
                        ],
                        'segment' => $segment,
                        'statuses' => ['approved'],
                        'transactionTypes' => [$transactionType],
                        'createdAt' => [
                            'begin' => $currentDate->format('Y-m-d 00:00:00'),
                            'end' => $currentDate->format('Y-m-d 23:59:59'),
                        ],
                    ], 'POST');

                    if (! $response['success']) {
                        // Retry once for 400 errors (might be temporary)
                        if (isset($response['status_code']) && $response['status_code'] == 400 && $offset === 0) {
                            $this->warn('⚠️ API returned 400, retrying once...');
                            sleep(1);
                            $response = $controller->callApiEndpointSingle('transactions', [
                                'orders' => [['field' => 'createdAt', 'direction' => 'DESC']],
                                'segment' => $segment,
                                'statuses' => ['approved'],
                                'transactionTypes' => [$transactionType],
                                'createdAt' => [
                                    'begin' => $currentDate->format('Y-m-d 00:00:00'),
                                    'end' => $currentDate->format('Y-m-d 23:59:59'),
                                ],
                            ], 'POST');
                        }

                        if (! $response['success']) {
                            $this->warn("⚠️ API call failed for {$dateStr} at offset {$offset}: ".($response['error'] ?? 'Unknown error'));
                            break;
                        }
                    }

                    $transactions = $response['data'] ?? [];
                    $count = count($transactions);

                    if ($count === 0) {
                        break;
                    }

                    // Process and store transactions
                    $this->storeTransactions($transactions, $transactionType, $dateStr);

                    $dayTotal += $count;
                    $offset += $batchSize;

                    $this->info("   📦 Batch: {$count} records (offset: {$offset})");

                    // Add a small delay between requests
                    usleep(100000); // 0.1 second delay

                } while ($count === $batchSize); // Continue if we got a full batch

                $totalSynced += $dayTotal;
                $this->info("✅ {$dateStr}: Synced {$dayTotal} {$transactionType}s");

            } catch (\Exception $e) {
                $this->warn("⚠️ Exception for {$dateStr}: ".$e->getMessage());
                // Continue with next date
            }

            $currentDate->addDay();
        }

        $this->info("🔍 Completed: Synced {$totalSynced} total {$transactionType} records");
    }

    private function storeTransactions(array $transactions, string $transactionType, string $date)
    {
        $records = [];

        foreach ($transactions as $transaction) {
            // Skip if required fields are missing
            if (empty($transaction['id']) || empty($transaction['fromLoginSid'])) {
                continue;
            }

            $processedAmount = $transaction['processedAmount'] ?? 0;
            // Convert negative amounts to positive for withdrawals
            if ($transactionType === 'withdrawal' && $processedAmount < 0) {
                $processedAmount = abs($processedAmount);
            }

            $records[] = [
                'transaction_id' => $transaction['id'],
                'from_login_sid' => $transaction['fromLoginSid'],
                'transaction_type' => $transactionType,
                'status' => 'approved',
                'processed_amount' => $processedAmount,
                'transaction_date' => $date,
                'created_at_api' => isset($transaction['createdAt']) ? Carbon::parse($transaction['createdAt']) : now(),
                'processed_at_api' => isset($transaction['processedAt']) ? Carbon::parse($transaction['processedAt']) : null,
                'metadata' => json_encode([
                    'method' => $transaction['method'] ?? null,
                    'currency' => $transaction['currency'] ?? null,
                    'psp' => $transaction['psp'] ?? null,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert with ignore duplicates
        if (! empty($records)) {
            try {
                TransactionDetail::upsert(
                    $records,
                    ['transaction_id'], // Unique key
                    ['processed_amount', 'processed_at_api', 'metadata', 'updated_at'] // Update these if exists
                );
            } catch (\Exception $e) {
                $this->warn('⚠️ Failed to store batch: '.$e->getMessage());
            }
        }
    }
}
