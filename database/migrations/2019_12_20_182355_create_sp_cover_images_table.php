<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpCoverImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_cover_images', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id')->nullable();
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->string('cover_image')->nullable();
          $table->enum('is_active',['0','1'])->default('0')->comment('0 for active and 1 for inactive');
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
        Schema::dropIfExists('sp_cover_images');
    }
}
