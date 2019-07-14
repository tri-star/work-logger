<?php

namespace Tests\Unit\Domain\Task;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();
    }

    public function testユーザーを取得できること()
    {
        $task = factory(\WorkLogger\Domain\Task\Task::class)->create();
        $user = $task->user;
        $this->assertTrue($user instanceof User);
    }


    public function test_scopeScheduledTasks_取得対象が正しいこと()
    {
        foreach ($this->getDataForScopeScheduledTasks() as $title => $fixture) {
            $project = $fixture['project'];
            $user = $fixture['user'];
            $now = $fixture['now'];
            $expectedCount = $fixture['expectedCount'];

            $count = Task::scheduledTasks($project->id, $user->id, $now)->count();
            $this->assertEquals($expectedCount, $count, $title);
        }
    }


    public function getDataForScopeScheduledTasks()
    {
        $validScheduleTaskGenerator = function (Project $project, User $user, Carbon $now, int $count) {
            $tasks = factory(Task::class, 4)->create([
                'project_id' => $project->id,
                'user_id'    => $user->id,
                'status'     => Task::STATE_NONE,
                'start_date' => $now->format('Y-m-d H:i:s'),
                'end_date'   => $now,
            ], $count);

            return $tasks;
        };
        

        //自分のプロジェクトで自分が担当しているタスクで、開始日を過ぎて未着手なものはカウントすること
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $validScheduleTaskGenerator($project, $user, $now, 4);

        yield
            '自分が担当_開始日を過ぎて未着手なものはカウントする' => [
                'user'          => $user,
                'project'       => $project,
                'now'           => $now,
                'expectedCount' => 4,
        ];

        //自分のプロジェクトで自分が担当しているタスクで、開始日を過ぎて着手中なものはカウントすること
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $tasks = $validScheduleTaskGenerator($project, $user, $now, 4);
        $tasks->each(function ($task) {
            $task->status = Task::STATE_IN_PROGRESS;
            $task->save();
        });

        yield
        '自分が担当_開始日を過ぎて着手中状態のものはカウントする' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 4,
        ];


        //他ユーザーのタスクをカウントしないこと
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $validScheduleTaskGenerator($project, $anotherUser, $now, 1);

        yield
        '他ユーザーのタスクをカウントしないこと' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 0,
        ];

        //別プロジェクトのタスクをカウントしないこと
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $anotherProject = factory(Project::class)->create();
        $validScheduleTaskGenerator($anotherProject, $user, $now, 1);

        yield
        '他プロジェクトのタスクをカウントしないこと' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 0,
        ];

        //開始日前のタスクをカウントしないこと
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $tasks = $validScheduleTaskGenerator($project, $user, $now, 1);
        $tasks->each(function ($task) use ($now) {
            $task->start_date = $now->copy()->addDay();
            $task->save();
        });

        yield
        '開始日前のタスクをカウントしないこと' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 0,
        ];

        //中断したタスクをカウントいないこと
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $tasks = $validScheduleTaskGenerator($project, $user, $now, 1);
        $tasks->each(function ($task) use ($now) {
            $task->status = Task::STATE_PAUSE;
            $task->save();
        });

        yield
        '中断したタスクをカウントいないこと' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 0,
        ];

        //無効なタスクをカウントしないこと
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $tasks = $validScheduleTaskGenerator($project, $user, $now, 1);
        $tasks->each(function ($task) use ($now) {
            $task->status = Task::STATE_INVALID;
            $task->save();
        });

        yield
        '中断したタスクをカウントいないこと' => [
            'user'          => $user,
            'project'       => $project,
            'now'           => $now,
            'expectedCount' => 0,
        ];
    }
}
