import { PomodoroController, PomodoroState } from '../../../../resources/js/domain/pomodoro/pomodoro'

describe('PomodoroController', () => {
  test('初期状態', () => {
    const controller = new PomodoroController()
    const state = controller.getCurrentState()

    expect(state.state).toBe(PomodoroState.STATE_INITIAL)
    expect(state.minutes).toBe(0)
  })

  describe('状態遷移', () => {
    test('初期状態 -> 仕事中', () => {
      const controller = new PomodoroController()
      const state = controller.progressState()

      expect(state.state).toBe(PomodoroState.STATE_WORK)
      expect(state.minutes).toBe(25)
    })

    test('仕事中 -> 休憩中', () => {
      const controller = new PomodoroController(PomodoroState.STATE_WORK)
      const state = controller.progressState()

      expect(state.state).toBe(PomodoroState.STATE_BREAK)
      expect(state.minutes).toBe(5)
    })

    test('休憩中 -> 仕事中', () => {
      const controller = new PomodoroController(PomodoroState.STATE_BREAK)
      const state = controller.progressState()

      expect(state.state).toBe(PomodoroState.STATE_WORK)
      expect(state.minutes).toBe(25)
    })
  })

  describe('状態遷移の中止', () => {
    test('初期状態 -> 仕事中 -> 中止して再開: 仕事中状態から再開出来ること', () => {
      const controller = new PomodoroController()
      controller.progressState()

      controller.restoreState()

      let state = controller.getCurrentState()
      expect(state.state).toBe(PomodoroState.STATE_INITIAL)

      state = controller.progressState()
      expect(state.state).toBe(PomodoroState.STATE_WORK)
    })
  })
})
