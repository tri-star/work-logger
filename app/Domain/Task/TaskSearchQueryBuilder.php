<?php

namespace WorkLogger\Domain\Task;

use Illuminate\Database\Eloquent\Builder;

class TaskSearchQueryBuilder
{
    public function build(int $projectId, array $conditions): Builder
    {
        $query = Task::where('project_id', $projectId);

        $query->when($conditions['keyword'] ?? null, function (Builder $query, $keyword) {
            $query->where(function (Builder $query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
                $query->orWhere('description', 'like', "%{$keyword}%");
            });
        });

        $query->when($conditions['sort_order'] ?? null, function (Builder $query, $sortOrder) {
            switch ($sortOrder) {
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                case 'start_date_asc':
                    $query->orderBy('start_date');
                    break;
                case 'end_date_asc':
                    $query->orderBy('end_date');
                    break;
            }
        });

        $query->when($conditions['statuses'] ?? null, function (Builder $query, $statuses) {
            $query->whereIn('status', $statuses);
        });

        return $query;
    }
}
