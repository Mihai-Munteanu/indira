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
            ->selectRaw('site_title, site_logo, currency, staff_access, date_format, developed_by, invoice_format, state, theme, currency_position', )
            ->get()
            ->each(
                function ($old) {
                    GeneralSetting::create(
                        [
                        'site_title' => $old->site_title,
                        'site_logo' => $old->site_logo,
                        'currency' => $old->currency,
                        'staff_access' => $old->staff_access,
                        'date_format' => $old->date_format,
                        'developed_by' => $old->developed_by,
                        'invoice_format' => $old->invoice_format,
                        'state' => $old->state,
                        'theme' => $old->theme,
                        'currency_position' => $old->currency_position,
                        ]
                    );
                }
            );
    }
}
