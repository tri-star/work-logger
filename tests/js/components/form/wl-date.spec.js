import MockDate from 'mockdate'
import WlDate from '../../../../resources/js/components/form/wl-date'
import { mount } from '@vue/test-utils'

function verifyConsoleError (closure, callback) {
  const mock = jest.spyOn(console, 'error')

  closure()

  callback(mock.mock.calls)

  mock.mockReset()
  mock.mockRestore()
}

function createWlDate (value) {
  return mount(WlDate, {
    propsData: {
      value
    }
  })
}

describe('WlDate', () => {
  test('valueは必須項目であること', () => {
    verifyConsoleError(() => {
      mount(WlDate)
    }, (calls) => {
      expect(calls[0][0]).toMatch(/Missing required prop/)
    })
  })

  test('valueが表示と連動していること', async () => {
    const wrapper = createWlDate('2019-01-01')
    wrapper.setValue('2019-01-02')
    expect(wrapper.element.value).toBe('2019-01-02')
  })

  it.each`
  date             | expected
  ${'2019-01-01'}  | ${'2019-01-01'}
  ${'2019-01-02 '} | ${'2019-01-02 '}
  ${' 2019-01-03'} | ${' 2019-01-03'}
  ${'2019/01/04'}  | ${'2019/01/04'}
  ${'20190105'}    | ${'20190105'}
  ${'2019-01-05 00:00:00'} | ${'2019-01-05 00:00:00'}
  ${''}    | ${''}
    `(`"$date" は "$expected" として表示されること`, ({ date, expected }) => {
  const wrapper = createWlDate(date)
  expect(wrapper.find('input').element.value).toBe(expected)
})

  it.each`
  date            | expected
  ${'2019-10-20'} | ${'2019-01-01'}
  ${''}           | ${'2019-01-01'}
  ${' '}          | ${'2019-01-01'}
  ${'abc'}        | ${'2019-01-01'}
  ${'2019-02-01'} | ${'2019-01-01'}
  ${'2019-01-60'} | ${'2019-01-01'}
  `('"$date" に対しスペースキーを押下すると "$expected" になること', ({ date, expected }) => {
  const wrapper = createWlDate(date)

  MockDate.set('2019-01-01')
  wrapper.trigger('keydown.space')
  expect(wrapper.vm.innerValue).toMatch(expected)
  MockDate.reset()
})

  it.each`
  date            | expected
  ${'2019-01-01'} | ${'2019-01-02'}
  ${'2019-02-28'} | ${'2019-03-01'}
  ${'2019-02-29'} | ${''}
  ${'2020-02-29'} | ${'2020-03-01'}
  ${'2019-03-32'} | ${''}
  ${'2019/01/01'} | ${''}
  ${'20190101'}   | ${''}
  ${'2019'}       | ${''}
  `('"$date" に対し上キーを押下すると "$expected" になること', ({ date, expected }) => {
  const wrapper = createWlDate(date)
  wrapper.trigger('keydown.up')
  expect(wrapper.vm.innerValue).toMatch(expected)
})

  it.each`
  date            | expected
  ${'2019-01-01'} | ${'2018-12-31'}
  ${'2019-03-01'} | ${'2019-02-28'}
  ${'2019-02-29'} | ${''}
  ${'2020-03-01'} | ${'2020-02-29'}
  ${'2019-03-32'} | ${''}
  ${'2019/01/01'} | ${''}
  ${'20190101'}   | ${''}
  ${'2019'}       | ${''}
  `('"$date" に対し下キーを押下すると "$expected" になること', ({ date, expected }) => {
  const wrapper = createWlDate(date)
  wrapper.trigger('keydown.down')
  expect(wrapper.vm.innerValue).toMatch(expected)
})
})
