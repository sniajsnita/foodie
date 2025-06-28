<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['topup', 'tarik']);
            $table->integer('amount');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('proof');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coin_transactions');
    }
}
