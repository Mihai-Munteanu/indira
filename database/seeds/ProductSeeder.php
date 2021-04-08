<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $allValueSource = \DB::connection('mysql_source')
            ->table('products')
            ->selectRaw('id, name, code, type, barcode_symbology, brand_id, category_id, unit_id, purchase_unit_id, sale_unit_id, cost, price, qty, alert_quantity, promotion, promotion_price, starting_date, last_date, tax_id, tax_method, image, featured, product_details, is_active, supplier_id, supplier_sku_code, url, sale_price')
            ->get()
            ->each(
                function ($old) {
                    Product::create(
                        [
                            'id' => $old->id,
                            'name' => $old->name,
                            'code' => $old->code,
                            'type' => $old->type,
                            'barcode_symbology' => $old->barcode_symbology,
                            'brand_id'=> $old->brand_id,
                            'category_id' => $old->category_id,
                            'unit_id' => $old->unit_id,
                            'purchase_unit_id' => $old->purchase_unit_id,
                            'sale_unit_id' => $old->sale_unit_id,
                            'cost' => $old->cost,
                            'price' => $old->price,
                            'qty' => $old->qty,
                            'alert_quantity' => $old->alert_quantity,
                            'promotion' => $old->promotion,
                            'promotion_price' => $old->promotion_price,
                            'starting_date' => $old->starting_date,
                            'last_date' => $old->last_date,
                            'tax_id' => $old->tax_id,
                            'tax_method' => $old->tax_method,
                            'image' => $old->image,
                            'featured' => $old->featured,
                            'product_details' => $old->product_details,
                            'is_active' => $old->is_active,
                            'supplier_id' => $old->supplier_id,
                            'supplier_sku_code' => $old->supplier_sku_code,
                            'url' => $old->url,
                            'sale_price' => $old->sale_price,
                        ]
                    );
                }
            );
    }
}
