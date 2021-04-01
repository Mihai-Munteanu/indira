<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('users')
            ->selectRaw('name, email, password, phone, company_name, role_id, is_active, is_deleted, biller_id, warehouse_id')
            ->get()
            ->each(
                function ($old) {
                    User::create(
                        [
                            'name' => $old->name,
                            'email' => $old->email,
                            'password' => $old->password,
                            'phone' => $old->phone,
                            'company_name' => $old->company_name,
                            'role_id' => $old->role_id,
                            'is_active' => $old->is_active,
                            'is_deleted' => $old->is_deleted,
                            // 'users_email_unique' => $old->users_email_unique,
                            'biller_id' => $old->biller_id,
                            'warehouse_id' => $old->warehouse_id,
                        ]
                    );
                }
            );
    }
}
