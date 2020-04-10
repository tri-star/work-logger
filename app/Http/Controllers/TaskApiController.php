<?php

declare(strict_types=1);

namespace WorkLogger\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\Task\TaskSearchQueryBuilder;
use WorkLogger\Http\Response\JsonResponse;
use WorkLogger\UseCase\Task\BulkUpdateDateUseCase;
use WorkLogger\UseCase\Task\BulkUpdateStateUseCase;
use WorkLogger\UseCase\Task\RegisterTaskLogUseCase;
use WorkLogger\UseCase\Task\RegisterTaskUseCase;
use WorkLogger\UseCase\Task\UpdateTaskUseCase;

class TaskApiController extends Controller
{
    public function addTask(int $projectId, Request $request, RegisterTaskUseCase $useCase)
    {
        $result = $useCase->execute(\Auth::user(), $projectId, $request->input());

        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function updateTask(int $taskId, Request $request, UpdateTaskUseCase $useCase)
    {
        $result = $useCase->execute(\Auth::user(), $taskId, $request->input());

        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function bulkUpdateDate(Request $request, BulkUpdateDateUseCase $useCase)
    {
        $ids = $request->input('ids', []);
        $type = (int) $request->input('type', 0);
        $params = $request->input('params', []);
        $result = $useCase->execute(\Auth::user(), $ids, $type, $params);

        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function bulkUpdateState(Request $request, BulkUpdateStateUseCase $useCase)
    {
        $ids = $request->input('ids', []);
        $newState = (int) $request->input('new_state', 0);
        $result = $useCase->execute(\Auth::user(), $ids, $newState);

        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function getTask(int $id, Request $request)
    {
        $withTaskLogs = $request->query->has('with_task_logs');
        $relations = ['project'];
        if ($withTaskLogs) {
            $relations[] = 'logs';
        }

        $task = Task::with($relations)->find($id);
        if (!$task || !$task->project->isMember(\Auth::user())) {
            throw new NotFoundHttpException();
        }

        $response = $task->attributesToArray();
        if (!empty($response['start_date'])) {
            $response['start_date'] = Carbon::parse($response['start_date'])->format('Y-m-d');
        }
        if (!empty($response['end_date'])) {
            $response['end_date'] = Carbon::parse($response['end_date'])->format('Y-m-d');
        }

        if ($withTaskLogs) {
            $response['logs'] = $task->logs->toArray();
            $response['actual_time'] = $task->getActualTime();
        }
        return new JsonResponse($response);
    }


    public function getTaskList(int $projectId, Request $request)
    {
        $project = Project::find($projectId);
        if (!$project || !$project->isMember(\Auth::user())) {
            throw new NotFoundHttpException();
        }

        $queryBuilder = new TaskSearchQueryBuilder();
        $tasks = $queryBuilder->build($projectId, $request->query->all())->get();
        return new JsonResponse($tasks);
    }


    public function getTaskSuggestionList(Request $request)
    {
        $user = \Auth::user();
        $projectId = $request->query('project_id', 0);
        $keyword = $request->query('keyword', '');
        if (is_null($keyword)) {
            $keyword = '';
        }

        $tasks = Task::includeKeyword($user->id, $projectId, $keyword)->limit(20)->get();
        $list = $tasks->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->title,
            ];
        });
        return new JsonResponse($list);
    }

    public function addTaskLog(int $taskId, Request $request, RegisterTaskLogUseCase $useCase)
    {
        $result = $useCase->execute(\Auth::user(), $taskId, $request->input());

        $statusCode = 200;
        if (!$result['success']) {
            $statusCode = 400;
        }
        return new JsonResponse($result, $statusCode);
    }


    public function getNearDeadlineList()
    {
        $user = \Auth::user();
        $taskList = Task::nearDeadline($user->id, 2)->get()->mapWithKeys(function ($task) {
            return [
                $task->id => [
                    'id'             => $task->id,
                    'title'          => $task->title,
                    'estimate_hours' => $task->estimate_hours,
                    'actual_time'    => $task->getActualTime(),
                    'end_date'       => $task->end_date,
                ],
            ];
        });
        return new JsonResponse(['tasks' => $taskList]);
    }

    public function getInProgressList()
    {
        $user = \Auth::user();
        $taskList = Task::inProgress($user->id)->get()->mapWithKeys(function ($task) {
            return [
                $task->id => [
                    'id'             => $task->id,
                    'title'          => $task->title,
                    'estimate_hours' => $task->estimate_hours,
                    'actual_time'    => $task->getActualTime(),
                    'end_date'       => $task->end_date,
                ],
            ];
        });
        return new JsonResponse(['tasks' => $taskList]);
    }
}
