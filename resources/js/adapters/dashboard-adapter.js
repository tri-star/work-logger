class DashboardAdapter {
    construct() {}

    async getProjectList() {
        return [
            { id: 1, project_name: "プロジェクトA", description: "説明" },
            { id: 2, project_name: "プロジェクトB", description: "説明" },
            { id: 3, project_name: "プロジェクトC", description: "説明" },
            { id: 4, project_name: "プロジェクトD", description: "説明" },
            { id: 5, project_name: "プロジェクトE", description: "説明" }
        ]
    }
}

const dashboardAdapter = new DashboardAdapter()

export default dashboardAdapter
