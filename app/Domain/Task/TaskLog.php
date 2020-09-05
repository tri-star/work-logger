<?php

namespace WorkLogger\Domain\Task;

use DateTimeInterface;
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

    /**
     * 配列／JSONシリアライズのためデータを準備する
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
