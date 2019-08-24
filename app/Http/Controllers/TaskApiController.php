<?php

declare(strict_types=1);

namespace WorkLogger\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;
use WorkLogger\Http\Response\JsonResponse;
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
        if ($withTaskLogs) {
            $response['logs'] = $task->logs->toArray();
            $response['actual_time'] = $task->getActualTime();
        }
        return new JsonResponse($response);
    }


    public function getTaskList(int $projectId)
    {
        $project = Project::find($projectId);
        if (!$project || !$project->isMember(\Auth::user())) {
            throw new NotFoundHttpException();
        }


        $tasks = Task::inProject($projectId)->get();
        return new JsonResponse($tasks);
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


    public function getTotalCompletedTaskCount()
    {
        $count = \Auth::user()->getTotalCompletedTaskCount();
        return new JsonResponse(['count' => $count]);
    }


    public function getNearDeadlineList()
    {
        $user = \Auth::user();
        $taskList = Task::nearDeadline($user->id, 2)->get()->mapWithKeys(function ($task) {
            return [$task->id => $task];
        });
        return new JsonResponse(['tasks' => $taskList]);
    }
}
