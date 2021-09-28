<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopratedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toprateds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->bigInteger('is_position')->default(0);
            $table->enum('is_active',[0,1])->default(0)->comments('1=active,0=inactive');
            $table->enum('is_delete',[0,1])->default(0)->comment('1=deleted,0=not deleted');
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
        Schema::dropIfExists('toprateds');
    }
}
