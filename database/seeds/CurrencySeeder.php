<?php

use App\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('currencies')
            ->selectRaw('name, code, exchange_rate' )
            ->get()
            ->each(
                function ($old) {
                    Currency::create(
                        [
                        'name' => $old->name,
                        'code' => $old->code,
                        'exchange_rate' => $old->exchange_rate,
                        ]
                    );
                }
            );
    }
}
