<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\UserAnalyticsCache;
use Illuminate\Console\Command;

class SyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:users {--force : Force refresh even if cache is fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user analytics data: active users (with firstDepositDate + logged in today), new users (by registration date), and inactive users (by last login date)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting user analytics sync...');

        $startTime = now();

        try {
            // Increase memory and time limits for the command
            ini_set('memory_limit', '4G');
            set_time_limit(0);

            $controller = new class extends Controller
            {
                // Anonymous class to access protected methods
                public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
                {
                    return parent::callApiEndpointSingle($endpoint, $params, $method);
                }
            };

            $this->syncActiveUsers($controller);
            $this->syncNewUsers($controller);
            $this->syncInactiveUsers($controller);

            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ User analytics sync completed successfully in {$duration} seconds!");

        } catch (\Exception $e) {
            $this->error('❌ User analytics sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }

        return 0;
    }

    private function syncActiveUsers($controller)
    {
        $this->info('📊 Syncing active users data (daily snapshot: firstDepositDate not null + lastLoginDate = today)...');

        $today = date('Y-m-d');

        // Check if today's snapshot already exists
        $existingCache = UserAnalyticsCache::where('metric_type', 'active_users_daily')
            ->whereDate('data_date', $today)
            ->first();

        if ($existingCache && ! $this->option('force')) {
            $this->info("⏭️  Today's active users snapshot already exists (count: {$existingCache->total_count}), skipping...");

            return;
        }

        $totalActiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000;
        $hasMoreData = true;

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");

            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ],
            ], 'POST');

            if (! $response['success']) {
                throw new \Exception('Failed to fetch users data: '.$response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            foreach ($batchData as $user) {
                // Only process users with firstDepositDate
                if (empty($user['firstDepositDate'])) {
                    continue;
                }

                // Check if user logged in today
                if (isset($user['lastLoginDate'])) {
                    $lastLoginDate = date('Y-m-d', strtotime($user['lastLoginDate']));
                    if ($lastLoginDate === $today) {
                        $totalActiveUsers++;
                    }
                }
            }

            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalActiveUsers} active users from {$totalFetched} total records");

        // Store today's snapshot - this will preserve previous days' records
        UserAnalyticsCache::updateOrCreate(
            [
                'metric_type' => 'active_users_daily',
                'data_date' => $today,
            ],
            [
                'chart_data' => [$today => $totalActiveUsers],
                'total_count' => $totalActiveUsers,
                'metadata' => [
                    'snapshot_date' => $today,
                    'description' => 'Daily active users snapshot',
                ],
                'total_records_fetched' => $totalFetched,
                'synced_at' => now(),
            ]
        );

        $this->info("✅ Active users daily snapshot cached successfully: {$today} => {$totalActiveUsers} users");
    }

    private function syncNewUsers($controller)
    {
        $this->info('📊 Syncing new users data (firstDepositDate not null, grouped by registration date)...');

        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('new_users_new');
        if ($existingCache && ! $existingCache->isStale(60) && ! $this->option('force')) {
            $this->info('⏭️  New users data is fresh, skipping...');

            return;
        }

        $dateGroups = [];
        $totalNewUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000;
        $hasMoreData = true;

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");

            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ],
            ], 'POST');

            if (! $response['success']) {
                throw new \Exception('Failed to fetch users data: '.$response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            foreach ($batchData as $user) {
                // Only process users with firstDepositDate
                if (empty($user['firstDepositDate'])) {
                    continue;
                }

                // Group by registration date
                if (isset($user['registrationDate'])) {
                    $totalNewUsers++;
                    $date = date('Y-m-d', strtotime($user['registrationDate']));
                    $dateGroups[$date] = ($dateGroups[$date] ?? 0) + 1;
                }
            }

            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalNewUsers} new users (with first deposit) from {$totalFetched} total records");

        ksort($dateGroups);

        $metadata = [
            'date_range' => [
                'start' => ! empty($dateGroups) ? min(array_keys($dateGroups)) : null,
                'end' => ! empty($dateGroups) ? max(array_keys($dateGroups)) : null,
            ],
        ];

        UserAnalyticsCache::updateOrCreateCache(
            'new_users_new',
            $dateGroups,
            $totalNewUsers,
            $metadata,
            $totalFetched
        );

        $this->info('✅ New users data cached successfully');
    }

    private function syncInactiveUsers($controller)
    {
        $this->info('📊 Syncing inactive users data (daily snapshot: firstDepositDate not null + lastLoginDate != today)...');

        $today = date('Y-m-d');

        // Check if today's snapshot already exists
        $existingCache = UserAnalyticsCache::where('metric_type', 'inactive_users_daily')
            ->whereDate('data_date', $today)
            ->first();

        if ($existingCache && ! $this->option('force')) {
            $this->info("⏭️  Today's inactive users snapshot already exists (count: {$existingCache->total_count}), skipping...");

            return;
        }

        $statusBreakdown = [];
        $totalInactiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000;
        $hasMoreData = true;

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");

            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ],
            ], 'POST');

            if (! $response['success']) {
                throw new \Exception('Failed to fetch users data: '.$response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            foreach ($batchData as $user) {
                // Only process users with firstDepositDate
                if (empty($user['firstDepositDate'])) {
                    continue;
                }

                // Check if user is inactive (lastLoginDate is not today or null)
                $isInactive = true;
                if (isset($user['lastLoginDate'])) {
                    $lastLoginDate = date('Y-m-d', strtotime($user['lastLoginDate']));
                    if ($lastLoginDate === $today) {
                        $isInactive = false; // User is active today
                    }
                }

                if ($isInactive) {
                    $totalInactiveUsers++;

                    // Group by trading status
                    $tradingStatus = $user['tradingStatus'] ?? 'unknown';
                    $statusBreakdown[$tradingStatus] = ($statusBreakdown[$tradingStatus] ?? 0) + 1;
                }
            }

            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalInactiveUsers} inactive users from {$totalFetched} total records");
        $this->info('📈 Status breakdown: '.json_encode($statusBreakdown));

        // Store today's snapshot - this will preserve previous days' records
        UserAnalyticsCache::updateOrCreate(
            [
                'metric_type' => 'inactive_users_daily',
                'data_date' => $today,
            ],
            [
                'chart_data' => [$today => $totalInactiveUsers],
                'total_count' => $totalInactiveUsers,
                'metadata' => [
                    'snapshot_date' => $today,
                    'description' => 'Daily inactive users snapshot',
                    'trading_status_breakdown' => $statusBreakdown,
                ],
                'total_records_fetched' => $totalFetched,
                'synced_at' => now(),
            ]
        );

        $this->info("✅ Inactive users daily snapshot cached successfully: {$today} => {$totalInactiveUsers} users");
    }
}
