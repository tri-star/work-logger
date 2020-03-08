import { RouterLinkStub, shallowMount } from '@vue/test-utils'
import ProjectList from '@/pages/dashboard/project-list'

describe('ProjectList', () => {
  const createProjectList = (projects) => {
    return shallowMount(ProjectList, {
      propsData: {
        projects
      },
      stubs: {
        RouterLink: RouterLinkStub
      }
    })
  }

  const createProject = (params = {}) => {
    const initialId = Math.floor(Math.random() * 1000)
    return {
      id: initialId,
      project_name: `test_${initialId}`,
      completed_task_count: 0,
      task_count: 1,
      total_result_hours: 2,
      total_estimated_hours: 3,
      ...params
    }
  }

  describe('一覧全体', () => {
    test('0件の場合: データ行が表示されないこと', async () => {
      const wrapper = createProjectList({})
      expect(wrapper.find('tr[data-test="data-row"]').exists()).toBeFalsy()
    })

    test('1件渡した場合、1行表示されること', async () => {
      const project = createProject()
      const wrapper = createProjectList({
        [project.id]: project
      })
      expect(wrapper.findAll('tr[data-test="data-row"]').length).toBe(1)
    })

    test('2件渡した場合、2行表示されること', async () => {
      const projects = [createProject(), createProject()]
      const wrapper = createProjectList({
        [projects[0].id]: projects[0],
        [projects[1].id]: projects[1]
      })
      expect(wrapper.findAll('tr[data-test="data-row"]').length).toBe(2)
    })
  })

  describe('行の表示', () => {
    test('1列目:プロジェクト名', async () => {
      const project = createProject()
      const wrapper = createProjectList({
        [project.id]: project
      })
      expect(wrapper.find('td[data-test="col-project-name"]').text()).toBe(project.project_name)
    })

    test('2列目:タスク統計', async () => {
      const project = createProject()
      const wrapper = createProjectList({
        [project.id]: project
      })
      const expectedText = `${project.completed_task_count} / ${project.task_count}`
      expect(wrapper.find('td[data-test="col-count-stat"]').text()).toBe(expectedText)
    })

    test('3列目:総作業時間', async () => {
      const totalResultHours = 3.5
      const project = createProject({
        total_result_hours: totalResultHours
      })
      const wrapper = createProjectList({
        [project.id]: project
      })
      expect(wrapper.find('td[data-test="col-total-result-hours"]').text()).toBe(`${totalResultHours}h`)
    })

    test('4列目:総見積時間', async () => {
      const totalEstimatedHours = 5.5
      const project = createProject({
        total_estimated_hours: totalEstimatedHours
      })
      const wrapper = createProjectList({
        [project.id]: project
      })
      expect(wrapper.find('td[data-test="col-total-estimate-hours"]').text()).toBe(`${totalEstimatedHours}h`)
    })
  })
})
