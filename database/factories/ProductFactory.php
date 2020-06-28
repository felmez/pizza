<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(15),
        'description' => $faker->text(30),
        'price' => '22',
        'quantity' => '0',
    ];
});
