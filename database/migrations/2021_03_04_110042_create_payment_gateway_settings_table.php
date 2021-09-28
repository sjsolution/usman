<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentGatewaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->boolean('is_default')->default(0)->comment('0: Hisabe, 1: Fatorah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway_settings');
    }
}
