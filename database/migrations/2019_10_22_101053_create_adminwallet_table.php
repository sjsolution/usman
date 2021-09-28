<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminwalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminwallet', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('admin_id');
          $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
          $table->decimal('amount',8,3)->nullable()->comment('Actual Amount');
          $table->decimal('credit_amount',8,3)->nullable()->comment('Credit Amount');
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
        Schema::dropIfExists('adminwallet');
    }
}
