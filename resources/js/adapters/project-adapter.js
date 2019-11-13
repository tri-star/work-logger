import _pick from 'lodash/pick'

class ProjectAdapter {
  construct () {}

  async getProject (id) {
    const response = await window.axios.get(`/api/v1/project/${id}/detail`)

    return response.data
  }

  async getTaskStat (id) {
    const response = await window.axios.get(
      `/api/v1/project/${id}/task-stat`
    )

    return response.data
  }

  async getScheduledTasks (id) {
    const response = await window.axios.get(
      `/api/v1/project/${id}/scheduled-tasks`
    )

    return response.data
  }

  async getInProgressTasks (id) {
    const response = await window.axios.get(
      `/api/v1/project/${id}/in-progress-tasks`
    )

    return response.data
  }

  async addProject (project) {
    await window.axios.post('/api/v1/project/add', {
      ..._pick(project, ['project_name', 'description'])
    })
  }

  async editProject (id, project) {
    await window.axios.post(`/api/v1/project/${id}/edit`, {
      ..._pick(project, ['project_name', 'description'])
    })
  }
}

const projectAdapter = new ProjectAdapter()

export default projectAdapter
