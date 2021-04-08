<?php

use App\PosSetting;
use Illuminate\Database\Seeder;

class PosSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('pos_setting')
            ->selectRaw('id, customer_id, warehouse_id, biller_id, product_number, stripe_public_key, stripe_secret_key, keybord_active')
            ->get()
            ->each(
                function ($old) {
                    PosSetting::create(
                        [
                            'id' => $old->id,
                            'customer_id' => $old->customer_id,
                            'warehouse_id' => $old->warehouse_id,
                            'biller_id' => $old->biller_id,
                            'product_number' => $old->product_number,
                            'stripe_public_key' => $old->stripe_public_key,
                            'stripe_secret_key' => $old->stripe_secret_key,
                            'keybord_active' => $old->keybord_active,
                        ]
                    );
                }
            );
    }
}
