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

        'user_addresses',

        'orders',
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

        factory(App\Models\Product::class, 50)->create();

        factory(App\Models\ProductPicture::class, 100)->create();

        factory(App\Models\Order::class, 20)->create();

        foreach (App\Models\Order::all() as $order) {
            for ($i = 0; $i < 2; $i++) {
                $product = App\Models\Product::inRandomOrder()->first();

                DB::table('order_product')->insert([
                    'product_id' => $product->id,

                    'order_id' => $order->id,

                    'product_title' => $product->title,

                    'product_price' => $product->price,

                    'quantity' => rand(1, 10),
                ]);
            }

        }

        factory(App\Models\State::class, 30)->create();

        factory(App\Models\City::class, 200)->create();
    }
}
