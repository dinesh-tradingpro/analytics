<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Torann\Currency\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::updateOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'format' => '1,0.00',
            'exchange_rate' => 1,
            'active' => true,
        ]);

        Currency::updateOrCreate(['code' => 'IDR'], [
            'name' => 'Indonesian Rupiah',
            'code' => 'IDR',
            'symbol' => 'Rp',
            'format' => '1,0.00',
            'exchange_rate' => 0.000064, // Approximate 1 USD = 15500 IDR
            'active' => true,
        ]);

        Currency::updateOrCreate(['code' => 'MYR'], [
            'name' => 'Malaysian Ringgit',
            'code' => 'MYR',
            'symbol' => 'RM',
            'format' => '1,0.00',
            'exchange_rate' => 0.22, // Approximate 1 USD = 4.5 MYR
            'active' => true,
        ]);
    }
}
