<?php

$factory->define(App\Models\User::class,
    function (Faker\Generator $faker, $names) {
        return [
            'password' => '12345',

            'name' => $faker->randomElement($names),

            'username' => $faker->unique()->word,

            'email' => $faker->unique()->safeEmail,

            'phone' => '0915' . $faker->randomNumber(7),

            'role' => rand(1, 100) > 90 ? 'admin' : 'user',
        ];
    });

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    $categoryNames = [
        'دمنوش',
        'چای',
        'قهوه',
        'شیر',
        'آب',
    ];

    $categoryDetails = [
        'زندگی مانند یک آینه است
        هنگامی که در آن لبخند بزنیم بهترین نتایج را به دست خواهیم آورد',

        'بیست سال بعد بیشتر به خاطر کارهای نکرده ناراحت می شوید
        تا کارهایی که انجام داده اید',

        'هرگز خودتان را با هیچ کس دیگر در این جهان مقایسه نکنید
        اگر این کار را بکنید به خودتان توهین کرده اید',
    ];

    return [
        'title' => $faker->randomElement($categoryNames),

        'details' => $faker->randomElement($categoryDetails),
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    $logos = [
        'product-1.jpg',
        'product-2.jpg',
        'product-3.jpg',
    ];

    $productNames = [
        'چای گرم',
        'چای سرد',
        'قهوه سرد',
        'قهوه گرم',
        'شیر توت فرنگی‌',
    ];

    return [
        'title' => $faker->randomElement($productNames),

        'price' => $faker->randomNumber(4),

        'quantity' => $faker->randomNumber(2),

        'logo' => 'imgs/' . $faker->randomElement($logos),

        'off' => rand(1, 100) > 80 ? rand(1, 100) : 0,

        'category_id' => $faker->randomElement(
            App\Models\Category::pluck('id')),

        'active' => rand(1, 100) > 90 ? false : true,
    ];
});

$factory->define(App\Models\ProductPicture::class,
    function (Faker\Generator $faker) {
        $productPictures = [
            '/imgs/pp-1.jpg',
            '/imgs/pp-2.jpg',
            '/imgs/pp-3.jpg',
            '/imgs/pp-4.jpg',
            '/imgs/pp-5.jpg',
        ];

        return [
            'product_id' => $faker->randomElement(
                \App\Models\Product::pluck('id')),

            'path' => $faker->randomElement($productPictures),
        ];
    });

$factory->define(App\Models\UserAddress::class,
    function (Faker\Generator $faker) {
        $cities = [
            'تهران',
            'کرمان',
            'رفسنجان',
        ];

        $states = [
            'کرمان',
            'تهران',
        ];

        $names = [
            'ناهید',
            'علی',
            'نازنین',
        ];

        return [
            'address' => 'آدرس به این صورت',
            'state' => $faker->randomElement($states),
            'city' => $faker->randomElement($cities),
            'zipcode' => '1234567890',
            'phone' => '0915' . $faker->randomNumber(7),
            'user_id' => $faker->randomElement(App\Models\User::pluck('id')),
            'receiver_name' => $faker->randomElement($names),
        ];
    });

$factory->define(App\Models\Order::class, function (Faker\Generator $faker) {
    $cities = [
        'تهران',
        'کرمان',
        'رفسنجان',
    ];

    $states = [
        'کرمان',
        'تهران',
    ];

    $names = [
        'ناهید',
        'علی',
        'نازنین',
    ];

    return [
        'total_price' => $faker->randomNumber(4),
        'user_id' => $faker->randomElement(App\Models\User::pluck('id')),
        'user_city' => $faker->randomElement($cities),
        'user_state' => $faker->randomElement($states),
        'user_address' => 'آدرس به این صورت',
        'user_zipcode' => '1234567890',
        'user_phone' => '0915' . $faker->randomNumber(7),
        'user_receiver_name' => $faker->randomElement($names),
        'status' => $faker->randomElement(App\Models\Order::STATUS),
    ];
});

$factory->define(App\Models\State::class, function (Faker\Generator $faker) {
    $states = [
        'کرمان',
        'تهران',
        'خراسان رضوی',
        'مازندران',
        'همدان',
        'یزد',
    ];

    return [
        'title' => $faker->randomElement($states),
    ];
});

$factory->define(App\Models\City::class, function (Faker\Generator $faker) {
    $cities = [
        'مشهد',
        'تهران',
        'اصفهان',
        'شیراز',
        'کرمان',
        'یزد',
        'همدان',
        'بندرعباس',
        'رفسنجان',
        'رشت',
        'ساری',
        'بجنورد',
    ];

    return [
        'title' => $faker->randomElement($cities),
        'state_id' => $faker->randomElement(App\Models\State::all()),
    ];
});
