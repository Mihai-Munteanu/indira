<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('image')->nullable();
            $table->string('name');
// de redenumit sku
            $table->string('code')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_sku_code')->nullable();
            $table->string('url')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('cost')->nullable();
            $table->string('price')->nullable();
            $table->double('sale_price')->nullable();
// de redenumit in stock
            $table->double('qty')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();


//de sters tot de mai jos, inclusiv din seeder.
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('unit_id')->nullable();
//nu am gasit ce face purchase_unit_id
            $table->integer('purchase_unit_id')->nullable();;
//nu am gasit ce face purchase_unit_id
            $table->integer('sale_unit_id')->nullable();;
            $table->double('alert_quantity')->nullable();
            $table->tinyInteger('promotion')->nullable();
            $table->string('promotion_price')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('last_date')->nullable();
            $table->integer('tax_id')->nullable();
            $table->integer('tax_method')->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->text('product_details')->nullable();
            $table->boolean('is_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
