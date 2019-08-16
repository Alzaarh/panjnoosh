<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    private $tables = [
        'users',
        'categories',
        'products',
        'product_pictures',
    ];
    public function run() {
        DB::statement('set foreign_key_checks = 0');
        foreach($this->tables as $table) {
            DB::table($table)->truncate();
        }
        factory(App\Models\Category::class, 50)->create();
        factory(App\Models\User::class, 1000)->create();
        factory(App\Models\Product::class, 100)->create();
        factory(App\Models\ProductPicture::class, 300)->create();
    }
}
