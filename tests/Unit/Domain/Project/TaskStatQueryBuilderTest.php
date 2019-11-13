<?php

namespace Tests\Unit\Domain\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Project\TaskStatQueryBuilder;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class TaskStatQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_getWeeklyDoneTaskStat_完了状態のみ集計されること()
    {
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        factory(Task::class, 5)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $now]);
        factory(Task::class, 1)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_IN_PROGRESS]);

        $taskStatQueryBuilder = new TaskStatQueryBuilder();
        $count = $taskStatQueryBuilder->getWeeklyDoneTaskStat($project->id);

        $this->assertEquals(5, $count);
    }


    public function test_getWeeklyDoneTaskStat_対象プロジェクトのみ集計されること()
    {
        $now = Carbon::now();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        factory(Task::class, 5)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $now]);

        $anotherProject = factory(Project::class)->create();
        factory(Task::class, 3)->create(['project_id' => $anotherProject->id, 'user_id' => $user->id]);

        $taskStatQueryBuilder = new TaskStatQueryBuilder();
        $count = $taskStatQueryBuilder->getWeeklyDoneTaskStat($project->id);

        $this->assertEquals(5, $count);
    }


    public function test_getWeeklyDoneTaskStat_直近1週間分が集計されること()
    {
        $now = Carbon::parse('2019-07-10 12:00:00');
        $date = $now->copy();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]);
        factory(Task::class)->create(['project_id' => $project->id, 'user_id' => $user->id, 'status' => Task::STATE_DONE, 'completed_at' => $date->subDay()]); //8日前

        $taskStatQueryBuilder = new TaskStatQueryBuilder();

        $list = $taskStatQueryBuilder->getDailyDoneTaskList($project->id, $now);
        $list = collect($list)->filter(function ($count) {
            return $count > 0;
        })->toArray();

        $this->assertCount(7, $list);
    }
}
