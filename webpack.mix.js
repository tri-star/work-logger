const mix = require("laravel-mix")
const path = require("path")

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .extract([
        "axios",
        "jquery",
        "lodash",
        "popper.js",
        "vue",
        "vue-router",
        "vuex"
    ])
    .sass("resources/sass/app.scss", "public/css")
    .version()

mix.webpackConfig({
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000,
        ignored: ["node_modules"]
    },
    devtool: "inline-source-map",
    resolve: {
        alias: {
            "@script": path.join(__dirname, "resources/js")
        }
    }
})
