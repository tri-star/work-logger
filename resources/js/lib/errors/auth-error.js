export default class AuthError extends Error {
    constructor(...args) {
        super(...args)

        Reflect.defineProperty(this, "name", {
            configurable: true,
            enumerable: false,
            value: this.constructor.name,
            writable: true
        })

        if (Error.captureStackTrace) {
            Error.captureStackTrace(this, AuthError)
        }
    }

    /**
     * エラー処理本体
     */
    handle() {
        window.location.href = "/login"
    }
}
