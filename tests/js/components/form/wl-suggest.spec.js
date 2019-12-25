import WlSuggest from '../../../../resources/js/components/form/wl-suggest'
import { mount } from '@vue/test-utils'

describe('WlSuggest', () => {
  const createSuggest = (value, text, suggestCallback) => {
    if (!suggestCallback) {
      suggestCallback = async () => {
        return [
          { id: 1, name: 'Test1' },
          { id: 2, name: 'Test2' },
          { id: 3, name: 'Test3' },
          { id: 4, name: 'Test4' },
        ]
      }
    }

    return mount(WlSuggest, {
      propsData: {
        value,
        text,
        suggestCallback
      }
    })
  }

  describe('初期表示', () => {
    test('初期データを渡した場合、初期データが表示されること', () => {
      const wrapper = createSuggest(1, 'test')
      expect(wrapper.vm.innerValue).toBe(1)
      expect(wrapper.vm.innerName).toBe('test')
    })
  })

  describe('動作', () => {
    test('テキストを入力すると、メニューが表示される', async () => {
      const wrapper = createSuggest(1, 'test')
      expect(wrapper.vm.showList).toBeFalsy()
      const inputNode = wrapper.find('input[type="text"]')
      inputNode.element.value = 'a'
      inputNode.trigger('input')
      await wrapper.vm.$nextTick

      expect(wrapper.vm.showList).toBeTruthy()
    })

    test('メニュー項目を選択すると、選択内容が通知される', async () => {
      const wrapper = createSuggest(1, 'test')
      const inputNode = wrapper.find('input[type="text"]')
      inputNode.element.value = 'a'
      inputNode.trigger('input')
      await wrapper.vm.$nextTick
      await wrapper.vm.$nextTick // 2回分待たないとul liに項目がロードされない

      const menuItem = wrapper.find('ul > li')
      menuItem.trigger('click')
      // 実際のイベントのペイロードはオブジェクトだが、vue-test-utilのwrapperは
      // 配列で括って返す模様
      expect(wrapper.emitted().selected[0]).toEqual([{
        value: 1,
        text: 'Test1'
      }])
    })
  })
})
