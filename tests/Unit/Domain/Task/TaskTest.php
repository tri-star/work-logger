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


    /**
     * @dataProvider getDataForScopeNearDeadline_未完了状態のタスクのみカウントすること
     */
    public function test_scopeNearDeadline_未完了状態のタスクのみカウントすること($state, $expectedCount)
    {
        $task = factory(Task::class)->create([
            'status'      => $state,
            'start_date'  => Carbon::now(),
            'end_date'    => Carbon::now()->addDay(),
        ]);

        $actualCount = Task::nearDeadline($task->user_id, 1)->count();
        $this->assertEquals($expectedCount, $actualCount);
    }


    public function getDataForScopeNearDeadline_未完了状態のタスクのみカウントすること()
    {
        return [
            'なし'  => [Task::STATE_NONE, 1],
            '作業中' => [Task::STATE_IN_PROGRESS, 1],
            '完了'  => [Task::STATE_DONE, 0],
            '中断'  => [Task::STATE_PAUSE, 0],
            '無効'  => [Task::STATE_INVALID, 0],
        ];
    }


    public function test_scopeNearDeadline__別ユーザーのタスクはカウントしないこと()
    {
        $users = factory(User::class, 2)->create();

        $task = factory(Task::class)->create([
            'user_id'     => $users[0]->id,
            'status'      => Task::STATE_IN_PROGRESS,
            'start_date'  => Carbon::now(),
            'end_date'    => Carbon::now()->addDay(),
        ]);
        $task2 = factory(Task::class)->create([
            'user_id'     => $users[1]->id,
            'status'      => Task::STATE_IN_PROGRESS,
            'start_date'  => Carbon::now(),
            'end_date'    => Carbon::now()->addDay(),
        ]);

        $actualCount = Task::nearDeadline($task->user_id, 1)->count();
        $this->assertEquals(1, $actualCount);
    }


    /**
     * @dataProvider getDataForScopeNearDeadline_開始日_終了日間のタスクのみが対象になること
     */
    public function test_scopeNearDeadline_開始日_終了日間のタスクのみが対象になること($now, $startDate, $endDate, $expectedCount)
    {
        $task = factory(Task::class)->create([
            'status'      => Task::STATE_IN_PROGRESS,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
        ]);

        $actualCount = Task::nearDeadline($task->user_id, 1, $now)->count();
        $this->assertEquals($expectedCount, $actualCount);
    }


    public function getDataForScopeNearDeadline_開始日_終了日間のタスクのみが対象になること()
    {
        $now = Carbon::parse('2019-01-01 00:00:00');
        $endDate = $now->clone()->addDays(2);
        return [
            '開始日前'             => ['now' => $now, 'start_date' => $now->clone()->subSecond(), 'end_date' => $endDate, 'expected_count' => 0],
            '期間中_締め切りまで1日以上'   => ['now' => $now, 'start_date' => $now, 'end_date' => $now->clone()->addDays(2), 'expected_count' => 0],
            '期間中_締め切りまで1日'     => ['now' => $now, 'start_date' => $now, 'end_date' => $now->clone()->addDays(1), 'expected_count' => 1],
            '期間中_締め切りまで1日未満'   => ['now' => $now, 'start_date' => $now, 'end_date' => $now->clone()->addDays(1)->subSecond(), 'expected_count' => 1],
            '終了日後'             => ['now' => $now, 'start_date' => $now->clone()->subDays(2), 'end_date' => $endDate->clone()->subDays(1), 'expected_count' => 1],
        ];
    }
}
