<?php

namespace WorkLogger\UseCase\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class RegisterTaskUseCase
{
    /**
     * タスクの登録を行う
     * @param User $user
     * @param int $projectId
     * @param array $parameters
     * @return array
     */
    public function execute(User $user, int $projectId, array $parameters): array
    {
        \DB::beginTransaction();

        //プロジェクトメンバーでなければ登録不可能
        $project = Project::find($projectId);
        if (!$project || !$project->isMember($user)) {
            return [
                'success'        => false,
                'error_messages' => [
                    '無効なプロジェクトが指定されました。',
                ],
            ];
        }

        $task = null;

        try {
            $validator = $this->getValidator($parameters);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $task = new Task($parameters);
            $task->user_id = $user->id;
            $task->project_id = $projectId;
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
            'success'         => true,
            'registered_task' => $task,
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
