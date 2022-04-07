<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('userName', 30)->unique()->nullable();
            $table->string('name', 30);
            $table->string('lastName', 30)->nullable();
            $table->string('profileImageUrl')->nullable();
            $table->string('bio', 30)->nullable();
            $table->string('email')->unique();
            $table->enum('gender', ['male', 'female', 'not specified'])->nullable();
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
