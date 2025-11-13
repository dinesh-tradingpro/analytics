# Cron Job Setup for Analytics Sync

## Laravel Scheduler Setup

### 1. Add to System Cron
Add this single line to your system's cron:

```bash
# Edit cron jobs
crontab -e

# Add this line:
* * * * * cd /Users/dineshkumarvalan/git/work/TPAnalytics && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Verify Laravel Scheduler
The Laravel scheduler is already configured in `routes/console.php` to run:
- `analytics:sync` every hour
- With overlap protection
- Background execution
- Error/success logging

### 3. Manual Commands

Run sync manually:
```bash
php artisan analytics:sync
```

Force refresh (ignore cache freshness):
```bash
php artisan analytics:sync --force
```

Check scheduled tasks:
```bash
php artisan schedule:list
```

Run scheduler once (for testing):
```bash
php artisan schedule:run
```

### 4. Monitor Performance

The command logs detailed progress:
- Batch processing with 2000 records per batch
- Memory usage is kept minimal through streaming
- Progress indicators show fetching and processing
- Final statistics include total users found and processed

### 5. Cache Behavior

- Cache is considered "fresh" for 60 minutes
- Dashboard loads instantly from cache
- If no cache exists, returns 503 with instruction to run sync
- Cache includes metadata like timestamps and staleness indicators

### 6. Production Recommendations

1. **Run sync every hour during business hours**
2. **Run sync every 4 hours during off-hours**
3. **Monitor logs for failures**
4. **Set up alerts for sync failures**
5. **Consider running sync during low-traffic periods**

Example cron for production:
```bash
# Business hours (9 AM - 6 PM): every hour
0 9-18 * * * cd /path/to/project && php artisan analytics:sync

# Off hours: every 4 hours
0 0,4,8,20,22 * * * cd /path/to/project && php artisan analytics:sync
```

### 7. Memory and Performance

- Command uses up to 4GB memory limit
- Processes 2000 records per batch
- Typical run time: 10-15 minutes for 160k+ records
- No timeout limits for CLI execution
- Garbage collection after each batch
