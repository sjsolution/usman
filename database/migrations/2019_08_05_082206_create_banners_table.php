<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('title_en')->nullable();
          $table->string('title_ar')->nullable();
          $table->text('banner_image')->nullable();
          $table->text('description_en')->nullable();
          $table->text('description_ar')->nullable();
          $table->enum('type',[1,2])->default(1)->comments('1=splash,2=carousal');
          $table->enum('is_active',[0,1])->default(0)->comments('0=active,1=inactive');
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
        Schema::dropIfExists('banners');
    }
}
