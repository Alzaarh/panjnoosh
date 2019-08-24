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
        'user_addresses',
    ];
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        factory(App\Models\Category::class, 20)->create();
        factory(App\Models\User::class, 200)->create()
            ->each(function ($user) {
                $user->addresses()->save(factory(App\Models\UserAddress::class)->make([
                    'user_id' => $user->id,
                ]));
            });
        factory(App\Models\Product::class, 50)->create()
            ->each(function ($product) {
                if (rand(1, 100) > 80) {
                    $product->discounts()->save(factory(App\Models\Discount::class)->make([
                        'product_id' => $product->id,
                    ]));
                }
            });
        factory(App\Models\ProductPicture::class, 100)->create();
        factory(App\Models\UserAddress::class);
    }
}
