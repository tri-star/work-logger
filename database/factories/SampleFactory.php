<?php

use Faker\Generator as Faker;

$factory->define(\WorkLogger\Sample::class, function (Faker $faker) {
    return [
        'value' => $faker->name,
    ];
});
