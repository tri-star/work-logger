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

    async getScheduledTasks(id) {
        const response = await window.axios.get(
            `/api/v1/project/${id}/scheduled-tasks`
        )

        return response.data
    }

    async searchTaskList(id) {
        return [
            {
                id: 1,
                user_id: 1,
                project_id: 1,
                title: "タスク1",
                estimate_minutes: 10,
                actual_minutes: 0,
                created_at: "2019-01-01 00:00:00",
                updated_at: "2019-01-01 00:00:00",
                status: 0,
                completed_at: null,
                start_date: "2019-07-01",
                end_date: "2019-07-10"
            }
        ]
    }
}

const projectAdapter = new ProjectAdapter()

export default projectAdapter
