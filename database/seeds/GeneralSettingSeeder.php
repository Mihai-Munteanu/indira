<?php

use App\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('general_settings')
            ->selectRaw('site_title, site_logo' )
            ->get()
            ->each(
                function ($old) {
                    GeneralSetting::create(
                        [
                        'site_title' => $old->site_title,
                        'site_logo' => $old->site_logo,
                        ]
                    );
                }
            );
    }
}
