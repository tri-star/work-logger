<?php

declare(strict_types=1);

namespace WorkLogger\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Domain\Task\Task;
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


    public function getTask(int $id)
    {
        $task = Task::with('project')->find($id);
        if (!$task || !$task->project->isMember(\Auth::user())) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($task);
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
}
