<?php

namespace WorkLogger\Domain\Project;

use Illuminate\Database\Eloquent\Builder;
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
     * 名前にキーワードが部分一致するものの一覧を返す
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId ユーザーID
     * @return \Illuminate\Database\Query\Builder
     */
    public static function scopeOwnedBy(Builder $query, int $userId)
    {
        return $query->join('project_user', 'project_user.project_id', '=', 'projects.id')
            ->where('project_user.user_id', $userId);
    }


    /**
     * 名前にキーワードが部分一致するものの一覧を返す
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword キーワード
     * @return \Illuminate\Database\Query\Builder
     */
    public static function scopeIncludeKeyword(Builder $query, string $keyword)
    {
        return $query->where('project_name', 'like', "%{$keyword}%")->orderBy('project_name');
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
