<?php

use Illuminate\Database\Seeder;

class PermisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $allValueSource = \DB::connection('mysql_source')
        ->table('permissions')
        ->selectRaw('name, guard_name')
        ->get()
        ->each(
            function ($old) {
                    $tableNames = config('permission.table_names');
                    Schema::create($tableNames['permissions'](
                        [
                            'name' => $old->name,
                            'guard_name' => $old->guard_name,
                        ]
                    ));

                }
            );
    }
}


// $tableNames = config('permission.table_names');

//         Schema::create($tableNames['permissions'], function (Blueprint $table) {
//             $table->increments('id');
//             $table->string('name');
//             $table->string('guard_name');
//             $table->timestamps();
//         });

//         Schema::table('roles', function (Blueprint $table) {
//             $table->string('guard_name')->nullable();
//         });

//         Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
//             $table->unsignedInteger('permission_id');
//             $table->morphs('model');

//             $table->foreign('permission_id')
//                 ->references('id')
//                 ->on($tableNames['permissions'])
//                 ->onDelete('cascade');

//             $table->primary(['permission_id', 'model_id', 'model_type']);
//         });

//         Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
//             $table->unsignedInteger('role_id');
//             $table->morphs('model');

//             $table->foreign('role_id')
//                 ->references('id')
//                 ->on($tableNames['roles'])
//                 ->onDelete('cascade');

//             $table->primary(['role_id', 'model_id', 'model_type']);
//         });

//         Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
//             $table->unsignedInteger('permission_id');
//             $table->unsignedInteger('role_id');

//             $table->foreign('permission_id')
//                 ->references('id')
//                 ->on($tableNames['permissions'])
//                 ->onDelete('cascade');

//             $table->foreign('role_id')
//                 ->references('id')
//                 ->on($tableNames['roles'])
//                 ->onDelete('cascade');

//             $table->primary(['permission_id', 'role_id']);

//             app('cache')->forget('spatie.permission.cache');
//         });
//     }
