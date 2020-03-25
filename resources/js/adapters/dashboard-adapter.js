import _pick from 'lodash/pick'

class DashboardAdapter {
  construct () {}

  async getProjectList () {
    const response = await window.axios.get('/api/v1/project/list')

    return Object.fromEntries(
      Object.entries(response.data).map(([projectId, row]) => {
        return [projectId, {
          ..._pick(row, [
            'id',
            'project_name',
            'task_count',
            'completed_task_count',
            'total_result_hours',
            'total_estimate_hours'
          ])
        }]
      })

    )
  }

  async getProjectSuggestionList (keyword) {
    const response = await window.axios.get('/api/v1/project/suggest-list', {
      params: {
        keyword
      }
    })
    return response.data
  }

  async getTaskSuggestionList (projectId, keyword) {
    const response = await window.axios.get('/api/v1/task/suggest-list', {
      params: {
        project_id: projectId,
        keyword
      }
    })
    return response.data
  }

  async getNearDeadlineTaskList () {
    const response = await window.axios.get(
      '/api/v1/task/near-deadline-list'
    )

    return response.data.tasks
  }

  async getInProgressTaskList () {
    const response = await window.axios.get('/api/v1/task/in-progress-list')

    return response.data.tasks
  }

  async registerResult (taskId, parameters) {
    const response = await window.axios.post(`/api/v1/task/${taskId}/log/add`, {
      ..._pick(parameters, ['hours', 'memo', 'status'])
    })
    return response.data
  }
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
