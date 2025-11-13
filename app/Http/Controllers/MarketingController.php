<?php

namespace App\Http\Controllers;

use App\Models\UserAnalyticsCache;

class MarketingController extends Controller
{
    /**
     * Get new users data for dashboard graph (from cache)
     * Based on registration date analysis
     */
    public function getNewUsersData()
    {
        $cachedData = UserAnalyticsCache::getLatest('new_users');

        if (! $cachedData) {
            return response()->json([
                'success' => false,
                'message' => 'No cached data available. Please run: php artisan analytics:sync',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'chart_data' => $cachedData->chart_data,
                'total_count' => $cachedData->total_count,
                'total_fetched' => $cachedData->total_records_fetched,
                'date_range' => $cachedData->metadata['date_range'] ?? null,
                'cached_at' => $cachedData->synced_at->toISOString(),
                'is_stale' => $cachedData->isStale(60),
            ],
        ]);
    }

    /**
     * Get active users data for dashboard graph (from cache)
     */
    public function getActiveUsersData()
    {
        $cachedData = UserAnalyticsCache::getLatest('active_users');

        if (! $cachedData) {
            return response()->json([
                'success' => false,
                'message' => 'No cached data available. Please run: php artisan analytics:sync',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'chart_data' => $cachedData->chart_data,
                'total_count' => $cachedData->total_count,
                'total_fetched' => $cachedData->total_records_fetched,
                'active_users' => $cachedData->metadata['active_users'] ?? $cachedData->total_count,
                'cached_at' => $cachedData->synced_at->toISOString(),
                'is_stale' => $cachedData->isStale(60),
            ],
        ]);
    }

    /**
     * Get inactive users data for dashboard graph (from cache)
     */
    public function getInactiveUsersData()
    {
        $cachedData = UserAnalyticsCache::getLatest('inactive_users');

        if (! $cachedData) {
            return response()->json([
                'success' => false,
                'message' => 'No cached data available. Please run: php artisan analytics:sync',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'chart_data' => $cachedData->chart_data,
                'total_count' => $cachedData->total_count,
                'total_fetched' => $cachedData->total_records_fetched,
                'breakdown' => $cachedData->metadata['breakdown'] ?? [],
                'cached_at' => $cachedData->synced_at->toISOString(),
                'is_stale' => $cachedData->isStale(60),
            ],
        ]);
    }

    /**
     * Get all dashboard data in one call
     */
    public function getDashboardData()
    {
        $newUsersResponse = $this->getNewUsersData();
        $activeUsersResponse = $this->getActiveUsersData();
        $inactiveUsersResponse = $this->getInactiveUsersData();

        $newUsersData = json_decode($newUsersResponse->getContent(), true);
        $activeUsersData = json_decode($activeUsersResponse->getContent(), true);
        $inactiveUsersData = json_decode($inactiveUsersResponse->getContent(), true);

        return response()->json([
            'success' => true,
            'data' => [
                'new_users' => $newUsersData['success'] ? $newUsersData['data'] : null,
                'active_users' => $activeUsersData['success'] ? $activeUsersData['data'] : null,
                'inactive_users' => $inactiveUsersData['success'] ? $inactiveUsersData['data'] : null,
            ],
            'summary' => [
                'total_new' => $newUsersData['success'] ? $newUsersData['data']['total_count'] : 0,
                'total_active' => $activeUsersData['success'] ? $activeUsersData['data']['total_count'] : 0,
                'total_inactive' => $inactiveUsersData['success'] ? $inactiveUsersData['data']['total_count'] : 0,
                'total_fetched_new' => $newUsersData['success'] ? $newUsersData['data']['total_fetched'] : 0,
                'total_fetched_active' => $activeUsersData['success'] ? $activeUsersData['data']['total_fetched'] : 0,
                'total_fetched_inactive' => $inactiveUsersData['success'] ? $inactiveUsersData['data']['total_fetched'] : 0,
            ],
        ]);
    }
}
