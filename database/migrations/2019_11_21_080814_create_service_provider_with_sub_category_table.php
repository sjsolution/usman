<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceProviderWithSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_provider_with_sub_category', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->unsignedBigInteger('sub_category_id');
          $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('service_provider_with_sub_category');
    }
}
