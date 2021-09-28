<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            \DB::statement("ALTER TABLE orders CHANGE status status ENUM('0','1','2','3','4','5','6','7') DEFAULT null COMMENT '0: Pending 1: Start 2: Completed 3: Failed 4:Cancelled 5: On the way 6:Accepted 7: Rejected'");
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
