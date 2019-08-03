<?php

use Josh\Faker\Faker;

$factory->define(App\Models\User::class, function (\Faker\Generator $faker) {

    return [
        'password' => '12345',

        'name' => Faker::firstname(),

        'username' => $faker->unique()->userName,
    ];
});
