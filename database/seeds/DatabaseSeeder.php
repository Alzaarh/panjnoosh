<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    private $tables = [
        'users',
        'categories',
        'products',
        'product_pictures',
        'admin_info',
    ];
    public function run() {
        DB::statement('set foreign_key_checks = 0');
        foreach($this->tables as $table) {
            DB::table($table)->truncate();
        }
        factory(App\Models\Category::class, 20)->create();
        factory(App\Models\User::class, 200)->create();
        factory(App\Models\Product::class, 50)->create();
        factory(App\Models\ProductPicture::class, 100)->create();
        //admin_info table seeder
        DB::table('admin_info')->insert([
            'name' => 'محمد قاسمی',
            'email' => 'mgh@gmail.com',
            'phone' => '5134561245',
            'social_networks' => json_encode('{"telegram": "panjnoosh.tel", "instagram": "panjnoosh.inst"}'), 
        ]);
    }
}
