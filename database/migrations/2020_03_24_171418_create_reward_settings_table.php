<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('from_date')->nullable();	
            $table->date('to_date')->nullable();
            $table->double('reward_amount',8,3)->default(0);
            $table->boolean('status')->comment('0: In-active 1: Active')->default(0);
            $table->boolean('is_active')->default(1);		
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
        Schema::dropIfExists('reward_settings');
    }
}
