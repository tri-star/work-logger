class ProjectAdapter {
    construct() {}

    async getProject(id) {
        return {
            id,
            project_name: "プロジェクトA",
            description: "プロジェクトAの説明"
        }
    }
}

const projectAdapter = new ProjectAdapter()

export default projectAdapter
