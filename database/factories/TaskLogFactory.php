<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use WorkLogger\Domain\Task\Task;

$factory->define(WorkLogger\Domain\Task\TaskLog::class, function (Faker $faker) {
    return [
        'task_id' => function () {
            return factory(\WorkLogger\Domain\Task\Task::class)->create()->id;
        },
        'hours'  => $faker->randomFloat(1, 0, 20),
        'memo'   => $faker->realText(200),
        'status' => array_rand([
            Task::STATE_NONE,
            Task::STATE_IN_PROGRESS,
            Task::STATE_DONE,
            Task::STATE_PAUSE,
            Task::STATE_INVALID,
        ]),
    ];
});
