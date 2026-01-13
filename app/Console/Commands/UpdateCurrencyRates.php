<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update-rates {--currencies=* : Optional list of currency codes to update}';

    protected $description = 'Fetch latest currency exchange rates (USD base) and update config/currency_rates.php with multipliers (1 unit currency -> USD)';

    public function handle()
    {
        $this->info('Fetching latest exchange rates (base USD)...');

        $configured = config('currency_rates.rates', []);
        $currencies = $this->option('currencies');

        if (empty($currencies)) {
            $currencies = array_keys($configured);
        }

        $symbols = implode(',', array_map('strtoupper', $currencies));

        // Use frankfurter.app (free, open source, no key required)
        $url = 'https://api.frankfurter.app/latest?from=USD';

        try {
            $resp = Http::get($url);
            if (! $resp->ok()) {
                $this->error('Failed to fetch rates: HTTP '.$resp->status());
                $this->error('Response: '.$resp->body());

                return 1;
            }

            $data = $resp->json();
            if (! isset($data['rates']) || ! is_array($data['rates'])) {
                $this->error('Unexpected response from rates provider');
                $this->error('Full response: '.json_encode($data));

                return 1;
            }

            $ratesData = $data['rates'];

            $newRates = $configured;

            foreach ($ratesData as $code => $ratePerUsd) {
                $code = strtoupper($code);
                if ($ratePerUsd == 0) {
                    $this->warn("Skipping $code due to zero rate");

                    continue;
                }

                // Convert: API gives X = 1 USD in currency units. We need multiplier to convert 1 unit currency -> USD
                $multiplier = round(1 / $ratePerUsd, 8);
                $newRates[$code] = $multiplier;
                $this->info("$code -> multiplier $multiplier (1 $code = $multiplier USD)");
            }

            // Persist to config file
            $path = config_path('currency_rates.php');
            $export = var_export(['rates' => $newRates], true);
            $content = "<?php\n\nreturn ".$export.";\n";

            file_put_contents($path, $content);
            $this->info('Updated '.$path);

            // Clear config cache so app picks up changes
            \Artisan::call('config:clear');
            $this->info('Config cache cleared');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error fetching/updating rates: '.$e->getMessage());

            return 1;
        }
    }
}
