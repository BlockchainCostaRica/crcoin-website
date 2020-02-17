<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliation_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('child_id')->unsigned();
            $table->foreign('child_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('amount', 19, 8)->default(0);
            $table->decimal('system_fees', 19, 8)->default(0);
            $table->bigInteger('transaction_id')->nullable();
            $table->integer('level');
            $table->integer('order_type')->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('affiliation_histories');
    }
}
