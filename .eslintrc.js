const INLINE_ELEMENTS = require('eslint-plugin-vue/lib/utils/inline-non-void-elements.json')

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
    'no-unused-expressions': 'warn',
    'vue/no-unused-components': 'warn',
    'vue/singleline-html-element-content-newline': ['warn', {
      'ignores': ['pre', 'textarea', 'button', 'p', 'h1', 'h2', 'h3', 'h4', 'option', 'router-link', ...INLINE_ELEMENTS],
    }],
    'vue/multiline-html-element-content-newline': 'off',
    'vue/v-slot-style': ['warn', 'longform'],
    'vue/max-attributes-per-line': ['warn', {
      'singleline': 5,
      'multiline': 1,
    }],
    'vue/no-multiple-template-root': 'off',
    'vue/multi-word-component-names': 'off',
  },
  "ignorePatterns": [
    "dist/",
    "node_modules/",
  ],
}
