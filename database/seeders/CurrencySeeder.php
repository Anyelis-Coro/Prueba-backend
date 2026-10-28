<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1.0000],
            ['name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.9200],
            ['name' => 'Bolívar Venezolano', 'symbol' => 'Bs', 'exchange_rate' => 36.5000],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}