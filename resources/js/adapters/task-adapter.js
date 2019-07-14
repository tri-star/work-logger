class TaskAdapter {
    construct() {}

    async addTask(projectId, task) {
        const response = await window.axios.post(
            `/api/v1/project/${projectId}/task/add`,
            {
                title: task.title,
                start_date: task.start_date,
                end_date: task.end_date,
                estimate_minutes: task.estimate_minutes,
                status: task.status
            }
        )

        return response.data
    }

    async updateTask(id, task) {
        const response = await window.axios.post(`/api/v1/task/${id}`, {
            title: task.title,
            start_date: task.start_date,
            end_date: task.end_date,
            estimate_minutes: task.estimate_minutes,
            status: task.status
        })

        return response.data
    }

    async getTask(id) {
        const response = await window.axios.get(`/api/v1/task/${id}`)

        return response.data
    }

    async search(projectId) {
        const response = await window.axios.get(
            `/api/v1/project/${projectId}/task/list`
        )

        return response.data
    }
}

const taskAdapter = new TaskAdapter()

export default taskAdapter
