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
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
