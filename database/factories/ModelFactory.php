<?php

use Josh\Faker\Faker;

$faker = \Faker\Factory::create('fa_IR');

$factory->define(App\Models\User::class, function (\Faker\Generator $faker) {
    return [
        'password' => '12345',

        'name' => Faker::firstname(),

        'username' => $faker->unique()->userName,

        'email' => $faker->unique()->safeEmail,

        'phone' => $faker->e164PhoneNumber,
    ];
});
$factory->define(App\Models\Category::class, function (\Faker\Generator $faker) {
    $categoryNames = [];
    for ($i = 1; $i <= 100; $i++) {
        array_push($categoryNames, ' دسته‌بندی' . $i);
    }
    return [
        'title' => $faker->randomElement($categoryNames),
        'details' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.',
    ];
});
$factory->define(App\Models\Product::class, function (\Faker\Generator $faker) {
    $shortPersionLorem = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان';

    $logos = ['product-1.jpg', 'product-2.jpg', 'product-3.jpg'];

    return [
        'title' => Faker::firstname(),

        'short_description' => (rand(1, 10) % 2) == 0 ? $shortPersionLorem : null,

        'price' => $faker->randomFloat(3, 0, 1000000),

        'quantity' => $faker->randomNumber(2),

        'logo' => 'imgs/' . $faker->randomElement($logos),

        'off' => rand(1, 100) > 80 ? rand(1, 100) : 0,

        'category_id' => $faker->randomElement(\App\Models\Category::pluck('id')),

        'active' => rand(1, 100) > 90 ? false : true,
    ];
});
$factory->define(App\Models\ProductPicture::class, function (\Faker\Generator $faker) {
    $productPictures = [
        '/imgs/pp-1.jpg',
        '/imgs/pp-2.jpg',
        '/imgs/pp-3.jpg',
        '/imgs/pp-4.jpg',
        '/imgs/pp.jpg',
    ];
    return [
        'product_id' => $faker->randomElement(\App\Models\Product::pluck('id')),
        'path' => $faker->randomElement($productPictures),
    ];
});

$factory->define(App\Models\UserAddress::class, function (\Faker\Generator $faker) {
    $states = [
        'تهران',
        'کرمان',
        'خراسان رضوی',
    ];

    $cities = [
        'مشهد',
        'تهران',
        'رفسنجان',
    ];

    return [
        'address' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک ',

        'state' => $faker->randomElement($states),

        'city' => $faker->randomElement($cities),

        'zipcode' => $faker->postcode,

        'phone' => $faker->e164PhoneNumber,

        'user_id' => 1,

        'receiver_name' => Faker::firstname(),
    ];
});

$factory->define(App\Models\Order::class, function ($faker) {
    return [
        'total_price' => $faker->randomNumber(4),

        'user_id' => $faker->randomElement(App\Models\User::pluck('id')),

        'user_city' => $faker->city,

        'user_state' => $faker->state,

        'user_address' => $faker->address,

        'user_zipcode' => $faker->postcode,

        'user_phone' => $faker->phoneNumber,

        'user_receiver_name' => $faker->name,

        'status' => $faker->randomElement(App\Models\Order::STATUS)
    ];
});

$factory->define(App\Models\State::class, function () {
    return [
        'title' => Faker::firstname(),
    ];
});

$factory->define(App\Models\City::class, function (\Faker\Generator $faker) {
    return [
        'title' => Faker::firstname(),

        'state_id' => $faker->randomElement(App\Models\State::all()),
    ];
});
