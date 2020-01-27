<?php

namespace WorkLogger\Http\Controllers\Project;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Project\TaskStatQueryBuilder;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;
use WorkLogger\Http\Controllers\Controller;
use WorkLogger\Http\Response\JsonResponse;
use WorkLogger\UseCase\Project\EditProjectUseCase;
use WorkLogger\UseCase\Project\RegisterProjectUseCase;

class ProjectApiController extends Controller
{
    public function addProject(RegisterProjectUseCase $useCase, Request $request)
    {
        $user = \Auth::user();

        $result = $useCase->execute($user, $request->input());
        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function editProject(int $id, EditProjectUseCase $useCase, Request $request)
    {
        $user = \Auth::user();

        $result = $useCase->execute($user, $id, $request->input());
        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function getList()
    {
        $user = \Auth::user();
        $projects = $user->projects()->orderBy('updated_at', 'desc')->get()->mapWithKeys(function ($project) {
            return [
                $project->id => [
                    'id'           => $project->id,
                    'project_name' => $project->project_name,
                    'description'  => $project->description,
                    'created_at'   => $project->created_at,
                    'updated_at'   => $project->updated_at,
                ],
            ];
        });
        return new JsonResponse($projects);
    }


    public function getProjectSuggestionList(Request $request)
    {
        $user = \Auth::user();
        $keyword = $request->query('keyword', '');

        if (strlen($keyword) < 2) {
            $projects = collect([]);
        } else {
            $projects = Project::ownedBy($user->id)->includeKeyword($keyword)->get();
        }
        $list = $projects->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->project_name
            ];
        });
        return new JsonResponse($list);
    }


    public function getDetail(int $id)
    {
        $user = \Auth::user();
        $project = $this->getProject($id, $user);

        $json = [
            'id'           => $project->id,
            'project_name' => $project->project_name,
            'description'  => $project->description,
            'users'        => $project->users->mapWithKeys(function ($user) {
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
        $project = $this->getProject($id, $user);

        $taskStatQueryBuilder = new TaskStatQueryBuilder();
        $data = [
            'weekly_done_count' => $taskStatQueryBuilder->getWeeklyDoneTaskStat($project->id),
            'daily_done_list'   => $taskStatQueryBuilder->getDailyDoneTaskList($project->id),
        ];

        return new JsonResponse($data);
    }


    /**
     * 今日のタスクの一覧を返す
     * @param int $id
     */
    public function getScheduledTaskList(int $id)
    {
        $user = \Auth::user();
        $project = $this->getProject($id, $user);

        $list = Task::scheduledTasks($project->id, $user->id)->get()->mapWithKeys(function ($item) {
            return [
                $item->id => $item,
            ];
        });
        return new JsonResponse($list);
    }


    /**
     * 作業中のタスクの一覧を返す
     * @param int $id プロジェクトID
     * @return JsonResponse
     */
    public function getInProgressList(int $id)
    {
        $user = \Auth::user();
        $taskList = Task::inProgress($user->id)
            ->where('project_id', $id)
            ->get()->mapWithKeys(function ($task) {
                return [$task->id => $task];
            });
        return new JsonResponse($taskList);
    }


    /**
     * 指定されたIDのプロジェクトの情報を返す。(見つからない場合はNotFound例外)
     * @param int $id プロジェクトID
     * @param User $user ログイン中のユーザー
     * @return Project
     */
    private function getProject(int $id, User $user): Project
    {
        $project = Project::with('users:id,name')->find($id);

        if (!$project || !$project->isMember($user)) {
            throw new NotFoundHttpException('無効なプロジェクトが指定されました');
        }
        return $project;
    }
}
