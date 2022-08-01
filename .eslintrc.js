module.exports = {
  root: true,
  env: {
    node: true,
    es6: true,
  },
  parserOptions: {
    ecmaVersion: 2018,
  },
  extends: [
    'plugin:vue/vue3-recommended',
  ],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'comma-dangle': ['warn', 'always-multiline'],
    'no-unused-vars': 'warn',
  },
  "ignorePatterns": [
    "dist/",
    "node_modules/",
  ],
}
