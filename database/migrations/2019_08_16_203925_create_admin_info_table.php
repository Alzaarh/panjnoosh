<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminInfoTable extends Migration {
    public function up() {
        Schema::create('admin_info', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->json('social_networks')->nullable();
            $table->text('address')->nullable();
        });
    }
    public function down() {
        Schema::dropIfExists('admin_info');
    }
}
