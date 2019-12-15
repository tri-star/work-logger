import WlDropDown from '../../../../resources/js/components/form/wl-dropdown'
import { mount } from '@vue/test-utils'

describe('WlDropDown', () => {
  const createDropDown = (name, value, menues, idPrefix = '') => {
    return mount(WlDropDown, {
      propsData: {
        name,
        value,
        menues,
        idPrefix
      }
    })
  }

  describe('初期状態', () => {
    test('初期状態の場合、未選択であること', () => {
      const wrapper = createDropDown('test', null, {
        1: 'a',
        2: 'b',
        3: 'c'
      })
      expect(wrapper.vm.value).toBe(null)
    })

    test('初期状態で指定した要素が選択されていること', () => {
      const expectedValue = 3
      const wrapper = createDropDown('test', expectedValue, {
        1: 'a',
        2: 'b',
        3: 'c'
      })
      expect(wrapper.vm.selectedKey).toBe(expectedValue)
    })

    test('初期状態で指定した要素がリストに存在しない場合、未選択であること', () => {
      const invalidValue = 4
      const wrapper = createDropDown('test', invalidValue, {
        1: 'a',
        2: 'b',
        3: 'c'
      })
      expect(wrapper.vm.selectedKey).toBeNull()
    })
  })
})
