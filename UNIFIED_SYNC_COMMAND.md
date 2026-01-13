# Transaction Sync Command

## Overview
Single unified command `sync:all-transactions` handles fetching detailed transaction data and computing analytics in one operation.

## New Command: `sync:all-transactions`

### Location
`app/Console/Commands/SyncAllTransactions.php`

### Features
- **Fetches detailed transactions** from API
- **Computes analytics automatically** from fetched data
- **Eliminates redundant API calls** - single comprehensive operation
- **Optional flags** for flexible usage patterns

### Usage

#### Full sync (fetch details + compute analytics)
```bash
php artisan sync:all-transactions
```

#### Fetch details without analytics computation
```bash
php artisan sync:all-transactions --skip-analytics
```

#### Recompute analytics from existing transaction data
```bash
php artisan sync:all-transactions --analytics-only
```

#### Force refresh all data
```bash
php artisan sync:all-transactions --force
```

#### Filter by transaction type
```bash
php artisan sync:all-transactions --type=deposit
php artisan sync:all-transactions --type=withdrawal
```

#### Custom date range
```bash
php artisan sync:all-transactions --start-date=2025-01-01 --end-date=2025-12-31
```

#### Custom batch size
```bash
php artisan sync:all-transactions --batch-size=5000
```

#### Combine options
```bash
php artisan sync:all-transactions --type=deposit --start-date=2025-11-01 --force --skip-analytics
```

## Previous Commands (Removed)

The old separate commands have been completely removed and replaced with `sync:all-transactions`:
- ~~`sync:transactions`~~ → Use `sync:all-transactions --analytics-only` for analytics-only
- ~~`sync:transaction-details`~~ → Use `sync:all-transactions` for full sync

## Benefits

### 1. **Reduced Complexity**
   - One command instead of two
   - Cleaner codebase
   - Easier to maintain

### 2. **Better Performance**
   - No separate count API call needed
   - Analytics computed from actual transaction data
   - Faster overall execution

### 3. **More Accurate Analytics**
   - Analytics now computed from detailed data (not counts)
   - Actual amounts included (previously counts-only API couldn't provide this)
   - Real transaction details available

### 4. **Flexibility**
   - Can fetch data without analytics (`--skip-analytics`)
   - Can recompute analytics anytime (`--analytics-only`)
   - No need to re-fetch just to recompute

### 5. **Single Source of Truth**
   - One command to rule them all
   - Consistent behavior across all use cases

## Database Schema
No changes required to existing tables:
- `transaction_details` - stores detailed transaction data
- `transaction_analytics_cache` - stores computed analytics

Both tables remain unchanged and compatible with the new command.

## Migration Path

### Before (Two Commands)
```bash
# Run details sync
php artisan sync:transaction-details

# Later, run analytics separately
php artisan sync:transactions
```

### After (One Command)
```bash
# Does both in one go
php artisan sync:all-transactions

# Or run separately if needed
php artisan sync:all-transactions --skip-analytics
# ... do something else ...
php artisan sync:all-transactions --analytics-only
```

## Implementation Details

### Data Flow
1. Fetches detailed transactions from API
2. Stores in `transaction_details` table
3. Processes stored records to compute analytics
4. Aggregates into 7 time periods:
   - `daily` - last 30 days
   - `weekly` - last 12 weeks
   - `monthly` - last 12 months
   - `yearly` - last 5 years
   - `current_month` - current month only
   - `last_7_days` - last 7 days only
   - `all_time` - all available data
5. Caches computed analytics in `transaction_analytics_cache` table

### Smart Features
- Automatic retry on 400 errors
- Consecutive error detection (stops after 5 consecutive errors)
- Batch processing for large datasets
- Duplicate detection via upsert
- Configurable batch size
- Date range filtering
- Transaction type filtering (deposit/withdrawal)
- Status support (approved/declined)

## Command Options Reference

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `--force` | Flag | false | Force refresh even if data exists |
| `--type` | String | both | Filter by transaction type (deposit/withdrawal) |
| `--start-date` | String | 2025-01-01 | Start date for sync |
| `--end-date` | String | yesterday | End date for sync |
| `--batch-size` | Integer | 2000 | Number of records per batch |
| `--skip-analytics` | Flag | false | Skip analytics cache computation |
| `--analytics-only` | Flag | false | Only compute analytics from existing data |

## Cron Job Example

```bash
# Daily sync at 2 AM
0 2 * * * cd /app && php artisan sync:all-transactions
```

## Troubleshooting

### Recompute analytics for a date range
```bash
php artisan sync:all-transactions --start-date=2025-11-01 --end-date=2025-11-30 --analytics-only
```

### Refresh all data from scratch
```bash
php artisan sync:all-transactions --force
```

### Check specific transaction type
```bash
php artisan sync:all-transactions --type=deposit --start-date=2025-12-01 --end-date=2025-12-18
```
