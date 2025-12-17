<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Console\Command;

class SyncManagers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:managers
                            {--debug : Log API status and sample payload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync managers from the CRM API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting managers sync...');

        $controller = new class extends Controller
        {
            public function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
            {
                return parent::callApiEndpointSingle($endpoint, $params, $method);
            }
        };

        try {
            $response = $controller->callApiEndpointSingle('managers', [], 'GET');

            if (! $response['success']) {
                $this->error('❌ API error: '.($response['error'] ?? 'Unknown error'));

                return 1;
            }

            if ($this->option('debug')) {
                $this->line('   Debug: status='.$response['status_code'].' count='.count($response['data'] ?? []));
            }

            $managers = $response['data'] ?? [];

            if (empty($managers)) {
                $this->info('✅ No managers to sync.');

                return 0;
            }

            $records = [];
            foreach ($managers as $manager) {
                $managerId = $manager['id'] ?? null;
                if (! $managerId) {
                    continue;
                }

                $records[] = [
                    'manager_id' => (int) $managerId,
                    'full_name' => $manager['fullName'] ?? null,
                    'phone' => $manager['phone'] ?? null,
                    'email' => $manager['email'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! empty($records)) {
                Manager::upsert(
                    $records,
                    ['manager_id'],
                    ['full_name', 'phone', 'email', 'updated_at']
                );

                $this->info('✅ Synced '.count($records).' managers.');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Managers sync failed: '.$e->getMessage());

            return 1;
        }
    }
}
