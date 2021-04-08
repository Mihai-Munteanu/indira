<?php

use Illuminate\Database\Seeder;

class PermisionSeeder extends Seeder
{

    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('permissions')
        ->selectRaw('id, name, guard_name')
        ->get()
        ->each(
            function ($old) {
                    DB::table('permissions')->insert(
                        [
                        'id' => $old->id,
                        'name' => $old->name,
                        'guard_name' => $old->guard_name,
                        ]
                    );
            }
        );
    }
}
