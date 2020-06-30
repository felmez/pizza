<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'product_id' => rand(1, 9),
        'product_quantity' => rand(1, 9),
        'total_price' => rand(10, 50),
        'name' => $faker->text(5),
        'phone' => $faker->phoneNumber(11),
        'address' => $faker->text(16),
    ];
});
