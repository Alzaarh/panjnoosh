<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration {
    public function up() {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title');

            $table->unsignedBigInteger('state_id');

            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('cities');
    }
}
