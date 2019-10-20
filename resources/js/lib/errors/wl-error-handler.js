import AuthError from './auth-error'

export default class WlErrorHandler {
  static fromVueError (err, vm, info) {
    const errorDetail = {
      err,
      vm,
      info
    }
    WlErrorHandler.handle(err, errorDetail)
  }

  static fromGeneralError (error) {
    WlErrorHandler.handle(error)
  }

  static fromPromiseError (error) {
    WlErrorHandler.handle(error.reason)
  }

  static handle (error, errorDetail = {}) {
    if (error instanceof AuthError) {
      return error.handle()
    }

    // ハンドルされていない例外
    console.log('UnhandledError: ', error, errorDetail)
  }
}
