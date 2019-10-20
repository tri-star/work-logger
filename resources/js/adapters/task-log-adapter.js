class TaskLogAdapter {
  construct () {}

  async addTaskLog (taskId, taskLog) {
    const response = await window.axios.post(
      `/api/v1/task/${taskId}/log/add`,
      {
        hours: taskLog.hours,
        memo: taskLog.memo,
        status: taskLog.status
      }
    )

    return response.data
  }
}

const taskLogAdapter = new TaskLogAdapter()

export default taskLogAdapter
