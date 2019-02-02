<?php
namespace WorkLogger\Providers;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class DatabaseServiceProvider extends \Illuminate\Database\DatabaseServiceProvider
{
    public function boot()
    {
        parent::boot();
        
        //Fakers配下を自動でロード出来るようにする
        $faker = $this->app[FakerGenerator::class];
        $faker->addProvider(new \WorkLogger\Fakers\CategoryFaker($faker));
    }
}
