<?php

namespace WorkLogger\Domain\Project;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use WorkLogger\Domain\Task\Task;
use WorkLogger\Domain\User\User;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'description',
    ];

    public function isMember(User $user)
    {
        foreach ($this->users as $user) {
            if ($user->id === $user->id) {
                return true;
            }
        }
        return false;
    }


    /**
     * プロジェクト別のタスクの件数一覧を返す
     */
    public static function getTaskCountList(int $userId): Collection
    {
        $list = Task::select(['tasks.project_id', 'projects.project_name', \DB::raw('count(*) as count')])
            ->join('project_user', function ($join) use ($userId) {
                $join->on('project_user.project_id', '=', 'tasks.project_id')
                    ->where('project_user.user_id', '=', $userId);
            })
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->groupBy(['tasks.project_id', 'project_name'])
            ->orderBy('tasks.project_id')
            ->get();

        return $list;
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin');
    }


    public function adminUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin')->WherePivot('is_admin', 1);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
