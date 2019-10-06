class TaskAdapter {
    construct() {}

    async addTask(projectId, task) {
        const response = await window.axios.post(
            `/api/v1/project/${projectId}/task/add`,
            {
                title: task.title,
                issue_no: task.issue_no,
                description: task.description,
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
            issue_no: task.issue_no,
            description: task.description,
            start_date: task.start_date,
            end_date: task.end_date,
            estimate_minutes: task.estimate_minutes,
            status: task.status
        })

        return response.data
    }

    async getTask(id, options = {}) {
        let queries = {}
        if (options["with_task_logs"]) {
            queries["with_task_logs"] = 1
        }
        const response = await window.axios.get(`/api/v1/task/${id}`, {
            params: queries
        })

        return response.data
    }

    async search(projectId, conditions) {
        const response = await window.axios.get(
            `/api/v1/project/${projectId}/task/list`,
            {
                params: {
                    keyword: window._.get(conditions, "keyword", ""),
                    sort_order: window._.get(conditions, "sortOrder", ""),
                    statuses: window._.get(conditions, "statuses", [])
                }
            }
        )

        return response.data
    }

    async bulkDateUpdate(taskIds, type, params) {
        await window.axios.post("/api/v1/task/bulk-date-update", {
            ids: taskIds,
            type,
            params
        })
    }
}

const taskAdapter = new TaskAdapter()

export default taskAdapter
