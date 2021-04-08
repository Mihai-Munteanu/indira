<?php

use App\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{

    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('units')
        ->selectRaw('id, unit_code, unit_name, base_unit, operator, operation_value, is_active')
        ->get()
        ->each(
            function ($old) {
                Unit::create(
                    [
                        'id' => $old->id,
                        'unit_code' => $old->unit_code,
                        'unit_name' => $old->unit_name,
                        'base_unit' => $old->base_unit,
                        'operator' => $old->operator,
                        'operation_value' => $old->operation_value,
                        'is_active' => $old->is_active,
                    ]
                );
            }
        );
    }
}
