<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Project\ProjectListStatQueryBuilder;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\Task\TaskLog;
use WorkLogger\Domain\User\User;

class ProjectListStatQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProjectListStatQueryBuilder
     */
    private $queryBuilder;


    protected function setUp(): void
    {
        parent::setUp();
        $this->queryBuilder = new ProjectListStatQueryBuilder();
    }

    public function test_総タスク数_プロジェクト毎にカウントされること()
    {
        $projects = factory(Project::class, 2)->create();
        $user = factory(User::class)->create();
        $projects[0]->users()->save($user);
        $projects[1]->users()->save($user);

        factory(Task::class, 3)->create([
            'project_id' => $projects[0]->id,
        ]);
        factory(Task::class, 1)->create([
            'project_id' => $projects[1]->id,
        ]);

        $result = $this->queryBuilder->getProjectList($user);

        $this->assertEquals(3, $result[$projects[0]->id]['task_count']);
        $this->assertEquals(1, $result[$projects[1]->id]['task_count']);
    }


    public function test_総タスク数_参加していないプロジェクトはカウントされないこと()
    {
        $projects = factory(Project::class, 2)->create();
        $user = factory(User::class)->create();
        $projects[0]->users()->save($user);

        factory(Task::class, 3)->create([
            'project_id' => $projects[0]->id,
        ]);
        factory(Task::class, 1)->create([
            'project_id' => $projects[1]->id,
        ]);

        $result = $this->queryBuilder->getProjectList($user);

        $this->assertEquals(3, $result[$projects[0]->id]['task_count']);
        $this->assertNotContains($projects[1]->id, $result);
    }


    public function test_完了タスク数_完了タスクのみカウントされること()
    {
        $projects = factory(Project::class, 2)->create();
        $user = factory(User::class)->create();
        $projects[0]->users()->save($user);
        $projects[1]->users()->save($user);

        factory(Task::class, 3)->create([
            'project_id' => $projects[0]->id,
            'status'     => Task::STATE_DONE,
        ]);
        factory(Task::class, 1)->create([
            'project_id' => $projects[0]->id,
            'status'     => Task::STATE_IN_PROGRESS,
        ]);

        $result = $this->queryBuilder->getProjectList($user);

        $this->assertEquals(3, $result[$projects[0]->id]['completed_task_count']);
    }


    public function test_総作業時間_プロジェクト配下の全タスクの実績が合計されること()
    {
        $projects = factory(Project::class, 2)->create();
        $user = factory(User::class)->create();
        $projects[0]->users()->save($user);
        $projects[1]->users()->save($user);

        $tasks = factory(Task::class, 3)->create([
            'project_id' => $projects[0]->id,
        ]);
        $anotherProjectTask = factory(Task::class, 1)->create([
            'project_id' => $projects[1]->id,
        ]);

        factory(TaskLog::class, 2)->create([
            'task_id' => $tasks[0]->id,
            'hours'   => 2.5,
        ]);
        factory(TaskLog::class, 2)->create([
            'task_id' => $tasks[1]->id,
            'hours'   => 2.5,
        ]);
        factory(TaskLog::class, 1)->create([
            'task_id' => $anotherProjectTask[0]->id,
            'hours'   => 3,
        ]);

        $result = $this->queryBuilder->getProjectList($user);

        $this->assertEquals(2.5 * 4, $result[$projects[0]->id]['total_result_hours']);
        $this->assertEquals(3, $result[$projects[1]->id]['total_result_hours']);
    }


    public function test_総見積時間_プロジェクト配下の全タスクの見積時間が合計されること()
    {
        $projects = factory(Project::class, 2)->create();
        $user = factory(User::class)->create();
        $projects[0]->users()->save($user);
        $projects[1]->users()->save($user);

        factory(Task::class, 3)->create([
            'project_id'       => $projects[0]->id,
            'estimate_minutes' => 1.4,
        ]);
        factory(Task::class, 1)->create([
            'project_id'       => $projects[1]->id,
            'estimate_minutes' => 2,
        ]);

        $result = $this->queryBuilder->getProjectList($user);

        $this->assertEquals(1.4 * 3, $result[$projects[0]->id]['total_estimated_hours']);
        $this->assertEquals(2, $result[$projects[1]->id]['total_estimated_hours']);
    }
}
