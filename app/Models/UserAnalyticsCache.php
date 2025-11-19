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
}
