<?php

use App\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('taxes')
        ->selectRaw('id, name, rate, is_active')
        ->get()
        ->each(
            function ($old) {
                Tax::create(
                    [
                        'id' => $old->id,
                        'name' => $old->name,
                        'rate' => $old->rate,
                        'is_active' => $old->is_active,

                    ]
                );
            }
        );
    }
}
