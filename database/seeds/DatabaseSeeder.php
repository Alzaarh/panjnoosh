<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private $tables = [
        'users',
        'categories',
        'products',
        'product_pictures',
        'admin_info',
        'discounts',
    ];
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        factory(App\Models\Category::class, 20)->create();
        factory(App\Models\User::class, 200)->create();
        factory(App\Models\Product::class, 50)->create()
            ->each(function ($product) {
                if (rand(1, 100) > 80) {
                    $product->discounts()->save(factory(App\Models\Discount::class)->make([
                        'product_id' => $product->id,
                    ]));
                }
            });
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
