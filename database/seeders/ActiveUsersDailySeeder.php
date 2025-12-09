<?php

namespace Database\Seeders;

use App\Models\UserAnalyticsCache;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActiveUsersDailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding daily active users snapshots...');

        // Clear existing test data
        UserAnalyticsCache::where('metric_type', 'active_users_daily')->delete();

        // Generate fake data for the last 30 days
        $baseCount = 1200;

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            // Add some realistic variance (±100 users)
            $variance = rand(-100, 100);
            $count = $baseCount + $variance;

            // Weekend dips
            $dayOfWeek = Carbon::now()->subDays($i)->dayOfWeek;
            if ($dayOfWeek == Carbon::SATURDAY || $dayOfWeek == Carbon::SUNDAY) {
                $count = (int) ($count * 0.7); // 30% less on weekends
            }

            UserAnalyticsCache::create([
                'metric_type' => 'active_users_daily',
                'data_date' => $date,
                'chart_data' => [$date => $count],
                'total_count' => $count,
                'metadata' => [
                    'snapshot_date' => $date,
                    'description' => 'Daily active users snapshot (seeded)',
                    'day_of_week' => Carbon::parse($date)->format('l'),
                ],
                'total_records_fetched' => 168000 + rand(-1000, 1000),
                'synced_at' => Carbon::parse($date)->setTime(1, 0, 0),
                'created_at' => Carbon::parse($date)->setTime(1, 0, 0),
                'updated_at' => Carbon::parse($date)->setTime(1, 0, 0),
            ]);

            $this->command->info("Created snapshot for {$date}: {$count} active users");
        }

        $this->command->info('✅ Successfully seeded 30 days of active users data!');

        // Show summary
        $total = UserAnalyticsCache::where('metric_type', 'active_users_daily')->count();
        $avgCount = UserAnalyticsCache::where('metric_type', 'active_users_daily')->avg('total_count');
        $this->command->info("Total snapshots: {$total}");
        $this->command->info('Average daily active users: '.round($avgCount));
    }
}
