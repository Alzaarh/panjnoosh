<?php

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    $rand = rand(1, 1000);
    return [
        'title' => 'دسته‌بندی-' . $rand,
    ];
});
