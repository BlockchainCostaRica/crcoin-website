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
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 180)->unique();
            $table->string('type');
            $table->string('status');
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_verified')->nullable();
            $table->string('country')->nullable();
            $table->string('photo')->nullable();
            $table->enum('g2f_enabled',[0,1]);
            $table->string('google2fa_secret')->nullable();
            $table->tinyInteger('is_verified')->nullable();
            $table->string('password');
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
