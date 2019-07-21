module.exports = {
    extends: [
        "eslint:recommended",
        "plugin:vue/essential",
        "plugin:prettier/recommended",
        "prettier"
    ],
    plugins: ["vue"],
    env: {
        node: true,
        mocha: true,
        es6: true,
        browser: true
    },
    parserOptions: {
        parser: "babel-eslint",
        sourceType: "module",
    },
    rules: {
        "comma-dangle": ["error", "only-multiline"],
        "generator-star-spacing": "off", // async/awaitを許可するために必要
        "no-console": "off",
        "no-unused-vars": ["error", {"args": "none"}],
        "no-var": "error",
        "object-shorthand": "error",
        "prefer-arrow-callback": "warn",
        "prefer-reflect": "error",
        "prefer-rest-params": "error",
        "prefer-spread": "error",
        "prefer-template": "error",
        "require-yield": "error",
        "semi": ["warn", "never"],
        "template-curly-spacing": "error",
        "yield-star-spacing": "error",
        "sort-imports": "error",
        "prettier/prettier": [
            "warn",
            {
                semi: false,
                tabWidth: 4,
                trailingComma: "none"
            }
        ]
    }
};
