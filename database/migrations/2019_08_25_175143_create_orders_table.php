<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('total_price')->unsigned();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('user_city');

            $table->string('user_state');

            $table->string('user_address');

            $table->string('user_zipcode');

            $table->string('user_phone');

            $table->string('user_receiver_name');

            $table->enum('status', ['0', '1', '2', '3']);

            $table->boolean('is_complete')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('transaction_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
