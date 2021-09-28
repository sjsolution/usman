<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('service')->onDelete('cascade');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->decimal('amount',10,3)->default('0.000');
            $table->string('image')->nullable();
            $table->string('time_duration')->nullable();
            $table->string('time_type')->comment('mins and hours based multiplication')->nullable();
            $table->enum('is_active',[0,1])->default(1)->comment('1=active and 0=inactive');
            $table->enum('is_delete',[0,1])->default(0)->comment('1=deleted');
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
        Schema::dropIfExists('service_addons');
    }
}
