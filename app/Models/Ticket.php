<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'manager',
        'category_title',
        'ticket_date',
        'created_at_api',
        'closed_at',
        'metadata',
    ];

    protected $casts = [
        'ticket_date' => 'date',
        'created_at_api' => 'datetime',
        'closed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Count tickets by status.
     */
    public static function statusCounts(): array
    {
        $counts = static::selectRaw("CASE WHEN LOWER(status) = 'closed' THEN 'closed' ELSE 'open' END as status_group, COUNT(*) as total")
            ->groupBy('status_group')
            ->pluck('total', 'status_group')
            ->toArray();

        return [
            'open' => (int) ($counts['open'] ?? 0),
            'closed' => (int) ($counts['closed'] ?? 0),
            'other' => 0,
            'total' => array_sum($counts),
        ];
    }

    /**
     * Build date-based chart data for the last N days (total tickets created).
     */
    public static function dailyCounts(int $days = 30): array
    {
        $days = max(1, $days);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $rows = static::selectRaw('ticket_date, COUNT(*) as total')
            ->whereNotNull('ticket_date')
            ->where('ticket_date', '>=', $startDate->toDateString())
            ->groupBy('ticket_date')
            ->orderBy('ticket_date')
            ->get();

        $labels = [];
        $totalData = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $date;
            $totalData[$date] = 0;
        }

        foreach ($rows as $row) {
            $dateKey = Carbon::parse($row->ticket_date)->format('Y-m-d');
            $totalData[$dateKey] = (int) $row->total;
        }

        return [
            'labels' => array_values($labels),
            'total' => array_values($totalData),
        ];
    }

    /**
     * All-time daily counts from earliest ticket_date (total tickets created).
     */
    public static function dailyCountsAll(): array
    {
        $rows = static::selectRaw('ticket_date, COUNT(*) as total')
            ->whereNotNull('ticket_date')
            ->groupBy('ticket_date')
            ->orderBy('ticket_date')
            ->get();

        if ($rows->isEmpty()) {
            return ['labels' => [], 'total' => []];
        }

        $byDate = [];
        foreach ($rows as $row) {
            $date = Carbon::parse($row->ticket_date)->format('Y-m-d');
            $byDate[$date] = (int) $row->total;
        }

        ksort($byDate);
        $labels = array_keys($byDate);
        $total = array_values($byDate);

        return [
            'labels' => $labels,
            'total' => $total,
        ];
    }

    /**
     * Build manager-based chart data (top N managers by ticket volume).
     */
    public static function managerCounts(int $limit = 8): array
    {
        // Deprecated; kept for backward compatibility but returns empty
        return [
            'labels' => [],
            'open' => [],
            'closed' => [],
            'totals' => [],
        ];
    }

    /**
     * Category distribution with open/closed breakdown (top N by total).
     */
    public static function categoryCounts(int $limit = 9): array
    {
        $limit = max(1, $limit);

        // Use canonical order so the chart is consistently organized
        $canonical = [
            'Accounts',
            'Deposits',
            'Withdrawals',
            'Partnership',
            'Verification',
            'Copytrade',
            'Pamm',
            'Trade Investigation',
            'Others',
        ];

        $labels = array_slice($canonical, 0, min($limit, count($canonical)));
        if (empty($labels)) {
            return ['labels' => [], 'open' => [], 'closed' => [], 'totals' => []];
        }

        $rows = static::selectRaw('category_title as category, CASE WHEN LOWER(status) = "closed" THEN "closed" ELSE "open" END as status_group, COUNT(*) as total')
            ->whereIn('category_title', $labels)
            ->groupBy('category', 'status_group')
            ->get();

        $openData = array_fill(0, count($labels), 0);
        $closedData = array_fill(0, count($labels), 0);

        foreach ($rows as $row) {
            $i = array_search($row->category, $labels, true);
            if ($i === false) {
                continue;
            }
            if ($row->status_group === 'open') {
                $openData[$i] = (int) $row->total;
            } elseif ($row->status_group === 'closed') {
                $closedData[$i] = (int) $row->total;
            }
        }

        $totals = [];
        foreach ($labels as $idx => $label) {
            $totals[$idx] = ($openData[$idx] ?? 0) + ($closedData[$idx] ?? 0);
        }

        return [
            'labels' => $labels,
            'open' => $openData,
            'closed' => $closedData,
            'totals' => array_values($totals),
        ];
    }

    /**
     * Get the latest ticket creation timestamp from the source API.
     */
    public static function latestTicketCreatedAt(): ?Carbon
    {
        $value = static::max('created_at_api');

        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Calculate average processing time (created to closed) in hours.
     * Uses SQL to compute average time difference for closed tickets.
     */
    public static function averageProcessingTime(): ?float
    {
        $result = static::selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at_api, closed_at)) as avg_hours')
            ->whereNotNull('created_at_api')
            ->whereNotNull('closed_at')
            ->whereRaw('closed_at > created_at_api')
            ->first();

        if (! $result || $result->avg_hours === null) {
            return null;
        }

        return (float) $result->avg_hours;
    }

    /**
     * Count manager responses from comments for a given time range.
     * Only counts responses where comment.manager is set (not null).
     * Returns array of manager_id => response_count.
     */
    public static function managerResponseCounts(int $days): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $rows = static::where('created_at_api', '>=', $startDate)
            ->whereNotNull('metadata')
            ->get(['metadata']);

        $managerCounts = [];

        foreach ($rows as $row) {
            $metadata = $row->metadata;
            if (! is_array($metadata) || ! isset($metadata['comments']) || ! is_array($metadata['comments'])) {
                continue;
            }

            foreach ($metadata['comments'] as $comment) {
                if (is_array($comment) && isset($comment['manager']) && $comment['manager'] !== null) {
                    $managerId = $comment['manager'];
                    $managerCounts[$managerId] = ($managerCounts[$managerId] ?? 0) + 1;
                }
            }
        }

        // Sort by count descending, then by manager_id
        arsort($managerCounts);

        return $managerCounts;
    }
}
