<?php

namespace Database\Seeders;

use App\Models\AuthorizedEmail;
use Illuminate\Database\Seeder;

class AuthorizedEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmails = config('authorized_emails.admin_emails', []);

        foreach ($adminEmails as $email) {
            AuthorizedEmail::updateOrCreate(
                ['email' => $email],
                [
                    'added_by_email' => 'system',
                    'notes' => 'Admin email added automatically from config',
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Authorized admin emails have been seeded.');
    }
}