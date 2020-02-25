import WlTabItem from '@/components/wl-tab-item.vue'
import { mount } from '@vue/test-utils'

describe('WlTabItem', () => {
  const createTabItem = (tabKey = 'test', label = 'テスト', isActive = false) => {
    return mount(WlTabItem, {
      propsData: {
        tabKey,
        label,
        isActive
      }
    })
  }

  describe('初期状態', () => {
    test('指定されたラベルが表示されていること', () => {
      const expectedLabel = 'ラベル表示テスト'
      const wrapper = createTabItem('test', expectedLabel)
      expect(wrapper.find('.tab-item').element.innerHTML).toContain(expectedLabel)
    })

    test('isActiveにtrueを渡すと選択状態になること', () => {
      const wrapper = createTabItem('test', 'テスト', true)
      expect(wrapper.find('.tab-item').classes()).toContain('active')
    })
  })

  describe('基本動作', () => {
    test('押下すると自分のtabKeyを通知すること', () => {
      const expectedKey = 'Expected'
      const wrapper = createTabItem(expectedKey)
      wrapper.find('.tab-item').trigger('click')
      expect(wrapper.emitted('selected')[0][0]).toEqual(expectedKey)
    })
  })
})
