<?php

namespace WorkLogger\Domain\Project;

use Carbon\Carbon;
use WorkLogger\Domain\Task\Task;

class TaskStatQueryBuilder
{
    /**
     * 週間のタスク終了件数を返す
     * @param int $projectId プロジェクトID
     * @return int
     */
    public function getWeeklyDoneTaskStat(int $projectId, ?Carbon $now=null): int
    {
        if (is_null($now)) {
            $now = Carbon::now();
        }

        $sql = 'select count(*) as count from tasks '
             . 'where project_id=:project_id '
             . ' and status=:status'
             . ' and completed_at >= :term_from';

        $result = \DB::select($sql, [
            'project_id' => $projectId,
            'status' => Task::STATE_DONE,
            'term_from' => $now->copy()->subDays(7)
        ]);

        return $result[0]->count;
    }


    /**
     * 日別の完了数の一覧を返す
     * @param int $projectId プロジェクトID
     * @return array
     */
    public function getDailyDoneTaskList(int $projectId, ?Carbon $now=null): array
    {
        if (is_null($now)) {
            $now = Carbon::now();
        }

        $sql = "select date_format(completed_at, '%Y-%m-%d') as date, count(*) as count from tasks "
            . 'where project_id=:project_id '
            . ' and status=:status'
            . ' and completed_at > :term_from'
            . ' group by date';

        $result = collect(\DB::select($sql, [
            'project_id' => $projectId,
            'status' => Task::STATE_DONE,
            'term_from' => $now->copy()->subDays(7)
        ]))->mapWithKeys(function ($item) {
            return [ $item->date => $item->count ];
        })->toArray();

        //存在しない日は0で補間
        $currentDate = $now->copy();
        for ($i=0; $i<7; $i++) {
            $key = $currentDate->format('Y-m-d');
            if (!isset($result[$key])) {
                $result[$key] = 0;
            }
            $currentDate->subDay();
        }
        krsort($result);
        return $result;
    }
}
