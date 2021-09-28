<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_type')->onDelete('cascade');
            $table->integer('type')->default('0')->comment('0 for normal and 1= insurance');
            $table->integer('service_type')->default('0')->comment('1 = full party and 2=third party ');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->longText('special_note_en')->nullable();
            $table->longText('special_note_ar')->nullable();
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
        Schema::dropIfExists('service');
    }
}
