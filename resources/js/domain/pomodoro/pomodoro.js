
export class PomodoroController {
  constructor (initialState = PomodoroState.STATE_INITIAL) {
    this.currentState = {
      state: initialState,
      minutes: this._getMinutes(initialState),
    }
    this.prevState = null
    this.workMinutes = 25
    this.breakMinutes = 5
  }

  /**
   * @return [PomodoroState]
   */
  progressState () {
    this.prevState = this.currentState
    const newState = this._getNextState(this.currentState.state)
    const minutes = newState === PomodoroState.STATE_WORK ? this.workMinutes : this.breakMinutes
    this.currentState = new PomodoroState(newState, minutes)
    return this.currentState
  }

  restoreState () {
    if (this.prevState === null) {
      throw new Error('無効なタイミングでrestoreStateが呼ばれました')
    }
    this.currentState = this.prevState
    this.prevState = null
  }

  getCurrentState () {
    return this.currentState
  }

  reset () {
    const initialState = PomodoroState.STATE_INITIAL
    this.currentState = {
      state: initialState,
      minutes: this._getMinutes(initialState),
    }
    this.prevState = null
  }

  _getNextState (state) {
    switch (state) {
      case PomodoroState.STATE_INITIAL:
      case PomodoroState.STATE_BREAK:
        return PomodoroState.STATE_WORK

      case PomodoroState.STATE_WORK:
        return PomodoroState.STATE_BREAK
    }
    throw new Error(`無効な状態が指定されました: ${state}`)
  }

  _getMinutes (state) {
    if (state === PomodoroState.STATE_WORK) {
      return this.workMinutes
    }
    if (state === PomodoroState.STATE_BREAK) {
      return this.breakMinutes
    }
    return 0
  }
}

export class PomodoroState {
  /**
   * 初期状態
   */
  static get STATE_INITIAL () { return 0 }

  /**
   * 仕事中
   */
  static get STATE_WORK () { return 1 }

  /**
   * 休憩中
   */
  static get STATE_BREAK () { return 2 }

  constructor (state, minutes) {
    this.state = state
    this.minutes = minutes
  }
}
