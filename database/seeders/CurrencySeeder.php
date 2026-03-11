<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'COP', 'name' => 'Colombian Peso', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$'],
            ['code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => '$'],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }

        // Exchange Rates (Base USD)
        $rates = [
            ['from' => 'USD', 'to' => 'COP', 'rate' => 3900.00],
            ['from' => 'USD', 'to' => 'EUR', 'rate' => 0.92],
            ['from' => 'USD', 'to' => 'MXN', 'rate' => 17.00],
            ['from' => 'USD', 'to' => 'ARS', 'rate' => 850.00],
            ['from' => 'USD', 'to' => 'BRL', 'rate' => 4.95],
        ];

        foreach ($rates as $rate) {
            ExchangeRate::updateOrCreate(
                ['from_currency' => $rate['from'], 'to_currency' => $rate['to']],
                ['rate' => $rate['rate']]
            );
            
            // Inverse
            ExchangeRate::updateOrCreate(
                ['from_currency' => $rate['to'], 'to_currency' => $rate['from']],
                ['rate' => 1 / $rate['rate']]
            );
        }
    }
}
