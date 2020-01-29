<?php

namespace WorkLogger\UseCase\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\Task\TaskLog;
use WorkLogger\Domain\User\User;

class RegisterTaskLogUseCase
{
    /**
     * タスクの登録を行う
     * @param User $user
     * @param int $taskId
     * @param array $parameters
     * @return array
     */
    public function execute(User $user, int $taskId, array $parameters): array
    {
        \DB::beginTransaction();

        $task = Task::find($taskId);
        if (!$task) {
            return [
                'success'        => false,
                'error_messages' => [
                    '無効なタスクが指定されました。',
                ],
            ];
        }
        if (!$task->project || !$task->project->isMember($user)) {
            return [
                'success'        => false,
                'error_messages' => [
                    '無効なタスクが指定されました。',
                ],
            ];
        }

        try {
            $validator = $this->getValidator($parameters);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $status = $parameters['status'] ?? $task->status;

            $taskLog = new TaskLog($parameters);
            $taskLog->task_id = $taskId;
            $taskLog->status = $status;

            $taskLog->save();

            if ($task->status != $status) {
                $task->changeStatus($status);
                $task->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return [
                'success'        => false,
                'error_messages' => [
                    $e->getMessage(),
                ],
            ];
        }

        return [
            'success'             => true,
            'registered_task_log' => $taskLog,
        ];
    }


    private function getValidator(array $input): Validator
    {
        $parameters = $input;

        $rules = [
            'hours'      => 'required|numeric|min:0.1',
            'status'     => Rule::in(Task::getStatuses()),
        ];

        $messages = [
        ];

        $attributes = [
            'hours'   => '作業時間',
            'status'  => '状態',
        ];

        return \Validator::make($parameters, $rules, $messages, $attributes);
    }
}
