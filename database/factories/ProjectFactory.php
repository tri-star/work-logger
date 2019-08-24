<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use WorkLogger\Domain\Project\Project;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'project_name' => $faker->projectName(),
        'description'  => $faker->numerify('説明文#####'),
    ];
});
