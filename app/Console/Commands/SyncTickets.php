<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:tickets
                            {--statuses=* : Ticket statuses to fetch (open, closed)}
                            {--batch-size=1000 : Number of tickets to fetch per request}
                            {--force : Force refresh even if records already exist}
                            {--debug : Log API status and sample payload size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync help-desk tickets, capturing status, manager, and created date for analytics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting ticket sync...');

        $startTime = now();
        $batchSize = max(1, (int) $this->option('batch-size'));
        $statuses = $this->option('statuses');
        $statuses = array_filter(array_map('strtolower', $statuses ?: ['open', 'closed']));
        if (empty($statuses)) {
            $statuses = ['open', 'closed'];
        }

        $controller = new class extends Controller
        {
            public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
            {
                return parent::callApiEndpointSingle($endpoint, $params, $method);
            }
        };

        $offset = 0;
        $totalFetched = 0;
        $stored = 0;
        $statusTally = ['open' => 0, 'closed' => 0, 'other' => 0];

        try {
            do {
                $this->info("📥 Fetching tickets offset {$offset} (batch {$batchSize})...");

                $payload = [
                    'statuses' => $statuses,
                    'withComments' => true,
                    'orders' => [
                        [
                            'field' => 'createdAt',
                            'direction' => 'DESC',
                        ],
                    ],
                    'segment' => [
                        'limit' => $batchSize,
                        'offset' => $offset,
                    ],
                ];

                $response = $controller->callApiEndpointSingle('help-desk/tickets', $payload, 'POST');

                if (! $response['success']) {
                    $this->error('❌ API error: '.($response['error'] ?? 'Unknown error'));
                    break;
                }

                if ($this->option('debug')) {
                    $this->line('   Debug: status='.$response['status_code'].' count='.count($response['data'] ?? []));
                }

                $tickets = $response['data'] ?? [];

                if ($this->option('debug') && $offset === 0 && ! empty($tickets)) {
                    $this->line('   Debug: First ticket keys = '.implode(', ', array_keys((array) $tickets[0])));
                    $this->line('   Debug: First ticket = '.json_encode($tickets[0]));
                }
                $count = count($tickets);
                $totalFetched += $count;

                if ($count === 0) {
                    break;
                }

                $records = [];

                foreach ($tickets as $ticket) {
                    $ticketId = $ticket['id'] ?? $ticket['ticketId'] ?? null;
                    if (! $ticketId) {
                        continue;
                    }

                    $status = strtolower($ticket['status'] ?? 'unknown');

                    $createdAt = null;
                    if (! empty($ticket['createdAt'])) {
                        try {
                            $createdAt = Carbon::parse($ticket['createdAt']);
                        } catch (\Exception $e) {
                            $createdAt = null;
                        }
                    }
                    $ticketDate = $createdAt ? $createdAt->toDateString() : null;

                    // Extract closed_at from comments
                    $closedAt = $this->extractClosedTimestamp($ticket);

                    // Map category title from API to our canonical set (title only)
                    [$categoryId, $categoryTitle] = $this->mapCategory($ticket);

                    // Extract comments data to store manager response information
                    $comments = [];
                    if (! empty($ticket['comments']) && is_array($ticket['comments'])) {
                        foreach ($ticket['comments'] as $comment) {
                            if (is_array($comment)) {
                                $comments[] = [
                                    'manager' => $comment['manager'] ?? null,
                                    'text' => $comment['text'] ?? null,
                                    'createdAt' => $comment['createdAt'] ?? null,
                                ];
                            }
                        }
                    }

                    $metadata = [
                        'priority' => $ticket['priority'] ?? null,
                        'category' => $ticket['category'] ?? null,
                        'source' => $ticket['source'] ?? null,
                        'comments' => $comments,
                    ];

                    $metadataJson = $metadata ? json_encode(array_filter($metadata, fn ($value) => ! is_null($value) && $value !== [])) : null;

                    $records[] = [
                        'ticket_id' => (string) $ticketId,
                        'status' => $status,
                        'manager' => null,
                        'category_title' => $categoryTitle,
                        'ticket_date' => $ticketDate,
                        'created_at_api' => $createdAt,
                        'closed_at' => $closedAt,
                        'metadata' => $metadataJson,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (array_key_exists($status, $statusTally)) {
                        $statusTally[$status]++;
                    } else {
                        $statusTally['other']++;
                    }
                }

                if (! empty($records)) {
                    Ticket::upsert(
                        $records,
                        ['ticket_id'],
                        ['status', 'manager', 'category_title', 'ticket_date', 'created_at_api', 'closed_at', 'metadata', 'updated_at']
                    );

                    $stored += count($records);
                }

                unset($tickets, $records);
                gc_collect_cycles();

                $offset += $batchSize;
            } while ($count === $batchSize);

            $duration = $startTime->diffInSeconds(now());

            $this->info("✅ Synced {$stored} tickets ({$statusTally['open']} open, {$statusTally['closed']} closed) in {$duration} seconds. Total fetched: {$totalFetched}.");

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Ticket sync failed: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return 1;
        }
    }

    private function mapCategory(array $ticket): array
    {
        // Canonical categories
        $categories = [
            1 => 'Accounts',
            2 => 'Deposits',
            3 => 'Withdrawals',
            4 => 'Partnership',
            5 => 'Verification',
            6 => 'Copytrade',
            7 => 'Pamm',
            8 => 'Trade Investigation',
            9 => 'Others',
        ];

        // Try common API shapes
        $id = $ticket['categoryId'] ?? ($ticket['category']['id'] ?? null);
        $title = $ticket['categoryTitle'] ?? ($ticket['category']['title'] ?? ($ticket['category'] ?? null));

        if (is_numeric($id)) {
            $id = (int) $id;
            $mappedTitle = $categories[$id] ?? ($title ?: 'Others');

            return [$id, (string) $mappedTitle];
        }

        if (is_string($title) && $title !== '') {
            // Normalize and try to match known titles
            $norm = strtolower(trim($title));
            foreach ($categories as $cid => $ctitle) {
                if (strtolower($ctitle) === $norm) {
                    return [$cid, $ctitle];
                }
            }

            // Fallback unknown title
            return [9, $title];
        }

        // Default fallback
        return [9, 'Others'];
    }

    /**
     * Extract closed timestamp from ticket comments.
     * Looks for "Status changed: opened => closed" or similar patterns.
     */
    private function extractClosedTimestamp(array $ticket): ?Carbon
    {
        if (empty($ticket['comments']) || ! is_array($ticket['comments'])) {
            return null;
        }

        foreach ($ticket['comments'] as $comment) {
            if (! is_array($comment) || empty($comment['text'])) {
                continue;
            }

            $text = $comment['text'];

            // Look for status change to closed
            if ((stripos($text, 'Status changed') !== false || stripos($text, 'status changed') !== false)
                && stripos($text, 'closed') !== false) {

                // Try updatedAt first, then createdAt
                $timestamp = $comment['updatedAt'] ?? $comment['createdAt'] ?? null;

                if ($timestamp) {
                    try {
                        return Carbon::parse($timestamp);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        return null;
    }
}
