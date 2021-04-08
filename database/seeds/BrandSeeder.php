<?php

use App\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{

    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('brands')
        ->selectRaw('id, title, image, is_active')
        ->get()
        ->each(
            function ($old) {
                Brand::create(
                    [
                        'id' => $old->id,
                        'title' => $old->title,
                        'image' => $old->image,
                        'is_active' => $old->is_active,

                    ]
                );
            }
        );
    }
}
