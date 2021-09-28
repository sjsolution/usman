<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsercmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usercms', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->string('title_en')->nullable();
          $table->string('title_ar')->nullable();
          $table->longText('description_en')->nullable();
          $table->longText('description_ar')->nullable();
          $table->enum('slug',['about','privacy_policy','term_condition'])->nullable();
          $table->tinyInteger('is_active')->default('1')->comment('1=active,0=inactive');
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
        Schema::dropIfExists('usercms');
    }
}
