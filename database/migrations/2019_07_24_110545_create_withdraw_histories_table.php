<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wallet_id')->unsigned();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->decimal('amount',19,8)->unsigned();
            $table->tinyInteger('address_type');
            $table->string('address');
            $table->string('transaction_hash');
            $table->string('receiver_wallet_id')->nullable();
            $table->string('confirmations')->nullable();
            $table->decimal('fees',19,8)->unsigned();
            $table->tinyInteger('status')->default(0);
            $table->longText('message')->nullable();

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
        Schema::dropIfExists('withdraw_histories');
    }
}
