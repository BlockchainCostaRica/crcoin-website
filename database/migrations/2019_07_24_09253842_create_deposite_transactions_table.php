<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposite_transactions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('address');
            $table->bigInteger('fees')->nullable();
            $table->bigInteger('sender_wallet_id')->nullable();
            $table->bigInteger('receiver_wallet_id')->unsigned();
            $table->string('address_type');
            $table->string('type')->nullable();
            $table->decimal('amount',19,8);
            $table->string('transaction_id');
            $table->string('status')->default(0);
            $table->integer('confirmations')->default(0);
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
        Schema::dropIfExists('deposite_transactions');
    }
}
