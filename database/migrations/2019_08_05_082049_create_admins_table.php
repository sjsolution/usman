<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('name')->nullable();
          $table->string('email');
          $table->string('password');
          $table->string('profile_pic')->nullable();
          $table->string('mobile_number')->nullable();
          $table->string('address')->nullable();
          $table->integer('is_active')->default(1)->comments('0=inactive,1=active');
          $table->timestamp('email_verified_at')->nullable();
          $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
