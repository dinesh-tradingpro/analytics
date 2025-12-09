<?php

namespace Database\Seeders;

use App\Models\UserAnalyticsCache;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InactiveUsersDailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding daily inactive users snapshots...');

        // Clear existing test data
        UserAnalyticsCache::where('metric_type', 'inactive_users_daily')->delete();

        // Generate fake data for the last 30 days
        $baseCount = 58000; // Higher base for inactive users

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            // Add some realistic variance (±1000 users)
            $variance = rand(-1000, 1000);
            $count = $baseCount + $variance;

            // Weekend increases (more people become inactive on weekends)
            $dayOfWeek = Carbon::now()->subDays($i)->dayOfWeek;
            if ($dayOfWeek == Carbon::SATURDAY || $dayOfWeek == Carbon::SUNDAY) {
                $count = (int) ($count * 1.02); // 2% more on weekends
            }

            // Generate realistic trading status breakdown
            $statusBreakdown = [
                'new' => rand(8000, 9000),
                'active' => rand(7000, 8000),
                'deleted' => rand(10000, 11500),
                'dormant 3-6 months' => rand(2800, 3100),
                'dormant 6-12 months' => rand(4900, 5200),
                'dormant more than 1 year' => rand(24000, 25000),
            ];

            // Adjust to match total count
            $statusSum = array_sum($statusBreakdown);
            $ratio = $count / $statusSum;
            foreach ($statusBreakdown as $key => $value) {
                $statusBreakdown[$key] = (int) ($value * $ratio);
            }

            UserAnalyticsCache::create([
                'metric_type' => 'inactive_users_daily',
                'data_date' => $date,
                'chart_data' => [$date => $count],
                'total_count' => $count,
                'metadata' => [
                    'snapshot_date' => $date,
                    'description' => 'Daily inactive users snapshot (seeded)',
                    'day_of_week' => Carbon::parse($date)->format('l'),
                    'trading_status_breakdown' => $statusBreakdown,
                ],
                'total_records_fetched' => 168000 + rand(-1000, 1000),
                'synced_at' => Carbon::parse($date)->setTime(1, 0, 0),
                'created_at' => Carbon::parse($date)->setTime(1, 0, 0),
                'updated_at' => Carbon::parse($date)->setTime(1, 0, 0),
            ]);

            $this->command->info("Created snapshot for {$date}: {$count} inactive users");
        }

        $this->command->info('✅ Successfully seeded 30 days of inactive users data!');

        // Show summary
        $total = UserAnalyticsCache::where('metric_type', 'inactive_users_daily')->count();
        $avgCount = UserAnalyticsCache::where('metric_type', 'inactive_users_daily')->avg('total_count');
        $this->command->info("Total snapshots: {$total}");
        $this->command->info('Average daily inactive users: '.round($avgCount));
    }
}
