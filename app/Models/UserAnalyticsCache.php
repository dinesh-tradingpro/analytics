<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnalyticsCache extends Model
{
    use HasFactory;

    protected $table = 'user_analytics_cache';

    protected $fillable = [
        'metric_type',
        'chart_data',
        'total_count',
        'metadata',
        'total_records_fetched',
        'data_date',
        'synced_at',
    ];

    protected $casts = [
        'chart_data' => 'array',
        'metadata' => 'array',
        'data_date' => 'datetime',
        'synced_at' => 'datetime',
    ];

    const METRIC_TYPES = [
        'new_users' => 'new_users',
        'active_users' => 'active_users',
        'inactive_users' => 'inactive_users',
        // New criteria-based metrics
        'new_users_new' => 'new_users_new',
        'active_users_new' => 'active_users_new',
        'inactive_users_new' => 'inactive_users_new',
        // Daily snapshot metrics
        'active_users_daily' => 'active_users_daily',
        'inactive_users_daily' => 'inactive_users_daily',
    ];

    /**
     * Get the latest cached data for a specific metric type
     */
    public static function getLatest($metricType)
    {
        return self::where('metric_type', $metricType)
            ->orderBy('synced_at', 'desc')
            ->first();
    }

    /**
     * Get cached data for a specific date
     */
    public static function getForDate($metricType, $date)
    {
        return self::where('metric_type', $metricType)
            ->whereDate('data_date', $date)
            ->orderBy('synced_at', 'desc')
            ->first();
    }

    /**
     * Check if data is stale (older than specified minutes)
     */
    public function isStale($minutes = 60)
    {
        return $this->synced_at->diffInMinutes(now()) > $minutes;
    }

    /**
     * Update or create cache entry
     */
    public static function updateOrCreateCache($metricType, $chartData, $totalCount, $metadata = [], $totalFetched = 0)
    {
        return self::updateOrCreate(
            [
                'metric_type' => $metricType,
                'data_date' => today(),
            ],
            [
                'chart_data' => $chartData,
                'total_count' => $totalCount,
                'metadata' => $metadata,
                'total_records_fetched' => $totalFetched,
                'synced_at' => now(),
            ]
        );
    }

    /**
     * Get daily snapshots for a date range
     */
    public static function getDailySnapshots($metricType, $startDate = null, $endDate = null, $limit = 365)
    {
        $query = self::where('metric_type', $metricType)
            ->orderBy('data_date', 'desc');

        if ($startDate) {
            $query->where('data_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('data_date', '<=', $endDate);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get combined chart data from daily snapshots
     */
    public static function getCombinedDailyChartData($metricType, $startDate = null, $endDate = null, $limit = 365)
    {
        $snapshots = self::getDailySnapshots($metricType, $startDate, $endDate, $limit);

        $combinedData = [];
        $latestCount = 0;

        foreach ($snapshots as $snapshot) {
            $date = $snapshot->data_date->format('Y-m-d');
            $combinedData[$date] = $snapshot->total_count;
        }

        ksort($combinedData);

        // Get the most recent day's count as the "current" count
        if (! empty($combinedData)) {
            $latestCount = end($combinedData);
        }

        return [
            'chart_data' => $combinedData,
            'total_count' => $latestCount, // Changed: returns latest day's count, not sum
            'record_count' => count($combinedData),
        ];
    }
}
