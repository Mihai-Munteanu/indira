<?php

use App\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('suppliers')
            ->selectRaw('id, name, image, company_name, vat_number, email, phone_number, address, city, state, postal_code, country, is_active')
            ->get()
            ->each(
                function ($old) {
                    Supplier::create(
                        [
                            'id' => $old->id,
                            'name' => $old->name,
                            'image' => $old->image,
                            'company_name' => $old->company_name,
                            'vat_number' => $old->vat_number,
                            'email' => $old->email,
                            'phone_number' => $old->phone_number,
                            'address' => $old->address,
                            'city' => $old->city,
                            'state' => $old->state,
                            'postal_code' => $old->postal_code,
                            'country' => $old->country,
                            'is_active' => $old->is_active,
                        ]
                    );
                }
            );
    }
}
