<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                UserSeeder::class,
                CurrencySeeder::class,
                GeneralSettingSeeder::class,
                PermisionSeeder::class,
                RoleSeeder::class,
                RoleHasPermissionsSeeder::class,
                PosSettingSeeder::class,
                SupplierSeeder::class,
                CategorySeeder::class,
                UnitSeeder::class,
                BrandSeeder::class,
                TaxSeeder::class,
                ProductSeeder::class,


            ]
        );
    }
}
