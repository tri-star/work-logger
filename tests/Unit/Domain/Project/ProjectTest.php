<?php

declare(strict_types = 1);

namespace Tests\Unit\Domain\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_scopeIncludeKeyword_キーワード指定なしでも検索可能()
    {
        $project = factory(Project::class)->create();

        $matchedProjects = Project::includeKeyword('')->get();
        $this->assertCount(1, $matchedProjects);
        $this->assertEquals($project->id, $matchedProjects[0]->id);
    }


    /**
     * @dataProvider for_test_scopeIncludeKeyword_指定キーワードに部分一致するものが含まれること
     */
    public function test_scopeIncludeKeyword_指定キーワードに部分一致するものが含まれること($projectName, $keyword, $expectedCount)
    {
        $project = factory(Project::class)->create([
            'project_name' => $projectName,
        ]);

        $matchedProjects = Project::includeKeyword($keyword)->get();
        $this->assertCount($expectedCount, $matchedProjects);
    }

    public function for_test_scopeIncludeKeyword_指定キーワードに部分一致するものが含まれること()
    {
        return [
            '先頭と一致' => ['project_name' => 'タイトルのテスト', 'keyword' => 'タイトル', 'expectedCount' => 1],
            '部分一致'  => ['project_name' => 'タイトルのテスト', 'keyword' => 'ルのテ', 'expectedCount' => 1],
            '後方と一致' => ['project_name' => 'タイトルのテスト', 'keyword' => 'テスト', 'expectedCount' => 1],
            '一致しない' => ['project_name' => 'タイトルのテスト', 'keyword' => 'サンプル', 'expectedCount' => 0],
        ];
    }


}
