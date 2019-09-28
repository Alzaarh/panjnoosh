<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private $cities = [
        'مشهد',
        'رفسنجان',
        'تهران',
        'کرمان',
        'اردبیل',
        'ری',
        'یزد',
        'همدان',
    ];

    private $states = [
        'خراسان رضوی',
        'خراسان شمالی',
        'یزد',
        'کرمان',
        'تهران',
        'گیلان',
        'همدان',
        'البرز',
    ];

    private $names = [
        'رضا',
        'محمد',
        'احمد',
        'نازنین',
        'علی',
        'امیر',
        'کسری',
        'سامان',
        'ناهید',
        'محمدرضا',
        'محمدامین',
    ];

    private $tables = [
        'users',
        'categories',
        'products',
        'product_pictures',
        'user_addresses',
        'orders',
        'order_product',
    ];

    public function run()
    {
        DB::statement('set foreign_key_checks = 0');

        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }

        factory(App\Models\Category::class, 5)->create();

        factory(App\Models\User::class, 30)->create();

        factory(App\Models\UserAddress::class, 15)->create();

        factory(App\Models\Product::class, 10)->create();

        factory(App\Models\ProductPicture::class, 20)->create();
        factory(App\Models\State::class, 5)->create();
        factory(App\Models\City::class, 10)->create();
        factory(App\Models\Blog::class, 10)->create();
    }
}
