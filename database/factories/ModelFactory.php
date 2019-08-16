<?php

use Josh\Faker\Faker;

// User factory
$factory->define(App\Models\User::class, function (\Faker\Generator $faker) {
    return [
        'password' => '12345',
        'name' => Faker::firstname(),
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
    ];
});
// Category factory
$factory->define(App\Models\Category::class, function (\Faker\Generator $faker) {
    return [
        'title' => Faker::firstname(),
    ];
});
//Product factory
$factory->define(App\Models\Product::class, function (\Faker\Generator $faker) {
    $shortPersionLorem = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.';
    $persianDescription = '';
    return [
        'title' => Faker::firstname(),
        'short_description' => (rand(1, 10) % 2) == 0 ? $shortPersionLorem : null,
        'price' => $faker->randomFloat(3, 0, 1000000),
        'quantity' => $faker->randomNumber(2),
        'category_id' => $faker->randomElement(\App\Models\Category::pluck('id')),
    ];
});
//ProductPicture factory
$factory->define(App\Models\ProductPicture::class, function (\Faker\Generator $faker) {
    $productPictures = [
        '/img/product-1.jpg',
        '/img/product-2.jpg',
        '/img/product-3.jpg',
        '/img/product-4.jpg',
        '/img/product-5.jpg',
    ];
    return [
        'product_id' => $faker->randomElement(\App\Models\Product::pluck('id')),
        'path' => $faker->randomElement($productPictures),
    ];
});
