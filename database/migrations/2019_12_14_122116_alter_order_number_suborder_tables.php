<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderNumberSuborderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suborders', function (Blueprint $table) {
            DB::statement('ALTER TABLE suborders MODIFY order_number_id  LONGTEXT;');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suborders', function (Blueprint $table) {
            //
        });
    }
}
