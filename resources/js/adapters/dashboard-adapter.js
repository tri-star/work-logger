class DashboardAdapter {
    construct() {}

    async getProjectList() {
        const response = await window.axios.get("/api/v1/project/list")

        return response.data
    }
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
