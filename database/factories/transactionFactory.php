<?php

use Faker\Generator as Faker;


$factory->define(\App\Transaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat(2,100, 1000000),
        'date'  => $faker->dateTime()
    ];
});
