<?php

namespace WorkLogger\Domain\Task;

use Illuminate\Database\Eloquent\Model;

/**
 * タスク
 */
class Task extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\WorkLogger\Domain\User\User::class);
    }
}
