class DashboardAdapter {
  construct () {}

  async getProjectList () {
    const response = await window.axios.get('/api/v1/project/list')

    return response.data
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
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
