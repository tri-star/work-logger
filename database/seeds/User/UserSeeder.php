<?php

use Illuminate\Database\Seeder;
use WorkLogger\Domain\User\User;

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

        User::truncate();
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
