<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteNonNullablecolumnsToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
            $table->dropColumn('item');
            $table->dropColumn('total_qty');
            $table->dropColumn('total_discount');
            $table->dropColumn('total_tax');
            $table->dropColumn('total_cost');
            $table->dropColumn('grand_total');
            $table->dropColumn('paid_amount');
            $table->dropColumn('status');
            $table->dropColumn('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
}
