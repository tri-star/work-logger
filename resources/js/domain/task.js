export default class Task {
  static get STATE_NONE () {
    return 0
  }
  static get STATE_IN_PROGRESS () {
    return 10
  }
  static get STATE_DONE () {
    return 20
  }
  static get STATE_PAUSE () {
    return 30
  }
  static get STATE_INVALID () {
    return 40
  }

  static getStatusNames () {
    return {
      [this.STATE_NONE]: '未着手',
      [this.STATE_IN_PROGRESS]: '作業中',
      [this.STATE_DONE]: '完了',
      [this.STATE_PAUSE]: '保留',
      [this.STATE_INVALID]: '無効'
    }
  }

  static getStatusName (status) {
    const names = Task.getStatusNames()
    return names[status] ? names[status] : ''
  }
}
