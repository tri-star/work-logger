<?php

namespace WorkLogger\Domain\Project;

use Illuminate\Support\Collection;
use WorkLogger\Domain\User\User;
use WorkLogger\Domain\Task\Task;

class ProjectListStatQueryBuilder
{
    /**
     * プロジェクトの統計情報付きの一覧を返す
     * @param User $user ユーザー
     * @return Collection
     */
    public function getProjectList(User $user): Collection
    {
        $projects = collect($user->projects()->orderBy('updated_at', 'desc')->get());
        $projectIds = $projects->map(function ($project) {
            return "'" . $project->id . "'";
        })->toArray();

        if(count($projectIds) === 0) {
            return [];
        }

        $sql = 'select projects.id, count(distinct tasks.id) as task_count, '
            . '     count(distinct completed_tasks.id) as completed_task_count, '
            . '     sum(task_logs.hours) as total_result_hours, '
            . '     sum(tasks.estimate_minutes) as total_estimated_hours '
            . ' from projects '
            . ' left join tasks on (tasks.project_id=projects.id) '
            . ' left join tasks completed_tasks on (completed_tasks.project_id=projects.id and completed_tasks.status=:completed_status) '
            . ' left join task_logs on task_logs.task_id=tasks.id '
            . ' where projects.id in (' . implode(',', $projectIds) . ')'
            . ' group by projects.id ';

        $projectStats = collect(\DB::select($sql, [
            'completed_status' => Task::STATE_DONE
        ]))->mapWithKeys(function($row){
            return [$row->id => $row];
        })->toArray();

        return $projects->mapWithKeys(function($project) use($projectStats){
            return [
                $project->id => [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'task_count' => $projectStats[$project->id]->task_count,
                    'completed_task_count' => $projectStats[$project->id]->completed_task_count,
                    'total_result_hours' => $projectStats[$project->id]->total_result_hours,
                    'total_estimated_hours' => $projectStats[$project->id]->total_estimated_hours,
                ]
            ];
        });
    }
}
