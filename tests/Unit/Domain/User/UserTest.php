<?php

namespace Tests\Unit\Domain\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testタスクを追加できること()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $user->tasks()->save($task);

        $firstTask = $user->tasks->first();
        $this->assertEquals($task->id, $firstTask->id);
    }
}
