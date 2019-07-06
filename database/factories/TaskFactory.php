<?php

use Faker\Generator as Faker;
use WorkLogger\Domain\Task\Task;

$factory->define(WorkLogger\Domain\Task\Task::class, function (Faker $faker) {
    return [
        'project_id' => function () {
            return factory(\WorkLogger\Domain\Project\Project::class)->create()->id;
        },
        'user_id' => function () {
            return factory(\WorkLogger\Domain\User\User::class)->create()->id;
        },
        'issue_no' => $faker->numerify('Issue-####'),
        'title' => $faker->title,
        'description' => $faker->lexify('説明文 ???????'),
        'estimate_minutes' => $faker->numberBetween(30, 240),
        'actual_minutes' => 0,
        'status' => array_rand([
            Task::STATE_NONE,
            Task::STATE_IN_PROGRESS,
            Task::STATE_DONE,
            Task::STATE_PAUSE,
            Task::STATE_INVALID,
        ]),
    ];
});
