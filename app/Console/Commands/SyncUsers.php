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
        $this->info('📊 Syncing active users data (firstDepositDate not null + lastLoginDate = today)...');

        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('active_users_new');
        if ($existingCache && ! $existingCache->isStale(60) && ! $this->option('force')) {
            $this->info('⏭️  Active users data is fresh, skipping...');

            return;
        }

        $activityGroups = [];
        $totalActiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000;
        $hasMoreData = true;
        $today = date('Y-m-d');

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
                        $activityGroups[$today] = ($activityGroups[$today] ?? 0) + 1;
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

        ksort($activityGroups);

        UserAnalyticsCache::updateOrCreateCache(
            'active_users_new',
            $activityGroups,
            $totalActiveUsers,
            ['active_users_today' => $totalActiveUsers, 'date' => $today],
            $totalFetched
        );

        $this->info('✅ Active users data cached successfully');
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
        $this->info('📊 Syncing inactive users data (firstDepositDate not null, grouped by last login date and trading status)...');

        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('inactive_users_new');
        if ($existingCache && ! $existingCache->isStale(60) && ! $this->option('force')) {
            $this->info('⏭️  Inactive users data is fresh, skipping...');

            return;
        }

        $inactivityGroups = [];
        $statusBreakdown = [];
        $totalInactiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000;
        $hasMoreData = true;
        $today = date('Y-m-d');

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

                    // Group by last login date
                    if (isset($user['lastLoginDate'])) {
                        $date = date('Y-m-d', strtotime($user['lastLoginDate']));
                        $inactivityGroups[$date] = ($inactivityGroups[$date] ?? 0) + 1;
                    } else {
                        $inactivityGroups['never'] = ($inactivityGroups['never'] ?? 0) + 1;
                    }

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

        ksort($inactivityGroups);

        $metadata = [
            'inactive_users_breakdown' => [
                'never_logged_in' => $inactivityGroups['never'] ?? 0,
                'total_with_login_history' => $totalInactiveUsers - ($inactivityGroups['never'] ?? 0),
            ],
            'trading_status_breakdown' => $statusBreakdown,
        ];

        UserAnalyticsCache::updateOrCreateCache(
            'inactive_users_new',
            $inactivityGroups,
            $totalInactiveUsers,
            $metadata,
            $totalFetched
        );

        $this->info('✅ Inactive users data cached successfully');
    }
}
