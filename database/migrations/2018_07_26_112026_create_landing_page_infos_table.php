<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandingPageInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo',256);
            $table->string('background_image',256);
            $table->string('title',256);
            $table->text('description');
            $table->text('button_link');
            $table->text('ios_link');
            $table->text('android_link');
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
        Schema::dropIfExists('landing_page_infos');
    }
}
