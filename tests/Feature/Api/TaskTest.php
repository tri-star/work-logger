<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class TaskTest extends TestCase
{
    use RefreshDatabase;

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
}
