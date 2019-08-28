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

        factory(App\Models\Purchase::class, 50)->create();
        //product_purchase table
        for ($i = 1; $i <= 100; $i++) {
            DB::table('product_purchase')->insert([
                'purchase_id' => rand(1, 50),
                'product_id' => App\Models\Product::inRandomOrder()->first()->id,
            ]);
        }

        $json = json_decode(file_get_contents(storage_path() . '/app/Province.json'), true);

        foreach ($json as $state) {
            DB::table('states')->insert(['title' => $state['name']]);

            foreach ($state['Cities'] as $city) {
                DB::table('cities')->insert(['title' => $city['name']]);
            }
        }
    }
}
