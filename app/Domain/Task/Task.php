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
     * 終了した状態のステータス一覧を返す
     * @return array
     */
    public static function getEndStatuses(): array
    {
        return [
            self::STATE_DONE,
            self::STATE_PAUSE,
            self::STATE_INVALID,
        ];
    }


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


    /**
     * ユーザーに割り当てられたタスクのうち、開始日を過ぎて未完了のものの一覧を返す
     * @param Builder $query
     * @param int $projectId プロジェクトID
     * @param int $userId ユーザーID
     * @param Carbon|null $now
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeScheduledTasks(Builder $query, int $projectId, int $userId, Carbon $now=null)
    {
        if (is_null($now)) {
            $now = Carbon::now();
        }

        return $query->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNotIn('status', $this->getEndStatuses())
            ->where('start_date', '>=', $now->format('Y-m-d'))
            ->orderBy('end_date');
    }
}
