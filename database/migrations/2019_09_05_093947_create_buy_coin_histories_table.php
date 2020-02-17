<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyCoinHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_coin_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('address');
            $table->tinyInteger('type');
            $table->bigInteger('user_id');
            $table->decimal('coin',19,8);
            $table->decimal('btc',19,8)->nullable();
            $table->decimal('doller',19,8)->nullable();
            $table->string('transaction_id')->nullable();
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->tinyInteger('admin_confirmation')->default(STATUS_PENDING);
            $table->boolean('confirmations')->nullable();
            $table->string('bank_sleep')->nullable();
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
        Schema::dropIfExists('buy_coin_histories');
    }
}
