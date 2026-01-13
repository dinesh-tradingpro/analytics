<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'from_login_sid',
        'from_user_id',
        'transaction_type',
        'status',
        'processed_amount',
        'processed_amount_usd',
        'requested_amount',
        'requested_amount_usd',
        'processed_currency',
        'requested_currency',
        'transaction_date',
        'created_at_api',
        'processed_at_api',
        'metadata',
    ];

    protected $casts = [
        'processed_amount' => 'decimal:2',
        'processed_amount_usd' => 'decimal:2',
        'requested_amount' => 'decimal:2',
        'requested_amount_usd' => 'decimal:2',
        'transaction_date' => 'date',
        'created_at_api' => 'datetime',
        'processed_at_api' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get top transactions by amount
     */
    public static function getTopTransactions($transactionType, $limit = 10, $startDate = null, $endDate = null)
    {
        $query = static::selectRaw('
                id,
                transaction_id,
                from_user_id,
                from_login_sid,
                processed_amount_usd as amount,
                processed_currency,
                transaction_date,
                created_at_api
            ')
            ->where('transaction_type', $transactionType)
            ->where('status', 'approved')
            ->orderBy('processed_amount_usd', 'desc')
            ->limit($limit);

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        return $query->get();
    }

    /**
     * Get users with most repeat transactions
     */
    public static function getRepeatTransactionUsers($transactionType, $limit = 10, $startDate = null, $endDate = null)
    {
        $query = static::selectRaw('
                from_user_id,
                from_login_sid,
                COUNT(*) as transaction_count,
                SUM(processed_amount_usd) as total_amount,
                AVG(processed_amount_usd) as avg_amount,
                AVG(TIMESTAMPDIFF(SECOND, created_at_api, processed_at_api)) as avg_processing_time_seconds,
                MIN(transaction_date) as first_transaction,
                MAX(transaction_date) as last_transaction
            ')
            ->where('transaction_type', $transactionType)
            ->where('status', 'approved')
            ->whereNotNull('created_at_api')
            ->whereNotNull('processed_at_api')
            ->groupBy('from_user_id', 'from_login_sid')
            ->orderBy('transaction_count', 'desc')
            ->limit($limit);

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        return $query->get()->map(function ($user) {
            $user->first_transaction = \Carbon\Carbon::parse($user->first_transaction);
            $user->last_transaction = \Carbon\Carbon::parse($user->last_transaction);

            return $user;
        });
    }

    /**
     * Get daily transaction statistics
     */
    public static function getDailyStats($transactionType, $startDate, $endDate)
    {
        return static::selectRaw('
                transaction_date,
                COUNT(*) as count,
                SUM(processed_amount_usd) as total_amount,
                AVG(processed_amount_usd) as avg_amount,
                COUNT(DISTINCT from_user_id) as unique_users
            ')
            ->where('transaction_type', $transactionType)
            ->where('status', 'approved')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();
    }
}
