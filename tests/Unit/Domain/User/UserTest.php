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


    public function testGetTotalCompletedTaskCount_完了済のタスクのみカウントすること()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->save($user);

        factory(Task::class, 2)->states(['done'])->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
        ]);
        factory(Task::class, 3)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
        ]);

        $count = $user->getTotalCompletedTaskCount();
        $this->assertEquals(2, $count);
    }

    public function testGetTotalCompletedTaskCount_同一プロジェクトの他ユーザーのタスクもカウントすること()
    {
        $users = factory(User::class, 2)->create();
        $project = factory(Project::class)->create();
        $project->users()->saveMany($users);

        factory(Task::class, 2)->states(['done'])->create([
            'user_id'    => $users[0]->id,
            'project_id' => $project->id,
        ]);
        factory(Task::class, 3)->states(['done'])->create([
            'user_id'    => $users[1]->id,
            'project_id' => $project->id,
        ]);

        $count = $users[0]->getTotalCompletedTaskCount();
        $this->assertEquals(5, $count);
    }


    public function testGetTotalCompletedTaskCount_自分が参加していないプロジェクトはカウントしないこと()
    {
        $users = factory(User::class, 2)->create();
        $projects = factory(Project::class, 2)->create();
        $projects[0]->users()->save($users[0]);
        $projects[1]->users()->save($users[1]);

        factory(Task::class, 2)->states(['done'])->create([
            'user_id'    => $users[0]->id,
            'project_id' => $projects[0]->id,
        ]);
        factory(Task::class, 3)->states(['done'])->create([
            'user_id'    => $users[1]->id,
            'project_id' => $projects[1]->id,
        ]);

        $count = $users[0]->getTotalCompletedTaskCount();
        $this->assertEquals(2, $count);
    }
}
