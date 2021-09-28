<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('knet_fixed_charges')->default(0);
            $table->double('knet_commission_charges')->default(0);
            $table->double('other_fixed_charges')->default(0);
            $table->double('other_commission_charges')->default(0);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('fee_charges');
    }
}
