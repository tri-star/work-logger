<?php

namespace WorkLogger\UseCase\Project;

use Illuminate\Contracts\Validation\Validator;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\User\User;

class RegisterProjectUseCase
{
    public function execute(User $user, array $parameters): array
    {
        \DB::beginTransaction();

        $project = null;

        try {
            $validator = $this->getValidator($parameters);
            if ($validator->fails()) {
                return [
                    'success'        => false,
                    'error_messages' => $validator->errors()->all(),
                ];
            }

            $project = new Project($parameters);
            $project->save();
            $project->users()->save($user);
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
            'success'            => true,
            'registered_project' => $project,
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
