<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_buffers', function (Blueprint $table) {
            $table->string('game_rounds',150)->default(9);
            $table->string('game_order',150)->default(1);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_buffers', function (Blueprint $table) {
            //
        });
    }
}
