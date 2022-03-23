<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id('balance_id');
            $table->bigInteger('bTotal_harvest');
            $table->bigInteger('bTotal_amount');
            $table->bigInteger('site_id');
            $table->bigInteger('farmer_id')->unsigned();
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');
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
        Schema::dropIfExists('balances');
    }
}
