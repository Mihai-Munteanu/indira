<?php

use Illuminate\Database\Seeder;

class RoleHasPermissionsSeeder extends Seeder
{
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('role_has_permissions')
        ->selectRaw('permission_id, role_id')
        ->get()
        ->each(
            function ($old) {
                DB::table('role_has_permissions')->insert(
                    [
                    'permission_id' => $old->permission_id,
                    'role_id' => $old->role_id,
                    ]
                );
            }
        );
    }
}

