class ProjectAdapter {
    construct() {}

    async getProject(id) {
        const response = await window.axios.get(`/api/v1/project/detail/${id}`)

        return response.data
    }
}

const projectAdapter = new ProjectAdapter()

export default projectAdapter
