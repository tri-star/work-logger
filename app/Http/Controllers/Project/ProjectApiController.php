<?php

namespace WorkLogger\Http\Controllers\Project;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Project\TaskStatQueryBuilder;
use WorkLogger\Http\Controllers\Controller;
use WorkLogger\Http\Response\JsonResponse;

class ProjectApiController extends Controller
{
    public function getList()
    {
        $user = \Auth::user();
        $projects = $user->projects()->orderBy('updated_at', 'desc')->get()->mapWithKeys(function ($project) {
            return [
                $project->id => [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'description' => $project->description,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ]
            ];
        });
        return new JsonResponse($projects);
    }


    public function getDetail(int $id)
    {
        $user = \Auth::user();
        $project = Project::with('users:id,name')->find($id);

        if (!$project || !$project->isMember($user)) {
            throw new NotFoundHttpException('無効なプロジェクトが指定されました');
        }

        $json = [
            'id' => $project->id,
            'project_name' => $project->project_name,
            'description' => $project->description,
            'users' => $project->users->mapWithKeys(function ($user) {
                return [
                    $user->id => $user->name,
                 ];
            })->toArray(),
        ];

        return new JsonResponse($json);
    }


    public function getTaskStatList(int $id)
    {
        $user = \Auth::user();
        $project = Project::with('users:id,name')->find($id);

        if (!$project || !$project->isMember($user)) {
            throw new NotFoundHttpException('無効なプロジェクトが指定されました');
        }


        $taskStatQueryBuilder = new TaskStatQueryBuilder();
        $data = [
            'weekly_done_count' => $taskStatQueryBuilder->getWeeklyDoneTaskStat($project->id),
            'daily_done_list' => $taskStatQueryBuilder->getDailyDoneTaskList($project->id)
        ];

        return new JsonResponse($data);
    }
}
