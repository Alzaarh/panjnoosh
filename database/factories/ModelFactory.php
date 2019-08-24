<?php

use Josh\Faker\Faker;

$factory->define(App\Models\User::class, function (\Faker\Generator $faker) {
    return [
        'password' => '12345',
        'name' => Faker::firstname(),
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'profile_picture' => '/imgs/profile.png',
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
    $shortPersionLorem = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.';
    $logos = ['product-1.jpg', 'product-2.jpg', 'product-3.jpg'];
    return [
        'title' => Faker::firstname(),
        'short_description' => (rand(1, 10) % 2) == 0 ? $shortPersionLorem : null,
        'price' => $faker->randomFloat(3, 0, 1000000),
        'quantity' => $faker->randomNumber(2),
        'main_logo' => 'imgs/' . $faker->randomElement($logos),
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
$factory->define(App\Models\Discount::class, function (\Faker\Generator $faker) {
    return [
        'product_id' => $faker->unique()->randomElement(App\Models\Product::pluck('id')),
        'off' => $faker->numberBetween(1, 100),
    ];
});
$factory->define(App\Models\UserAddress::class, function (\Faker\Generator $faker) {
    $states = ['تهران', 'خراسان رضوی', 'همدان', 'کرمان', 'مازندران'];
    $cities = ['تهران', 'مشهد', 'همدان', 'ساری', 'ری'];
    return [
        'address' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک ',
        'state' => rand(1, 10) > 50 ? $faker->randomElement($states) : null,
        'city' => rand(1, 10) > 5 ? $faker->randomElement($cities) : null,
        'zipcode' => $faker->postcode,
        'phone' => $faker->e164PhoneNumber,
        'user_id' => 1,
    ];
});
