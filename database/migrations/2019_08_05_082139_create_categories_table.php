<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('parent_id')->nullable();
          $table->string('name_en')->nullable();
          $table->string('name_ar')->nullable();
          $table->string('image')->nullable();
          $table->integer('type')->default('1')->comment('1=Normal, 2=Insurance , 3=Emergency');
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
        Schema::dropIfExists('categories');
    }
}
