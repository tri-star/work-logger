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
