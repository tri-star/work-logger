import WlTabBar from '@/components/wl-tab-bar.vue'
import { mount } from '@vue/test-utils'

describe('WlTabBar', () => {
  const createTabBar = (initialSelected = '', items = {}) => {
    return mount(WlTabBar, {
      propsData: {
        initialSelected,
        items
      }
    })
  }

  describe('初期状態', () => {
    test('初期状態で指定したタブが選択されていること', async () => {
      const expectedTab = 'test1'
      const items = {
        test1: {
          tabKey: 'test1',
          label: 'テスト1'
        },
        test2: {
          tabKey: 'test2',
          label: 'テスト2'
        }
      }
      const wrapper = createTabBar(expectedTab, items)
      await wrapper.vm.$nextTick()
      expect(wrapper.find('.tab-item.active').element.innerHTML).toContain('テスト1')
    })

    test('初期状態で指定したタブが通知されること', async () => {
      const expectedTab = 'test1'
      const items = {
        test1: {
          tabKey: 'test1',
          label: 'テスト1'
        },
        test2: {
          tabKey: 'test2',
          label: 'テスト2'
        }
      }
      const wrapper = createTabBar(expectedTab, items)
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted('tabChange')[0][0]).toEqual(expectedTab)
    })
  })

  describe('基本動作', () => {
    test('タブを選択すると選択したタブがアクティブになること', async () => {
      const initialTab = 'test1'
      const items = {
        test1: {
          tabKey: 'test1',
          label: 'テスト1'
        },
        test2: {
          tabKey: 'test2',
          label: 'テスト2'
        }
      }
      const wrapper = createTabBar(initialTab, items)
      await wrapper.vm.$nextTick()
      wrapper.find('.tab-item[tab-key="test2"]').trigger('click')
      await wrapper.vm.$nextTick()
      expect(wrapper.find('.tab-item.active').element.innerHTML).toContain('テスト2')
    })

    test('タブを選択すると選択したタブのキーが通知されること', async () => {
      const initialTab = 'test1'
      const items = {
        test1: {
          tabKey: 'test1',
          label: 'テスト1'
        },
        test2: {
          tabKey: 'test2',
          label: 'テスト2'
        }
      }
      const wrapper = createTabBar(initialTab, items)
      await wrapper.vm.$nextTick()
      wrapper.find('.tab-item[tab-key="test2"]').trigger('click')
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted('tabChange')[1][0]).toEqual('test2')
    })
  })
})
