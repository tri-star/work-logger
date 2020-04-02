import TaskSelect from '@/pages/dashboard/task-select'
import flushPromises from 'flush-promises'
import { mount } from '@vue/test-utils'

describe('TaskSelect', () => {
  const createTaskSelect = (loadSuggestions = null, loadTaskSuggestions = null) => {
    if (!loadSuggestions) {
      loadSuggestions = keyword => []
    }
    if (!loadTaskSuggestions) {
      loadTaskSuggestions = keyword => []
    }
    return mount(TaskSelect, {
      propsData: {
        loadSuggestions,
        loadTaskSuggestions
      }
    })
  }

  /**
   * 指定した名前のプロジェクトを選択する
   */
  const selectProject = async (wrapper, projectName) => {
    wrapper.find('[data-test="project-input"]').trigger('click')
    await flushPromises()
    wrapper.find('[data-test="project-input"] input[type="text"]').element.value = projectName
    wrapper.find('[data-test="project-input"] input[type="text"]').trigger('input')
    await flushPromises()

    const items = wrapper.findAll('[data-test="project-input"] li')
    items.wrappers.forEach((item) => {
      if (item.text() === projectName) {
        item.trigger('click')
      }
    })
    await flushPromises()
  }

  /**
   * 指定した名前のタスクを選択する
   * (プロジェクトは選択済の想定)
   */
  const selectTask = async (wrapper, taskName) => {
    wrapper.find('[data-test="task-input"]').trigger('click')
    await flushPromises()
    wrapper.find('[data-test="task-input"] input[type="text"]').element.value = taskName
    wrapper.find('[data-test="task-input"] input[type="text"]').trigger('input')
    await flushPromises()

    const items = wrapper.findAll('[data-test="task-input"] li')
    items.wrappers.forEach((item) => {
      if (item.text() === taskName) {
        item.trigger('click')
      }
    })
    await flushPromises()
  }

  describe('初期状態', () => {
    test('プロジェクト名以外は入力不可になっていること', async () => {
      const wrapper = createTaskSelect()
      expect(wrapper.find('[data-test="project-input"]').exists()).toBeTruthy()
      expect(wrapper.find('[data-test="project-input"]').props()).not.toMatchObject({ disabled: true })
      expect(wrapper.find('[data-test="task-input"]').props()).toMatchObject({ disabled: true })
      expect(wrapper.find('[data-test="result-hours"]').element.disabled).toBeTruthy()
      expect(wrapper.find('[data-test="result-memo"]').element.disabled).toBeTruthy()
      expect(wrapper.find('[data-test="register-button"]').element.disabled).toBeTruthy()
    })

    // プロジェクト名を渡した場合、タスク名まで入力できる
    test('プロジェクト名を設定した場合、タスク名まで入力できる', async () => {
      const projectSuggestFunction = keyword => [{ id: 1, name: 'Project1' }]
      const wrapper = createTaskSelect(projectSuggestFunction)

      await selectProject(wrapper, 'Project1')

      expect(wrapper.find('[data-test="project-input"] .selected-text').text()).toBe('Project1')
      expect(wrapper.find('[data-test="task-input"]').props()).not.toMatchObject({ disabled: true })
    })

    // タスク名まで渡した場合、実績の入力が出来る
    test('タスク名まで設定した場合、実績の入力が出来る', async () => {
      const projectSuggestFunction = keyword => [{ id: 1, name: 'Project1' }]
      const taskSuggestFunction = keyword => [{ id: 1, name: 'Task1' }]
      const wrapper = createTaskSelect(projectSuggestFunction, taskSuggestFunction)

      await selectProject(wrapper, 'Project1')
      await selectTask(wrapper, 'Task1')

      expect(wrapper.find('[data-test="task-input"] .selected-text').text()).toBe('Task1')
      expect(wrapper.find('[data-test="result-hours"]').element.disabled).toBeFalsy()
      expect(wrapper.find('[data-test="result-memo"]').element.disabled).toBeFalsy()
    })
  })

  describe('タスク選択欄', () => {
    // タスクを選択解除しても、プロジェクトには影響しない
    // タスクを選択解除すると、実績は登録できなくなる
  })

  describe('実績入力欄', () => {
    // 作業時間は未入力不可
    // メモ欄も未入力不可
    //
  })
})
