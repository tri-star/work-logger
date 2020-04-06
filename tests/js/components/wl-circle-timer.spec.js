import WlCircleTimer from '../../../resources/js/components/wl-circle-timer'
import { mount } from '@vue/test-utils'

describe('WlCircleTimer', () => {
  const createTimer = (timeLimit, remainedSeconds) => {
    return mount(WlCircleTimer, {
      propsData: {
        timeLimit,
        remainedSeconds
      }
    })
  }

  describe('percentage', () => {
    const cases = [
      { pattern: '0%: 時間制限0', timeLimit: 0, remainedSeconds: 0, expected: 0 },
      { pattern: '0%: 標準パターン', timeLimit: 10, remainedSeconds: 0, expected: 0 },
      { pattern: '0%: 制限時間がマイナス', timeLimit: -10, remainedSeconds: 0, expected: 0 },
      { pattern: '0%: 残時間がマイナス', timeLimit: 10, remainedSeconds: -10, expected: 0 },

      { pattern: '100%: 標準パターン', timeLimit: 10, remainedSeconds: 10, expected: 100 },
      { pattern: '100%: 残時間が100%以上', timeLimit: 10, remainedSeconds: 11, expected: 100 },

      { pattern: '10%', timeLimit: 10, remainedSeconds: 1, expected: 10 },
      { pattern: '50%', timeLimit: 10, remainedSeconds: 5, expected: 50 },
      { pattern: '66%', timeLimit: 100, remainedSeconds: 66, expected: 66 },
      { pattern: '99%', timeLimit: 100, remainedSeconds: 99, expected: 99 },
    ]

    cases.forEach((c) => {
      test(c.pattern, () => {
        const wrapper = createTimer(c.timeLimit, c.remainedSeconds)
        expect(wrapper.vm.percentage).toBe(c.expected)
      })
    })
  })

  describe('slice1Degree', () => {
    const cases = [
      { pattern: '0%: 時間制限0', timeLimit: 0, remainedSeconds: 0, expected: -90 },
      { pattern: '10%', timeLimit: 10, remainedSeconds: 1, expected: -54 },
      { pattern: '25%', timeLimit: 100, remainedSeconds: 25, expected: 0 },
      { pattern: '50%', timeLimit: 100, remainedSeconds: 50, expected: 90 },
      { pattern: '66%', timeLimit: 100, remainedSeconds: 66, expected: 90 },
      { pattern: '99%', timeLimit: 100, remainedSeconds: 99, expected: 90 },
      { pattern: '100%: 標準パターン', timeLimit: 10, remainedSeconds: 10, expected: 90 },
    ]

    cases.forEach((c) => {
      test(c.pattern, () => {
        const wrapper = createTimer(c.timeLimit, c.remainedSeconds)
        expect(wrapper.vm.slice1Degree).toBe(c.expected)
      })
    })
  })

  describe('slice2Degree', () => {
    const cases = [
      { pattern: '0%: 時間制限0', timeLimit: 0, remainedSeconds: 0, expected: 0 },
      { pattern: '10%', timeLimit: 10, remainedSeconds: 1, expected: 0 },
      { pattern: '25%', timeLimit: 100, remainedSeconds: 25, expected: 0 },
      { pattern: '50%', timeLimit: 100, remainedSeconds: 50, expected: 0 },
      { pattern: '66%', timeLimit: 100, remainedSeconds: 60, expected: 216 },
      { pattern: '99%', timeLimit: 100, remainedSeconds: 99, expected: 356.4 },
      { pattern: '100%: 残時間が100%以上', timeLimit: 10, remainedSeconds: 11, expected: 360 },
    ]

    cases.forEach((c) => {
      test(c.pattern, () => {
        const wrapper = createTimer(c.timeLimit, c.remainedSeconds)
        expect(wrapper.vm.slice2Degree).toBe(c.expected)
      })
    })
  })

  describe('slice2Color', () => {
    const blankColor = '#ddd'
    const filledColor = '#f00'
    const cases = [
      { pattern: '0%', timeLimit: 0, remainedSeconds: 0, expected: blankColor },
      { pattern: '10%', timeLimit: 10, remainedSeconds: 1, expected: blankColor },
      { pattern: '25%', timeLimit: 100, remainedSeconds: 25, expected: blankColor },
      { pattern: '50%', timeLimit: 100, remainedSeconds: 50, expected: blankColor },
      { pattern: '66%', timeLimit: 100, remainedSeconds: 60, expected: filledColor },
      { pattern: '99%', timeLimit: 100, remainedSeconds: 99, expected: filledColor },
      { pattern: '100%', timeLimit: 10, remainedSeconds: 10, expected: filledColor },
    ]

    cases.forEach((c) => {
      test(c.pattern, () => {
        const wrapper = createTimer(c.timeLimit, c.remainedSeconds)
        expect(wrapper.vm.slice2Color).toBe(c.expected)
      })
    })
  })
})
