<?php
declare(strict_types=1);

namespace Tests\Unit\UseCase\Task;

use Tests\TestCase;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;
use WorkLogger\UseCase\Task\RegisterTaskLogUseCase;

class RegisterTaskLogUseCaseTest extends TestCase
{

    /**
     * @var RegisterTaskLogUseCase
     */
    private $useCase;

    protected function setUp(): void {
        parent::setUp();
        $this->useCase = new RegisterTaskLogUseCase();
    }

    public function test正常系_作業ログを登録できること()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task->project->users()->save($user);

        $taskLog = [
            'hours' => 0.1,
            'status' => Task::STATE_DONE,
            'memo' => 'メモ',
        ];
        $result = $this->useCase->execute($user, $task->id, $taskLog);
        $this->assertTrue($result['success']);

        $registeredTaskLog = $result['registered_task_log'];
        $this->assertEquals($taskLog['hours'], $registeredTaskLog['hours'], '作業時間が一致しません');
        $this->assertEquals($taskLog['memo'], $registeredTaskLog['memo'], 'メモが一致しません');
        $this->assertEquals($taskLog['status'], $registeredTaskLog['status'], 'ステータスが一致しません');
    }


    public function test正常系_ステータスを省略した場合は元のステータスのまま登録されること()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => Task::STATE_IN_PROGRESS,
        ]);
        $task->project->users()->save($user);

        $taskLog = [
            'hours' => 0.1,
            'memo' => 'メモ',
        ];
        $result = $this->useCase->execute($user, $task->id, $taskLog);
        $this->assertTrue($result['success']);

        $updatedTask = Task::find($task->id);
        $registeredTaskLog = $result['registered_task_log'];
        $this->assertEquals($task->status, $updatedTask->status, 'ステータスが一致しません');
        $this->assertEquals($task->status, $registeredTaskLog->status, '作業ログのステータスが一致しません');
    }

}
