/** @format */

// https://eslint.org/docs/user-guide/configuring

module.exports = {
    root: true,
    parser: 'babel-eslint',
    parserOptions: {
        sourceType: 'module',
    },
    env: {
        browser: true,
    },
    // https://github.com/standard/standard/blob/master/docs/RULES-en.md
    extends: [
        // add more generic rulesets here, such as:
        // 'eslint:recommended',
        // 'standard',
        'plugin:vue/recommended',
    ],
    // required to lint *.vue files
    plugins: ['html'],
    // add your custom rules here
    rules: {
        // allow async-await
        'generator-star-spacing': 'off',
        // allow debugger during development
        'no-debugger': 'production' === process.env.NODE_ENV ? 'error' : 'off',
        'no-console': 'production' === process.env.NODE_ENV ? 'error' : 'off',
    },
};
