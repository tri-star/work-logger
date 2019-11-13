import DashboardAdapter from './dashboard-adapter'
import ProjectAdapter from './project-adapter'
import TaskAdapter from './task-adapter'
import TaskLogAdapter from './task-log-adapter'

const adapters = {
  DashboardAdapter,
  ProjectAdapter,
  TaskAdapter,
  TaskLogAdapter
}

class AdapterFactory {
  get (adapterName) {
    if (!adapters[adapterName]) {
      throw new Error(`無効なAdapterが指定されました: ${adapterName}`)
    }

    return adapters[adapterName]
  }
}

const adapterFctory = new AdapterFactory()

export default adapterFctory
