<?php

use Illuminate\Database\Seeder;
use WorkLogger\Domain\User\User;
use WorkLogger\Domain\Task\Task;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'     => 'demo',
                'email'    => 'demo@example.com',
                'password' => bcrypt('testtest'),
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Task::truncate();
        User::truncate();
        foreach ($users as $user) {
            User::create($user);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
