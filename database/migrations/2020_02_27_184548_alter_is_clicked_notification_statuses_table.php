<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsClickedNotificationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_statuses', function (Blueprint $table) {
            $table->boolean('admin_is_clicked')->default(0);
            $table->boolean('sp_is_clicked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_statuses', function (Blueprint $table) {
            //
        });
    }
}
