# TradingPro Analytics Dashboard

This document describes the refactored dashboard implementation that uses only the real TradingPro API endpoint to create 3 analytical graphs.

## Single API Endpoint Used

**Endpoint**: `https://secure.tradingpro.com/rest/users?version=1.0.0`
**Method**: POST
**Authentication**: Bearer Token

## Dashboard Components

The dashboard displays 3 main graphs based on different trading status filters:

### 1. New Users Graph
- **API Call**: `tradingStatuses: ['new']`
- **Analysis**: Groups users by registration date (daily)
- **Chart Type**: Line chart showing new user registrations over time
- **Data Points**: Daily counts of new user registrations

### 2. Active Users Graph  
- **API Call**: `tradingStatuses: ['active']`
- **Analysis**: Groups users by last login date for activity analysis
- **Chart Type**: Line chart showing user activity patterns
- **Data Points**: Daily counts of active user logins

### 3. Inactive Users Graph
- **API Call**: `tradingStatuses: ['dormant 3-6 months', 'dormant 6-12 months', 'dormant more than 1 year']`
- **Analysis**: Groups users by dormancy period
- **Chart Type**: Doughnut chart showing distribution of inactive users
- **Data Points**: Breakdown by dormancy categories

## Controller Methods

### MarketingController
Located at: `app/Http/Controllers/MarketingController.php`

#### Available Methods:
1. `getNewUsersData()` - Fetches and analyzes new users by registration date
2. `getActiveUsersData()` - Fetches and analyzes active users by login activity  
3. `getInactiveUsersData()` - Fetches and analyzes inactive users by dormancy period
4. `getDashboardData()` - Combines all three data sets in a single response

## API Routes

All routes require authentication and are prefixed with `/api/dashboard/`:

- `GET /api/dashboard/new-users` - New users data
- `GET /api/dashboard/active-users` - Active users data  
- `GET /api/dashboard/inactive-users` - Inactive users data
- `GET /api/dashboard/all-data` - All dashboard data combined

## Livewire Component

### Dashboard Component
- **Location**: `app/Livewire/Dashboard.php`
- **View**: `resources/views/livewire/dashboard.blade.php`
- **Features**:
  - Auto-loads dashboard data on mount
  - Refresh button to reload data
  - Summary cards showing totals
  - Three interactive charts using Chart.js
  - Error handling and loading states

## Response Format

### New Users Data Response:
```json
{
  "success": true,
  "data": {
    "chart_data": {
      "2025-11-01": 5,
      "2025-11-02": 8,
      "2025-11-03": 12
    },
    "total_count": 25,
    "date_range": {
      "start": "2025-11-01",
      "end": "2025-11-03"
    }
  }
}
```

### Active Users Data Response:
```json
{
  "success": true,
  "data": {
    "chart_data": {
      "2025-11-01": 15,
      "2025-11-02": 18,
      "never": 3
    },
    "total_count": 36,
    "active_users": 36
  }
}
```

### Inactive Users Data Response:
```json
{
  "success": true,
  "data": {
    "chart_data": {
      "dormant 3-6 months": 10,
      "dormant 6-12 months": 8,
      "dormant more than 1 year": 15
    },
    "total_count": 33,
    "breakdown": {
      "dormant_3_6_months": 10,
      "dormant_6_12_months": 8,
      "dormant_1_year_plus": 15
    }
  }
}
```

## Frontend Implementation

### Chart.js Integration
- **Library**: Chart.js (loaded via CDN)
- **Chart Types**: Line charts for time-series data, Doughnut chart for categorical data
- **Responsive**: Charts adapt to container size
- **Interactive**: Hover effects and tooltips

### Styling
- **Framework**: Tailwind CSS
- **Design**: Clean, modern dashboard with cards and proper spacing
- **Responsive**: Mobile-friendly layout with grid system
- **Loading States**: Spinners and disabled states during API calls

## Usage Examples

### Access Dashboard
Navigate to `/dashboard` after logging in to view the analytics dashboard.

### Manual API Calls
```bash
# Get new users data
curl -X GET "http://your-app.com/api/dashboard/new-users" \
  -H "Authorization: Bearer your_laravel_token"

# Get all dashboard data
curl -X GET "http://your-app.com/api/dashboard/all-data" \
  -H "Authorization: Bearer your_laravel_token"
```

### Livewire Integration
```php
// In a Blade template
<livewire:dashboard />
```

## Key Features

✅ **Single API Source**: Uses only the real TradingPro API endpoint
✅ **Smart Filtering**: Different `tradingStatuses` for each graph
✅ **Real-time Data**: Fresh data on each refresh
✅ **Interactive Charts**: Professional Chart.js visualizations  
✅ **Responsive Design**: Works on all device sizes
✅ **Error Handling**: Graceful error states and messaging
✅ **Authentication**: Secure, authenticated routes
✅ **Performance**: Efficient data processing and caching-ready

## Testing

Run the dashboard tests:
```bash
php artisan test tests/Feature/DashboardTest.php
```

Tests verify:
- Authentication requirements for all endpoints
- Proper JSON response structures
- Route accessibility
- Data format consistency
