<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('code')->nullable();
            $table->enum('type',['0','1','2'])->comment('0: None, 1: Percentage, 2: Amount')->default(0);
            $table->double('coupon_value',10,3)->default(0);
            $table->date('valid_till')->nullable();
            $table->double('coupon_min_value',10,3)->default(0);
            $table->double('coupon_max_value',10,3)->default(0);
            $table->bigInteger('user_limit')->default(0);
            $table->bigInteger('coupon_per_user_limit')->default(0);
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
