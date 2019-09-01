<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title');

            $table->string('short_description')->nullable();

            $table->text('description')->nullable();

            $table->string('logo')->nullable();

            $table->float('price', 10, 3)->nullable();

            $table->unsignedInteger('quantity');

            $table->boolean('active')->default(true);

            $table->unsignedTinyInteger('off')->default(0);

            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
