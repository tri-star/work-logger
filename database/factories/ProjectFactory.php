<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use WorkLogger\Domain\Project\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'project_name' => $faker->projectName(),
        'description' => $faker->realText(50),
    ];
});
