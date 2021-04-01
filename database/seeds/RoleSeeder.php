<?php

use App\Roles;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('roles')
            ->selectRaw('name, description, is_active, guard_name')
            ->get()
            ->each(
                function ($old) {
                    Roles::create(
                        [
                            'name' => $old->name,
                            'description' => $old->description,
                            'is_active' => $old->is_active,
                            'guard_name' => $old->guard_name,
                        ]
                    );
                }
            );
    }
}
