<?php

namespace WorkLogger\UseCase\Project;

use Illuminate\Contracts\Validation\Validator;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\User\User;

class EditProjectUseCase
{
    public function execute(User $user, int $projectId, array $parameters): array
    {
        \DB::beginTransaction();

        $project = null;

        try {
            $project = Project::find($projectId);
            if (!$project) {
                return [
                    'test'           => true,
                    'success'        => false,
                    'error_messages' => '無効なプロジェクトが指定されました',
                ];
            }
            $isValidUser = $project->users->contains(function (User $projectUser) use ($user) {
                return $projectUser->id === $user->id;
            });
            if (!$isValidUser) {
                return [
                    'success'        => false,
                    'error_messages' => '無効なプロジェクトが指定されました',
                ];
            }

            $validator = $this->getValidator($parameters);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $project->fill($parameters);
            $project->save();
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
            'updated_project' => $project,
        ];
    }


    public function getValidator(array $parameters): Validator
    {
        $rules = [
            'project_name' => 'required|max:50',
            'description'  => 'max:512',
        ];
        $messages = [];
        $attributes = [
            'project_name' => 'プロジェクト名',
            'description'  => '詳細',
        ];

        return \Validator::make($parameters, $rules, $messages, $attributes);
    }
}
