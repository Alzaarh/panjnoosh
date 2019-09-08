<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transactions_code');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('order_id')->unsigned();
            $table->boolean('is_verified');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('se null');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
