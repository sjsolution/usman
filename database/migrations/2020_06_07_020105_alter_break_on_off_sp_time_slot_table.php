<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBreakOnOffSpTimeSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sp_time_slot', function (Blueprint $table) {
            $table->boolean('break_time_status')->comment('0 : Off 1: On')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sp_time_slot', function (Blueprint $table) {
            //
        });
    }
}
