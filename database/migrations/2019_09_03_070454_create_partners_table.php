<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('partnername_en')->nullable();
            $table->string('partnername_ar')->nullable();
            $table->text('partner_image')->nullable();
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
        Schema::dropIfExists('partners');
    }
}
