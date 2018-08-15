<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameBuffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_buffers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name',150);
            $table->string('game_date',150);
            $table->string('game_time',150)->default('None');
            $table->string('game_img_path',150)->default('None');
            $table->string('game_img_name',150)->default('None');
            $table->text('game_desc');
            $table->text('pubs');
            $table->integer('id_project');
            $table->string('project_name',150);
            $table->string('project_color',150);
            $table->integer('id_season');
            $table->string('season_name',150);
            $table->string('region',150);
            $table->boolean('confirmed')->default(0);
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
        Schema::dropIfExists('game_buffers');
    }
}
