<?php

namespace WorkLogger\UseCase\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

/**
 * タスクの日付の一括更新を行う
 */
class BulkUpdateDateUseCase
{

    /**
     * @param User $user 実行sユーザー
     * @param array $ids タスクIDの配列
     * @param int $type 更新種別
     * @param array $parameters 更新内容を含んだ配列
     * @return array
     */
    public function execute(User $user, array $ids, int $type, array $parameters): array
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
                'type'   => $type,
                'params' => $parameters,
            ];
            $validator = $this->getValidator($input);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $this->getTaskListQuery($projectIds, $ids)->get()->each(function (Task $task) use ($type, $parameters) {
                $this->updateDate($task, $type, $parameters);
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
            'ids'                => 'required|array|min:1',
            'type'               => ['required', Rule::in([1,2])],
            'params.start_date'  => 'nullable|date_format:Y-m-d',
            'params.end_date'    => 'nullable|date_format:Y-m-d',
            'params.offset_date' => 'nullable|numeric',
        ];

        $messages = [
        ];

        $attributes = [
            'ids'                => 'タスクID',
            'type'               => '更新種別',
            'params.start_date'  => '開始予定日',
            'params.end_date'    => '終了予定日',
            'params.offset_date' => '基準日',
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
     * @param int $type
     * @param $parameters
     */
    private function updateDate(Task $task, int $type, $parameters)
    {
        if ($type === 1) {
            $task->updateDateByOffset(Arr::get($parameters, 'offset_date'));
            $task->save();
        } elseif ($type === 2) {
            $newStartDate = Arr::get($parameters, 'start_date');
            $newEndDate = Arr::get($parameters, 'end_date');
            if ($newStartDate) {
                $task->start_date = Carbon::parse($newStartDate);
                $task->end_date = Carbon::parse($newEndDate);
                $task->save();
            }
        } else {
            throw new \InvalidArgumentException('無効な種別が指定されました。' . $type);
        }
    }
}
