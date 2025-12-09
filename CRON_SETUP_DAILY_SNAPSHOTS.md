# Cron Setup for Daily Active Users Snapshot

## Option 1: Laravel Scheduler (Recommended)

### Step 1: Update `app/Console/Kernel.php`

Add this to the `schedule()` method:

```php
protected function schedule(Schedule $schedule)
{
    // Sync user analytics daily at 1:00 AM
    $schedule->command('sync:users')
        ->dailyAt('01:00')
        ->timezone('UTC')  // Adjust to your timezone
        ->onSuccess(function () {
            \Log::info('User analytics sync completed successfully');
        })
        ->onFailure(function () {
            \Log::error('User analytics sync failed');
        });
}
```

### Step 2: Add Laravel Scheduler to System Cron

Run `crontab -e` and add this single line:

```bash
* * * * * cd /Users/dineshkumarvalan/git/work/TPAnalytics && php artisan schedule:run >> /dev/null 2>&1
```

This runs Laravel's scheduler every minute, which then executes your scheduled commands at the specified times.

## Option 2: Direct Cron Job

If you prefer to run the command directly via cron without Laravel scheduler:

### Add to System Cron

Run `crontab -e` and add:

```bash
# Run user analytics sync daily at 1:00 AM UTC
0 1 * * * cd /Users/dineshkumarvalan/git/work/TPAnalytics && php artisan sync:users >> /Users/dineshkumarvalan/git/work/TPAnalytics/storage/logs/cron-sync-users.log 2>&1
```

## Verification

### Check if Cron is Running

```bash
# View current crontab
crontab -l

# Check Laravel scheduler log
tail -f /Users/dineshkumarvalan/git/work/TPAnalytics/storage/logs/laravel.log

# Check custom cron log (if using Option 2)
tail -f /Users/dineshkumarvalan/git/work/TPAnalytics/storage/logs/cron-sync-users.log
```

### Test Manually

```bash
cd /Users/dineshkumarvalan/git/work/TPAnalytics

# Run sync
php artisan sync:users

# Check if today's snapshot was created
php artisan tinker
>>> \App\Models\UserAnalyticsCache::where('metric_type', 'active_users_daily')->whereDate('data_date', today())->first()
```

## Monitoring

### Check Last Sync Status

```bash
php artisan tinker
>>> $last = \App\Models\UserAnalyticsCache::where('metric_type', 'active_users_daily')->latest('synced_at')->first()
>>> echo "Last sync: " . $last->synced_at . " | Count: " . $last->total_count
```

### View All Daily Snapshots

```bash
php artisan tinker
>>> \App\Models\UserAnalyticsCache::where('metric_type', 'active_users_daily')->orderBy('data_date', 'desc')->get(['data_date', 'total_count', 'synced_at'])
```

## Timezone Considerations

**Important:** Ensure consistency between:
1. Cron timezone
2. Laravel app timezone (`config/app.php`)
3. Database timezone
4. Server timezone

Current setup uses:
- Date comparison: `lastLoginDate === today()`
- Today's date is determined by PHP's `date('Y-m-d')` which uses server timezone

## Troubleshooting

### Command Not Running

1. Check cron is active: `sudo launchctl list | grep cron` (macOS)
2. Check cron logs: `grep CRON /var/log/system.log` (macOS)
3. Verify path and permissions
4. Test command manually: `cd /path && php artisan sync:users`

### Duplicate Snapshots

If running multiple times per day:
- First run creates snapshot
- Subsequent runs skip (unless `--force` flag)
- This is by design to prevent overwrites

### Missing Snapshots

If a day is missed:
- Manual backfill: `php artisan sync:users --force`
- Note: This creates today's snapshot, not historical dates
- Historical backfill would require command enhancement

## Production Best Practices

1. **Notifications:** Set up email/Slack notifications on failures
2. **Monitoring:** Use Laravel Horizon or similar for job monitoring
3. **Logging:** Ensure proper log rotation
4. **Backup:** Regular database backups to preserve snapshots
5. **Validation:** Check snapshot counts for anomalies
6. **Documentation:** Keep track of when snapshots started for reporting

## Example Cron Setup with Logging

```bash
# User Analytics Sync - Daily at 1:00 AM UTC
0 1 * * * cd /Users/dineshkumarvalan/git/work/TPAnalytics && /usr/bin/php artisan sync:users >> /Users/dineshkumarvalan/git/work/TPAnalytics/storage/logs/cron-sync-$(date +\%Y-\%m-\%d).log 2>&1
```

This creates a separate log file for each day: `cron-sync-2025-12-08.log`
