<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->mediumText('block')->nullable();
          $table->mediumText('street')->nullable();
          $table->mediumText('avenue')->nullable();
          $table->mediumText('building')->nullable();
          $table->mediumText('floor')->nullable();
          $table->mediumText('house')->nullable();
          $table->mediumText('office')->nullable();
          $table->mediumText('appartment_number')->nullable();
          $table->mediumText('additional_direction')->nullable();
          $table->string('country_code')->nullable();
          $table->string('mobile_number')->nullable();
          $table->string('landline_number')->nullable();
          $table->mediumText('address')->nullable();
          $table->string('location_latitude')->nullable();
          $table->string('location_longitude')->nullable();
          $table->tinyInteger('is_primary')->default('1')->comment('1=yes,0=no');
          $table->tinyInteger('is_active')->default('1')->comment('1=active,0=inactive');
          $table->tinyInteger('address_type')->default('0')->comment('0=home,1=office,2=appartment');
          $table->tinyInteger('is_delete')->default('0')->comment('1=deleted,0=not deleted');
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
        Schema::dropIfExists('user_address');
    }
}
