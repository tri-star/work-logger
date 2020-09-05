<?php
namespace Tests\Unit\UseCase\Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;
use WorkLogger\UseCase\Task\RegisterTaskUseCase;

class RegisterTaskUseCaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var RegisterTaskUseCase
     */
    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = App::make(RegisterTaskUseCase::class);
    }

    public function test_登録成功時_登録されたタスクの情報が返されること()
    {
        $baseDate = Carbon::parse('2020-01-01 00:00:00');
        Carbon::setTestNow($baseDate);
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->save($user);
        $result = $this->useCase->execute($user, $project->id, [
            'issue_no'       => 'ISSUE-NO',
            'title'          => 'title',
            'description'    => 'description',
            'actual_minutes' => 0.5,
            'status'         => Task::STATE_IN_PROGRESS,
            'start_date'     => '2020-01-10',
            'end_date'       => '2020-01-11',
            'created_at'     => '2020-01-01 00:00:00',
            'updated_at'     => '2020-01-01 00:00:00',
        ]);

        \Log::debug($result);

        $this->assertEquals(true, $result['success']);
        $this->assertEquals($project->id, $result['registered_task']->project_id);
        $this->assertEquals($user->id, $result['registered_task']->user_id);
    }
}
