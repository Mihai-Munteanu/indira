<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_title');
            $table->string('site_logo')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_position')->nullable();
            $table->string('staff_access')->nullable();
            $table->string('date_format')->nullable();
            $table->string('theme')->nullable();
            $table->string('developed_by')->nullable();
            $table->string('invoice_format')->nullable();
            $table->integer('state')->nullable();
            $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
}
