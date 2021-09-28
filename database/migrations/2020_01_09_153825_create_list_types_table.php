<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->boolean('status')->default(1);
            $table->bigInteger('rank')->nullable();
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
        Schema::dropIfExists('list_types');
    }
}
