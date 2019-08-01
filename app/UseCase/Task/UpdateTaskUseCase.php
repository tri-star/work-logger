<?php

namespace WorkLogger\UseCase\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class UpdateTaskUseCase
{
    /**
     * タスクの更新を行う
     * @param User $user
     * @param int $taskId
     * @param array $parameters
     * @return array
     */
    public function execute(User $user, int $taskId, array $parameters): array
    {
        \DB::beginTransaction();

        $task = Task::with('project')->find($taskId);
        if (!$task) {
            return [
                'success'        => false,
                'error_messages' => [
                    '無効なタスクが指定されました。',
                ],
            ];
        }

        //プロジェクトメンバーでなければ登録不可能
        if (!$task->project || !$task->project->isMember($user)) {
            return [
                'success'        => false,
                'error_messages' => [
                    '無効なプロジェクトが指定されました。',
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

            $task->changeStatus($parameters['status']);
            $task->fill($parameters);
            $task->user_id = $user->id;
            if (is_null($task->issue_no)) {
                $task->issue_no = '';
            }

            $task->save();
        } catch (\Exception $e) {
            \DB::rollback();
            return [
                'success'        => false,
                'error_messages' => [
                    $e->getMessage(),
                ],
            ];
        }


        \DB::commit();

        return [
            'success' => true,
            'task'    => $task,
        ];
    }


    private function getValidator(array $input): Validator
    {
        $parameters = $input;

        $maxTitleLength = Task::MAX_TITLE_LENGTH;
        $rules = [
            'title'      => "required|max:{$maxTitleLength}",
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
            'status'     => Rule::in(Task::getStatuses()),
        ];

        $messages = [
        ];

        $attributes = [
            'title'      => 'タスク名',
            'start_date' => '開始予定日',
            'end_date'   => '終了予定日',
            'status'     => '状態',
        ];

        return \Validator::make($parameters, $rules, $messages, $attributes);
    }
}
