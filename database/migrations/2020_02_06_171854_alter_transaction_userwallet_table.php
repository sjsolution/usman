<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionUserwalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userwallet', function (Blueprint $table) {
            \DB::statement("ALTER TABLE userwallet CHANGE transaction_type transaction_type ENUM('0','1','2','3') DEFAULT null COMMENT '0 : Add Money, 1 : Credit, 2: Debit, 3 : Refunded'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userwallet', function (Blueprint $table) {
            //
        });
    }
}
