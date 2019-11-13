<?php

namespace WorkLogger\UseCase\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

/**
 * タスクの状態の一括更新を行う
 */
class BulkUpdateStateUseCase
{

    /**
     * @param User $user 実行sユーザー
     * @param array $ids タスクIDの配列
     * @param int $newState 新しい状態
     * @return array
     */
    public function execute(User $user, array $ids, int $newState): array
    {
        \DB::beginTransaction();

        try {
            $projectIds = $user->projects->pluck('id')->toArray();
            if (!$this->validateTaskId($projectIds, $ids)) {
                return [
                    'success'        => false,
                    'error_messages' => '無効なタスクIDが含まれています',
                ];
            }

            $input = [
                'ids'    => $ids,
                'newState' => $newState,
            ];
            $validator = $this->getValidator($input);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $this->getTaskListQuery($projectIds, $ids)->get()->each(function (Task $task) use ($newState) {
                $this->updateState($task, $newState);
            });

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
            'success' => true,
        ];
    }


    private function getValidator(array $input): Validator
    {
        $rules = [
            'ids'      => 'required|array|min:1',
            'newState' => ['required', Rule::in(Task::getStatuses())],
        ];

        $messages = [
        ];

        $attributes = [
            'ids'      => 'タスクID',
            'newState' => '状態',
        ];

        return \Validator::make($input, $rules, $messages, $attributes);
    }


    /**
     * タスクIDの配列が有効かを返す
     * @param array $userProjectIds ユーザーが参加しているプロジェクトIDの配列
     * @param array $ids タスクIDの配列
     * @return bool
     */
    private function validateTaskId(array $userProjectIds, array $ids): bool
    {
        $taskCount = $this->getTaskListQuery($userProjectIds, $ids)->count();
        if ($taskCount != count($ids)) {
            return false;
        }

        return true;
    }


    /**
     * タスク一覧取得用のクエリビルダを返す
     * @param array $userProjectIds ユーザーが参加しているプロジェクトIDの配列
     * @param array $ids タスクIDの配列
     * @return Builder
     */
    private function getTaskListQuery(array $userProjectIds, array $ids): Builder
    {
        return Task::whereIn('project_id', $userProjectIds)->whereIn('id', $ids);
    }


    /**
     * 1件分を更新する
     * @param Task $task
     * @param int $newState
     * @param $parameters
     */
    private function updateState(Task $task, int $newState)
    {
        $task->status = $newState;
        $task->save();
    }
}
