<?php

namespace App\Console\Commands;

use App\Models\AuthorizedEmail;
use Illuminate\Console\Command;

class SyncAuthorizedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:authorized-emails 
                           {--force : Force sync even if emails already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync authorized emails from config to database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $adminEmails = config('authorized_emails.admin_emails', []);
        
        if (empty($adminEmails)) {
            $this->warn('No admin emails found in config.');
            return;
        }

        $this->info('Syncing authorized emails...');
        
        foreach ($adminEmails as $email) {
            $exists = AuthorizedEmail::where('email', $email)->exists();
            
            if ($exists && !$this->option('force')) {
                $this->line("Email {$email} already exists, skipping...");
                continue;
            }
            
            AuthorizedEmail::updateOrCreate(
                ['email' => $email],
                [
                    'added_by_email' => 'system',
                    'notes' => 'Admin email synced from config',
                    'is_active' => true,
                ]
            );
            
            $this->info("✓ Added/Updated: {$email}");
        }

        $this->newLine();
        $this->info('Authorized emails sync completed!');
    }
}