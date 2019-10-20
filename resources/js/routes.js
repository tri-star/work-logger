export default [
  {
    path: '/',
    component: require('./pages/dashboard/dashboard-container').default
  },
  {
    path: '/project/:id',
    component: require('./pages/project/project-detail-container').default,
    props: route => ({ id: Number(route.params.id) })
  },
  {
    path: '/project/:id/tasks',
    component: require('./pages/project/task-list-container').default,
    props: route => ({ id: Number(route.params.id) })
  },
  {
    path: '/task/:id',
    component: require('./pages/tasks/task-detail-container').default,
    props: route => ({ id: Number(route.params.id) })
  }
]
