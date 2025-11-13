<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\UserAnalyticsCache;
use Illuminate\Console\Command;

class SyncUserAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:sync {--force : Force refresh even if cache is fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user analytics data from API and cache the results';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting user analytics sync...');
        
        $startTime = now();
        
        try {
            // Increase memory and time limits for the command
            ini_set('memory_limit', '4G'); // Increased to 4GB
            set_time_limit(0); // No time limit for CLI commands
            
            $controller = new class extends Controller {
                // Anonymous class to access protected methods
                public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
                {
                    return parent::callApiEndpointSingle($endpoint, $params, $method);
                }
            };

            $this->syncNewUsers($controller);
            $this->syncActiveUsers($controller);
            $this->syncInactiveUsers($controller);
            
            $duration = $startTime->diffInSeconds(now());
            $this->info("✅ Analytics sync completed successfully in {$duration} seconds!");
            
        } catch (\Exception $e) {
            $this->error("❌ Analytics sync failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    private function syncNewUsers($controller)
    {
        $this->info('📊 Syncing new users data...');
        
        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('new_users');
        if ($existingCache && !$existingCache->isStale(60) && !$this->option('force')) {
            $this->info('⏭️  New users data is fresh, skipping...');
            return;
        }

        // Process data in smaller chunks and aggregate results
        $dateGroups = [];
        $totalNewUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000; // Smaller batch size
        $hasMoreData = true;

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");
            
            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'tradingStatuses' => ['new', 'active', 'dormant 3-6 months', 'dormant 6-12 months', 'dormant more than 1 year'],
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ]
            ], 'POST');

            if (!$response['success']) {
                throw new \Exception('Failed to fetch new users data: ' . $response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            // Process this batch immediately and free memory
            foreach ($batchData as $user) {
                if (isset($user['tradingStatus']) && $user['tradingStatus'] === 'new') {
                    $totalNewUsers++;
                    $date = date('Y-m-d', strtotime($user['registrationDate']));
                    $dateGroups[$date] = ($dateGroups[$date] ?? 0) + 1;
                }
            }

            // Free memory
            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalNewUsers} new users from {$totalFetched} total records");

        // Sort by date
        ksort($dateGroups);

        $metadata = [
            'date_range' => [
                'start' => !empty($dateGroups) ? min(array_keys($dateGroups)) : null,
                'end' => !empty($dateGroups) ? max(array_keys($dateGroups)) : null,
            ],
        ];

        UserAnalyticsCache::updateOrCreateCache(
            'new_users',
            $dateGroups,
            $totalNewUsers,
            $metadata,
            $totalFetched
        );

        $this->info("✅ New users data cached successfully");
    }

    private function syncActiveUsers($controller)
    {
        $this->info('📊 Syncing active users data...');
        
        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('active_users');
        if ($existingCache && !$existingCache->isStale(60) && !$this->option('force')) {
            $this->info('⏭️  Active users data is fresh, skipping...');
            return;
        }

        // Process data in smaller chunks and aggregate results
        $activityGroups = [];
        $totalActiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000; // Smaller batch size
        $hasMoreData = true;

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");
            
            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'tradingStatuses' => ['new', 'active', 'dormant 3-6 months', 'dormant 6-12 months', 'dormant more than 1 year'],
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ]
            ], 'POST');

            if (!$response['success']) {
                throw new \Exception('Failed to fetch active users data: ' . $response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            // Process this batch immediately and free memory
            foreach ($batchData as $user) {
                if (isset($user['tradingStatus']) && $user['tradingStatus'] === 'active') {
                    $totalActiveUsers++;
                    if ($user['lastLoginDate']) {
                        $date = date('Y-m-d', strtotime($user['lastLoginDate']));
                        $activityGroups[$date] = ($activityGroups[$date] ?? 0) + 1;
                    } else {
                        $activityGroups['never'] = ($activityGroups['never'] ?? 0) + 1;
                    }
                }
            }

            // Free memory
            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalActiveUsers} active users from {$totalFetched} total records");

        // Sort by date
        ksort($activityGroups);

        UserAnalyticsCache::updateOrCreateCache(
            'active_users',
            $activityGroups,
            $totalActiveUsers,
            ['active_users' => $totalActiveUsers],
            $totalFetched
        );

        $this->info("✅ Active users data cached successfully");
    }

    private function syncInactiveUsers($controller)
    {
        $this->info('📊 Syncing inactive users data...');
        
        // Check if we need to refresh
        $existingCache = UserAnalyticsCache::getLatest('inactive_users');
        if ($existingCache && !$existingCache->isStale(60) && !$this->option('force')) {
            $this->info('⏭️  Inactive users data is fresh, skipping...');
            return;
        }

        // Process data in smaller chunks and aggregate results
        $dormancyGroups = [];
        $totalInactiveUsers = 0;
        $totalFetched = 0;
        $offset = 0;
        $batchSize = 2000; // Smaller batch size
        $hasMoreData = true;
        $inactiveStatuses = ['dormant 3-6 months', 'dormant 6-12 months', 'dormant more than 1 year'];

        while ($hasMoreData) {
            $this->info("📥 Fetching batch at offset {$offset}...");
            
            $response = $controller->callApiEndpointSingle('users?version=1.0.0', [
                'tradingStatuses' => $inactiveStatuses,
                'segment' => [
                    'limit' => $batchSize,
                    'offset' => $offset,
                ]
            ], 'POST');

            if (!$response['success']) {
                throw new \Exception('Failed to fetch inactive users data: ' . $response['error']);
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);
            $totalFetched += $batchCount;

            $this->info("📊 Processing {$batchCount} records...");

            // Process this batch immediately and free memory
            foreach ($batchData as $user) {
                if (isset($user['tradingStatus']) && in_array($user['tradingStatus'], $inactiveStatuses)) {
                    $totalInactiveUsers++;
                    $status = $user['tradingStatus'];
                    $dormancyGroups[$status] = ($dormancyGroups[$status] ?? 0) + 1;
                }
            }

            // Free memory
            unset($batchData);
            gc_collect_cycles();

            if ($batchCount < $batchSize) {
                $hasMoreData = false;
            } else {
                $offset += $batchSize;
            }
        }

        $this->info("🔍 Found {$totalInactiveUsers} inactive users from {$totalFetched} total records");

        $metadata = [
            'breakdown' => [
                'dormant_3_6_months' => $dormancyGroups['dormant 3-6 months'] ?? 0,
                'dormant_6_12_months' => $dormancyGroups['dormant 6-12 months'] ?? 0,
                'dormant_1_year_plus' => $dormancyGroups['dormant more than 1 year'] ?? 0,
            ],
        ];

        UserAnalyticsCache::updateOrCreateCache(
            'inactive_users',
            $dormancyGroups,
            $totalInactiveUsers,
            $metadata,
            $totalFetched
        );

        $this->info("✅ Inactive users data cached successfully");
    }
}
