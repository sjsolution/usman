<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookedTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booked_technicians', function (Blueprint $table) {
            \DB::statement("ALTER TABLE booked_technicians CHANGE status status ENUM('0','1','2','3','4') DEFAULT null COMMENT '0: None 1: Booked 2: Service Completed 3: Cancelled 4:Rejected'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booked_technicians', function (Blueprint $table) {
            //
        });
    }
}
