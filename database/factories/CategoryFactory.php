<?php

$factory->define(App\Category::class, function (Faker\Generator $faker) {

    $rand = rand(1, 100);
    
    return [
        'title' => 'دسته‌بندی-' . $rand,
    ];
});
