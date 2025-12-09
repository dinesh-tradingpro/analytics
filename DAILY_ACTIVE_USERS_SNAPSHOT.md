# Daily Active Users Snapshot System

## Overview
Modified the user analytics system to track daily active users as separate daily snapshots instead of a single aggregate record. Each day's execution creates a new record preserving historical data.

## Changes Made

### 1. Database Storage Pattern
**Before:** 
- Stored single aggregate record with `metric_type = 'active_users_new'`
- Each sync overwrite previous data using `data_date = today()`

**After:**
- Stores separate daily snapshot records with `metric_type = 'active_users_daily'`
- Each day creates a new record: `data_date` = specific date, preserving all historical snapshots
- Example format: `2025-12-08: 1277`, `2025-12-09: 1105`, etc.

### 2. Modified Files

#### `app/Console/Commands/SyncUsers.php`
**Changed Method:** `syncActiveUsers()`
- Modified metric type from `'active_users_new'` to `'active_users_daily'`
- Changed cache check to look for today's snapshot specifically
- Simplified storage: stores only today's count (not historical aggregation)
- Uses `updateOrCreate()` with both `metric_type` and `data_date` as unique keys
- Result: Each day's execution creates/updates only that day's record

**Key Logic:**
```php
// Check if today's snapshot already exists
$existingCache = UserAnalyticsCache::where('metric_type', 'active_users_daily')
    ->whereDate('data_date', $today)
    ->first();

// Store today's snapshot
UserAnalyticsCache::updateOrCreate(
    [
        'metric_type' => 'active_users_daily',
        'data_date' => $today,  // Unique per day
    ],
    [
        'chart_data' => [$today => $totalActiveUsers],
        'total_count' => $totalActiveUsers,
        'metadata' => [
            'snapshot_date' => $today,
            'description' => 'Daily active users snapshot'
        ],
        'total_records_fetched' => $totalFetched,
        'synced_at' => now(),
    ]
);
```

#### `app/Models/UserAnalyticsCache.php`
**Added Methods:**
1. `getDailySnapshots($metricType, $startDate, $endDate, $limit)`
   - Retrieves multiple daily snapshot records for a date range
   - Returns collection of records ordered by date

2. `getCombinedDailyChartData($metricType, $startDate, $endDate, $limit)`
   - Combines multiple daily snapshots into chart format
   - Returns array with `chart_data`, `total_count`, `record_count`
   - Used by frontend to display historical trends

**Added Constant:**
```php
const METRIC_TYPES = [
    // ... existing types ...
    'active_users_daily' => 'active_users_daily',
];
```

#### `app/Livewire/UserAnalyticsSimple.php`
**Modified Method:** `loadAnalyticsData()`
- Changed to use `getCombinedDailyChartData()` instead of `getLatest()`
- Loads last 365 days of daily snapshots
- Converts to chart-ready format for frontend display

**New Logic:**
```php
// Load daily snapshots for active users (last 365 days)
$activeUsersData = UserAnalyticsCache::getCombinedDailyChartData('active_users_daily', null, null, 365);
$this->activeUsers = (object)[
    'chart_data' => $activeUsersData['chart_data'],
    'total_count' => $activeUsersData['total_count'],
    'metadata' => ['snapshot_count' => $activeUsersData['record_count']]
];
```

### 3. How It Works

#### Daily Execution Flow:
1. Run command: `php artisan sync:users`
2. Command checks if today's snapshot already exists
3. If not exists (or `--force` flag), counts users with:
   - `firstDepositDate` not null
   - `lastLoginDate` = today's date
4. Stores result as new record with `data_date` = today
5. Previous days' records remain unchanged

#### Database Records Example:
```
| id | metric_type         | data_date  | total_count | chart_data            |
|----|---------------------|------------|-------------|-----------------------|
| 16 | active_users_daily  | 2025-12-08 | 1277        | {"2025-12-08": 1277}  |
| 17 | active_users_daily  | 2025-12-09 | 1105        | {"2025-12-09": 1105}  |
| 18 | active_users_daily  | 2025-12-10 | 1250        | {"2025-12-10": 1250}  |
```

### 4. Usage

#### Manual Sync (First Time):
```bash
php artisan sync:users --force
```

#### Daily Scheduled Sync:
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Run daily at 1:00 AM
    $schedule->command('sync:users')->dailyAt('01:00');
}
```

#### Force Re-sync Today:
```bash
php artisan sync:users --force
```

### 5. Benefits

1. **Historical Preservation:** Each day's data is permanently stored
2. **Trend Analysis:** Can analyze active user trends over time (daily, weekly, monthly)
3. **Data Integrity:** No risk of overwriting historical data
4. **Audit Trail:** Complete record of daily active user counts with timestamps
5. **Flexible Queries:** Can retrieve specific date ranges or last N days

### 6. Frontend Display

The chart in the user analytics dashboard now shows:
- Daily active users over the last 365 days
- Historical trend line showing changes over time
- Data from all preserved daily snapshots

### 7. Database Schema

Uses existing `user_analytics_cache` table structure:
- `metric_type`: 'active_users_daily'
- `data_date`: Specific date for the snapshot (unique per day)
- `total_count`: Number of active users for that day
- `chart_data`: JSON with date => count mapping
- `synced_at`: When the snapshot was created/updated

### 8. Active User Definition

An "active user" is counted if:
- `firstDepositDate` is not null (has made at least one deposit)
- `lastLoginDate` equals the snapshot date (logged in on that specific day)

### 9. Migration Notes

- Old data under `'active_users_new'` remains in database
- New snapshots use `'active_users_daily'` metric type
- Both can coexist without conflicts
- Frontend component automatically uses new snapshot data

## Testing

Verified working on 2025-12-08:
- Command executed successfully
- Created snapshot with 1277 active users
- Record stored with proper structure
- Subsequent runs with same date skip re-processing (unless `--force`)

## Future Enhancements

Potential improvements:
1. Add date range parameter to command for backfilling historical snapshots
2. Create aggregated views (weekly, monthly averages)
3. Add retention policy for old snapshots
4. Export snapshots to external analytics tools
