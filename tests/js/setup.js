// Jestの場合はJest内部で実行されるため不要
// require('jsdom-global')()

global.expect = require('expect')
global.axios = require('axios')
global.Vue = require('vue')
global._ = require('lodash')
global.bus = new Vue() // eslint-disable-line no-undef
