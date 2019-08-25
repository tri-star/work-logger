class DashboardAdapter {
    construct() {}

    async getProjectList() {
        const response = await window.axios.get("/api/v1/project/list")

        return response.data
    }

    async getTotalCompletedTaskCount() {
        const response = await window.axios.get(
            "/api/v1/task/total-completed-task-count"
        )

        return response.data.count
    }

    async getNearDeadlineTaskList() {
        const response = await window.axios.get(
            "/api/v1/task/near-deadline-list"
        )

        return response.data.tasks
    }

    async getInProgressTaskList() {
        const response = await window.axios.get("/api/v1/task/in-progress-list")

        return response.data.tasks
    }
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
