<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\User\User;

class ProjectTest extends TestCase {

    use RefreshDatabase;

    public function test_suggestList_キーワードに合致したものを取得できること()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $projects = factory(Project::class, 2)->create();
        $projects[0]->users()->save($user);
        $projects[1]->users()->save($user);

        $response = $this->get("/api/v1/project/suggest-list?keyword={$projects[0]->project_name}");
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

        $response = $this->get("/api/v1/project/suggest-list?keyword=");
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertCount(1, $json);
    }


    public function test_suggestList_上限以上は返却されないこと()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $projects = factory(Project::class, 25)->create();
        $projects->each(function($project) use ($user) {
            $project->users()->save($user);
        });

        $response = $this->get("/api/v1/project/suggest-list?keyword=");
        $response->assertStatus(200);

        $json = $response->json();
        $maxProjectSuggestions = 20;
        $this->assertCount($maxProjectSuggestions, $json);
    }

}
