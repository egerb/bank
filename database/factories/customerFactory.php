<?php

use Faker\Generator as Faker;
use App\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'cnp'  => $faker->uuid
    ];
});
