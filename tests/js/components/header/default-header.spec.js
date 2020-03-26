import DefaultHeader from '@/components/header/default-header.vue'
import { mount } from '@vue/test-utils'

describe('DefaultHeader', () => {

  test('ローカル環境では専用のスタイル指定が行われること', () => {
    
    const wrapper = mount(DefaultHeader, {
      propsData: {
        env: 'local'
      }
    })

    expect(wrapper.find('header').classes()).toContain('dev')
  })

  test('本番環境では開発用のスタイルが含まれないこと', () => {
    
    const wrapper = mount(DefaultHeader, {
      propsData: {
        env: 'production'
      }
    })

    expect(wrapper.find('header').classes()).not.toContain('dev')
  })

  test('ローカル環境では"(LOCAL)"が含まれること', () => {
    
    const wrapper = mount(DefaultHeader, {
      propsData: {
        env: 'local'
      }
    })

    expect(wrapper.find('h1').text()).toMatch(/\(LOCAL\)/)
  })

  test('本番環境では"(LOCAL)"が含まれないこと', () => {
    
    const wrapper = mount(DefaultHeader, {
      propsData: {
        env: 'production'
      }
    })

    expect(wrapper.find('h1').text()).not.toMatch(/\(LOCAL\)/)
  })

})
