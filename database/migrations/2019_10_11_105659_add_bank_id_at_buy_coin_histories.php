<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankIdAtBuyCoinHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_coin_histories', function (Blueprint $table) {
            $table->integer('bank_id')->after('bank_sleep')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_coin_histories', function (Blueprint $table) {
            $table->dropColumn('bank_id');
        });
    }
}
