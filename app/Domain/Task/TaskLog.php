<?php

namespace WorkLogger\Domain\Task;

use Illuminate\Database\Eloquent\Model;

/**
 * 作業ログ
 */
class TaskLog extends Model
{
    protected $fillable = [
        'hours',
        'memo',
        'status',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(\WorkLogger\Domain\Task\Task::class);
    }
}
