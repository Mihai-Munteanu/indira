<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
        ->table('categories')
        ->selectRaw('id, name, parent_id, is_active')
        ->get()
        ->each(
            function ($old) {
                Category::create(
                    [
                        'id' => $old->id,
                        'name' => $old->name,
                        'parent_id' => $old->parent_id,
                        'is_active' => $old->is_active,
                    ]
                );
            }
        );
    }
}
