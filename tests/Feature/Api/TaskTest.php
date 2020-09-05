<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\Task\TaskLog;
use WorkLogger\Domain\User\User;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Laravel7対応で日付のシリアライズ方法が変わったことの影響に対処出来ていることを確認するため、
     * 日付も含めて想定通りの値が返っているかをテストする
     */
    public function test_addTask_登録成功_登録したタスクの情報が返されること()
    {
        $baseDate = Carbon::parse('2020-01-01 00:00:00');
        Carbon::setTestNow($baseDate);
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $project = factory(Project::class)->create();
        $project->users()->save($user);

        $response = $this->postJson("/api/v1/project/{$project->id}/task/add", [
            'issue_no'       => 'ISSUE-NO',
            'title'          => 'title',
            'description'    => 'description',
            'actual_minutes' => 0.5,
            'status'         => Task::STATE_IN_PROGRESS,
            'start_date'     => '2020-01-10',
            'end_date'       => '2020-01-11',
        ]);
        $response->assertStatus(200);

        $json = $response->json();
        $registeredTask = array_except($json['registered_task'], ['id']);
        $this->assertEquals([
            'user_id'        => $user->id,
            'project_id'     => $project->id,
            'issue_no'       => 'ISSUE-NO',
            'title'          => 'title',
            'description'    => 'description',
            'actual_minutes' => 0.5,
            'status'         => Task::STATE_IN_PROGRESS,
            'start_date'     => '2020-01-10 00:00:00',
            'end_date'       => '2020-01-11 00:00:00',
            'created_at'     => $baseDate->format('Y-m-d H:i:s'),
            'updated_at'     => $baseDate->format('Y-m-d H:i:s'),
        ], $registeredTask);
    }

    public function test_suggestList_キーワードに合致したものを取得できること()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $project = factory(Project::class)->create();
        $project->users()->save($user);

        $task1 = factory(Task::class)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'title'      => 'test1',
            'status'     => Task::STATE_NONE,
        ]);
        factory(Task::class)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'title'      => 'test2',
            'status'     => Task::STATE_NONE,
        ]);

        $response = $this->get("/api/v1/task/suggest-list?project_id={$project->id}&keyword={$task1->title}");
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertCount(1, $json);
    }

    public function test_suggestList_空を渡せること()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $project = factory(Project::class)->create();
        $project->users()->save($user);

        $task1 = factory(Task::class)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'title'      => 'test1',
            'status'     => Task::STATE_NONE,
        ]);

        $response = $this->get("/api/v1/task/suggest-list?project_id={$project->id}&keyword=");
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertCount(1, $json);
    }


    public function test_suggestList_上限以上は返却されないこと()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $project = factory(Project::class)->create();
        $project->users()->save($user);

        $task1 = factory(Task::class, 25)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'status'     => Task::STATE_NONE,
        ]);

        $response = $this->get("/api/v1/task/suggest-list?project_id={$project->id}&keyword=");
        $response->assertStatus(200);

        $json = $response->json();
        $maxTaskSuggestions = 20;
        $this->assertCount($maxTaskSuggestions, $json);
    }


    /**
     * Laravel7対応で日付のシリアライズ方法が変わったことの影響に対処出来ていることを確認するため、
     * 日付も含めて想定通りの値が返っているかをテストする
     */
    public function test_getTask_withTaskLogs()
    {
        $baseDate = Carbon::parse('2020-01-01 00:00:00');
        Carbon::setTestNow($baseDate);
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $project = factory(Project::class)->create();
        $project->users()->save($user);
        $task = factory(Task::class)->create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'status'     => Task::STATE_IN_PROGRESS,
        ]);
        $log = factory(TaskLog::class)->create([
            'task_id' => $task->id,
        ]);

        $response = $this->get("/api/v1/task/{$task->id}?with_task_logs=1");
        $response->assertStatus(200);

        $json = $response->json();
        $taskLog = $json['logs'][0];
        $this->assertEquals([
            'id'         => $log->id,
            'task_id'    => $log->task_id,
            'hours'      => (string)$log->hours,
            'memo'       => $log->memo,
            'status'     => $log->status,
            'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $log->updated_at->format('Y-m-d H:i:s'),
        ], $taskLog);
    }
}
