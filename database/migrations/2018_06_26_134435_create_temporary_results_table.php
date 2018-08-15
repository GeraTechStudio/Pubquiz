<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporaryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->integer('location_id');
            $table->integer('round_size');
            $table->string('order',150);
            $table->text('game_data');
            $table->string('region',150);
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
        Schema::dropIfExists('temporary_results');
    }
}
