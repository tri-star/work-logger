class ProjectAdapter {
    construct() {}

    async getProject(id) {
        const response = await window.axios.get(`/api/v1/project/${id}/detail`)

        return response.data
    }

    async getTaskStat(id) {
        const response = await window.axios.get(
            `/api/v1/project/${id}/task-stat`
        )

        return response.data
    }
}

const projectAdapter = new ProjectAdapter()

export default projectAdapter
