<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');

            $table->unsignedBigInteger('order_id');

            $table->string('product_title');

            $table->integer('product_price')->unsigned();

            $table->smallInteger('quantity')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
