<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayemntTypeExtendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            \DB::statement("ALTER TABLE orders CHANGE payment_type payment_type ENUM('1','2','3','4','5') DEFAULT null COMMENT '1: Wallet 2: Knet 3: Knet & Wallet 4:Credit Card  5: Credit Card & Wallet'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
