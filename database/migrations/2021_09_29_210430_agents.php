<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Agents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('agents', function (Blueprint $table) {
            $table->id('agent_id');
            $table->string('agent_names');
            $table->string('agent_phone');
            $table->string('agent_gender');
            $table->bigInteger('site_id')->unsigned();
            $table->foreign('site_id')->references('site_id')->on('sites')->onDelete('cascade');
            $table->string('sector');
            $table->string('cell');
            $table->string('village');
            $table->string('agent_status');
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
        //
        Schema::dropIfExists('agents');
    }
}
