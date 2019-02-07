<?php

namespace Tests\Unit\Domain\Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\User\User;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testユーザーを取得できること()
    {
        $task = factory(\WorkLogger\Domain\Task\Task::class)->create();
        $user = $task->user;
        $this->assertTrue($user instanceof User);
    }
}
