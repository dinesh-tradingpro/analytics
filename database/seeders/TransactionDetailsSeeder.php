<?php

namespace Database\Seeders;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding transaction details...');

        // Clear existing data
        TransactionDetail::truncate();

        $users = [1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010,
            2001, 2002, 2003, 2004, 2005, 3001, 3002, 3003, 4001, 4002];

        $transactions = [];
        $transactionId = 100000;

        // Generate data for last 90 days
        for ($i = 90; $i >= 0; $i--) {
            $baseDate = Carbon::now()->subDays($i);

            // 5-15 deposits per day
            $depositsCount = rand(5, 15);
            for ($d = 0; $d < $depositsCount; $d++) {
                $userId = $users[array_rand($users)];

                // Some users do repeat transactions
                if (rand(1, 100) < 30) { // 30% chance of being a repeat user
                    $userId = $users[array_rand(array_slice($users, 0, 10))]; // Use first 10 users more often
                }

                $createdAt = $baseDate->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));
                $processingTime = rand(30, 600); // 30 seconds to 10 minutes

                $transactions[] = [
                    'transaction_id' => $transactionId++,
                    'from_login_sid' => $userId,
                    'transaction_type' => 'deposit',
                    'status' => 'approved',
                    'processed_amount' => rand(100, 50000) / 10, // $10 to $5000
                    'transaction_date' => $baseDate->format('Y-m-d'),
                    'created_at_api' => $createdAt,
                    'processed_at_api' => $createdAt->copy()->addSeconds($processingTime),
                    'metadata' => json_encode(['test' => true]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 3-10 withdrawals per day
            $withdrawalsCount = rand(3, 10);
            for ($w = 0; $w < $withdrawalsCount; $w++) {
                $userId = $users[array_rand($users)];

                // Some users do repeat transactions
                if (rand(1, 100) < 25) { // 25% chance of being a repeat user
                    $userId = $users[array_rand(array_slice($users, 5, 10))]; // Different set of frequent users
                }

                $createdAt = $baseDate->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));
                $processingTime = rand(60, 900); // 1 minute to 15 minutes

                $transactions[] = [
                    'transaction_id' => $transactionId++,
                    'from_login_sid' => $userId,
                    'transaction_type' => 'withdrawal',
                    'status' => 'approved',
                    'processed_amount' => rand(50, 40000) / 10, // $5 to $4000
                    'transaction_date' => $baseDate->format('Y-m-d'),
                    'created_at_api' => $createdAt,
                    'processed_at_api' => $createdAt->copy()->addSeconds($processingTime),
                    'metadata' => json_encode(['test' => true]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Add some high-value transactions
        $highValueTransactions = [
            ['type' => 'deposit', 'amount' => 15000, 'user' => 1001],
            ['type' => 'deposit', 'amount' => 12500, 'user' => 1002],
            ['type' => 'deposit', 'amount' => 11000, 'user' => 1003],
            ['type' => 'deposit', 'amount' => 9800, 'user' => 2001],
            ['type' => 'deposit', 'amount' => 8500, 'user' => 2002],
            ['type' => 'withdrawal', 'amount' => 13000, 'user' => 3001],
            ['type' => 'withdrawal', 'amount' => 10500, 'user' => 3002],
            ['type' => 'withdrawal', 'amount' => 9200, 'user' => 3003],
            ['type' => 'withdrawal', 'amount' => 8800, 'user' => 4001],
            ['type' => 'withdrawal', 'amount' => 7500, 'user' => 4002],
        ];

        foreach ($highValueTransactions as $hvt) {
            $createdAt = Carbon::now()->subDays(rand(1, 30));
            $processingTime = rand(120, 1800); // 2 minutes to 30 minutes for high-value

            $transactions[] = [
                'transaction_id' => $transactionId++,
                'from_login_sid' => $hvt['user'],
                'transaction_type' => $hvt['type'],
                'status' => 'approved',
                'processed_amount' => $hvt['amount'],
                'transaction_date' => $createdAt->format('Y-m-d'),
                'created_at_api' => $createdAt,
                'processed_at_api' => $createdAt->copy()->addSeconds($processingTime),
                'metadata' => json_encode(['test' => true, 'high_value' => true]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks
        foreach (array_chunk($transactions, 500) as $chunk) {
            TransactionDetail::insert($chunk);
        }

        $this->command->info('Seeded '.count($transactions).' transaction details successfully!');
    }
}
