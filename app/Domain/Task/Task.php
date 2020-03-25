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

    const MAX_TITLE_LENGTH = 50;
    const MAX_ISSUE_NO_LENGTH = 50;
    const MAX_DESCRIPTION_LENGTH = 255;

    protected $fillable = [
        'issue_no',
        'title',
        'description',
        'start_date',
        'end_date',
        'estimate_hours',
        'actual_minutes',
        'status',
    ];

    protected $dates = [
        'start_date',
        'end_date',
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
     * 全ての選択肢の一覧を返す
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATE_NONE,
            self::STATE_IN_PROGRESS,
            self::STATE_DONE,
            self::STATE_PAUSE,
            self::STATE_INVALID,
        ];
    }


    /**
     * タスクの状態を更新する(状態変化に伴う操作も実行する)。
     * @param int $newStatus
     */
    public function changeStatus(int $newStatus)
    {
        if ($this->status !== $newStatus && $newStatus == self::STATE_DONE) {
            $this->completed_at = Carbon::now();
        }
        $this->status = $newStatus;
    }


    /**
     * 指定した日数分開始日と終了日をずらす
     * @param int $offset
     */
    public function updateDateByOffset(int $offset)
    {
        if ($this->start_date) {
            $this->start_date = $this->start_date->addDays($offset);
        }
        if ($this->end_date) {
            $this->end_date = $this->end_date->addDays($offset);
        }
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(TaskLog::class)->orderBy('id', 'desc');
    }


    /**
     * @return float
     */
    public function getActualTime()
    {
        return $this->logs()->sum('hours');
    }


    /**
     * ユーザーに割り当てられたタスクのうち、開始日を過ぎて未完了のものの一覧を返す
     * @param Builder $query
     * @param int $projectId プロジェクトID
     * @param int $userId ユーザーID
     * @param Carbon|null $now
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeScheduledTasks(Builder $query, int $projectId, int $userId, Carbon $now=null)
    {
        if (is_null($now)) {
            $now = Carbon::now();
        }

        return $query->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNotIn('status', $this->getEndStatuses())
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->orderBy('end_date');
    }


    /**
     *
     * @param Builder $query クエリ
     * @param int $userId ユーザーID
     * @param int $deadlineDays 締め切りまでの日数
     * @param Carbon|null $now 現在時刻
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeNearDeadline(Builder $query, int $userId, int $deadlineDays, ?Carbon $now=null)
    {
        if (is_null($now)) {
            $now = Carbon::now();
        }

        return $query->where('user_id', $userId)
            ->whereNotIn('status', $this->getEndStatuses())
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->where('end_date', '<=', $now->clone()->addDays($deadlineDays)->format('Y-m-d'))
            ->orderBy('end_date');
    }


    /**
     * 作業中のタスク一覧を返す
     * @param Builder $query クエリ
     * @param int $userId ユーザーID
     * @param ?int $projectId プロジェクトID
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeInProgress(Builder $query, int $userId, ?int $projectId=null)
    {
        $query->where('user_id', $userId)
            ->where('status', Task::STATE_IN_PROGRESS)
            ->orderBy('end_date')
            ->orderBy('id', 'desc');

        $query->when($projectId, function ($query, $projectId) {
            $query->where('project_id', $projectId);
        });

        return $query;
    }


    public function scopeInProject(Builder $query, int $projectId)
    {
        return $query->where('project_id', $projectId)
            ->orderBy('updated_at', 'desc');
    }


    /**
     * 名前にキーワードが部分一致するものの一覧を返す
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId ユーザーID
     * @param int $projectId プロジェクトID
     * @param string $keyword キーワード
     * @return \Illuminate\Database\Query\Builder
     */
    public static function scopeIncludeKeyword(Builder $query, int $userId, int $projectId, string $keyword)
    {
        return $query
            ->where('user_id', $userId)
            ->where('project_id', $projectId)
            ->where('title', 'like', "%{$keyword}%")
            ->orderBy('title');
    }
}
