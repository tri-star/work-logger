<?php

namespace WorkLogger\Domain\Project;

use Illuminate\Database\Eloquent\Model;
use WorkLogger\Domain\User\User;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'description'
    ];


    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin');
    }


    public function adminUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin')->WherePivot('is_admin', 1);
    }
}
