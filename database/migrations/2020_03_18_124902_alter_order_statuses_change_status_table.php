<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderStatusesChangeStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            \DB::statement("ALTER TABLE order_statuses CHANGE status status ENUM('0','1','2','3','4','5','6') DEFAULT null COMMENT '0 : Pending, 1 : Accepted, 2: On the way, 3 : In Progress, 4 : Completed 5: Rejected, 6: Cancelled'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            //
        });
    }
}
