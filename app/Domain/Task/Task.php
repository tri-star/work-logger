<?php

namespace WorkLogger\Domain\Task;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * タスク
 */
class Task extends Model
{
    const STATE_NONE = 0;
    const STATE_IN_PROGRESS = 10;
    const STATE_DONE = 20;
    const STATE_PAUSE = 30;
    const STATE_INVALID = 40;

    protected $fillable = [
        'issue_no',
        'title',
        'description',
        'estimate_minutes',
        'actual_minutes',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\WorkLogger\Domain\User\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(\WorkLogger\Domain\Project\Project::class);
    }
}
