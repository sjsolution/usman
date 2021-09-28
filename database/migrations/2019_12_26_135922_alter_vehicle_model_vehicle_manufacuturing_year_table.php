<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleModelVehicleManufacuturingYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_manufacuturing_year', function (Blueprint $table) {
            
            $table->dropForeign('vehicle_manufacuturing_year_vehicle_type_id_foreign');
            $table->dropColumn('vehicle_type_id');
            $table->unsignedBigInteger('vehicle_model_id')->nullable();
            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_model')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_manufacuturing_year', function (Blueprint $table) {
            //
        });
    }
}
