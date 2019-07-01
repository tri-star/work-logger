export default [
    {
        path: "/",
        component: require("./pages/dashboard/dashboard-container").default
    },
    {
        path: "/project/:id",
        component: require("./pages/project/project-detail-container").default,
        props: route => ({ id: Number(route.params.id) })
    }
]
