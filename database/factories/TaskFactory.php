<?php

use Faker\Generator as Faker;

$factory->define(WorkLogger\Domain\Task\Task::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\WorkLogger\Domain\User\User::class)->create()->id;
        },
        'issue_no' => $faker->numerify('Issue-####'),
        'title' => $faker->title,
        'description' => $faker->lexify('説明文 ???????'),
        'estimate_minutes' => $faker->numberBetween(30, 240),
        'actual_minutes' => 0,
    ];
});
