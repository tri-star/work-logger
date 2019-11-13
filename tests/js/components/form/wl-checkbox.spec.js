import WlCheckBox from '../../../../resources/js/components/form/wl-checkbox'
import { mount } from '@vue/test-utils'

describe('WlCheckBox', () => {
  const createCheckBox = (id, value, label = 'test', checkedItems = []) => {
    return mount(WlCheckBox, {
      propsData: {
        id,
        value,
        label,
        checkedItems,
      }
    })
  }

  describe('初期データ', () => {
    test('チェック済のものが存在する場合、チェック状態になること', () => {
      const wrapper = createCheckBox('test', '1', 'test', ['1', '2'])
      expect(wrapper.find('input[type="checkbox"]').element.checked).toBeTruthy()
    })

    test('チェック済のものが存在しない場合、チェック状態にならないこと', () => {
      const wrapper = createCheckBox('test', '1', 'test', ['2'])
      expect(wrapper.find('input[type="checkbox"]').element.checked).toBeFalsy()
    })

    test('初期データが空の配列でもエラーにならないこと', () => {
      const wrapper = createCheckBox('test', '1', 'test', [])
      expect(wrapper.find('input[type="checkbox"]').element.checked).toBeFalsy()
    })
  })

  describe('チェック時の動作', () => {
    test('チェックするとイベントが送信されること', () => {
      const wrapper = createCheckBox('test', '1', 'test', [])

      wrapper.find('label').element.click()
      expect(wrapper.emitted('change')[0]).toStrictEqual([['1']])
    })
  })
})
