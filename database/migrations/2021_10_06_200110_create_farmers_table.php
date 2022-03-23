<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id('farmer_id');
            $table->string('farmer_firstname');
            $table->string('farmer_lastname');
            $table->string('farmer_gender');
            $table->string('farmer_phone');
            $table->bigInteger('site_id')->unsigned();
            $table->foreign('site_id')->references('site_id')->on('sites')->onDelete('cascade');
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
        Schema::dropIfExists('farmers');
    }
}
