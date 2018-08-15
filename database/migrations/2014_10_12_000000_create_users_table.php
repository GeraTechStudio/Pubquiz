<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region',150);
            $table->string('login',60)->unique();
            $table->string('name',150);
            $table->string('email',150)->unique();
            $table->string('tel',20)->unique();
            $table->string('password');
            $table->boolean('verified')->default(false);
            $table->string('token')->nullable();
            $table->string('is_admin',60)->default('user');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
