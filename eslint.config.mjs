import pluginVue from 'eslint-plugin-vue'
import js from '@eslint/js'

const INLINE_ELEMENTS = [
  'a', 'abbr', 'audio', 'b', 'bdi', 'bdo', 'canvas', 'cite', 'code', 'data',
  'del', 'dfn', 'em', 'i', 'iframe', 'ins', 'kbd', 'label', 'map', 'mark',
  'noscript', 'object', 'output', 'picture', 'q', 'ruby', 's', 'samp',
  'small', 'span', 'strong', 'sub', 'sup', 'svg', 'time', 'u', 'var',
  'video', 'wbr',
]

export default [
  js.configs.recommended,
  ...pluginVue.configs['flat/recommended'],
  {
    languageOptions: {
      globals: {
        window: 'readonly',
        document: 'readonly',
        console: 'readonly',
        setTimeout: 'readonly',
        clearTimeout: 'readonly',
        setInterval: 'readonly',
        clearInterval: 'readonly',
        fetch: 'readonly',
        URL: 'readonly',
        URLSearchParams: 'readonly',
        navigator: 'readonly',
        FormData: 'readonly',
        HTMLElement: 'readonly',
        Event: 'readonly',
        MutationObserver: 'readonly',
        IntersectionObserver: 'readonly',
        requestAnimationFrame: 'readonly',
        cancelAnimationFrame: 'readonly',
      },
    },
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
  },
  {
    ignores: ['dist/', 'node_modules/'],
  },
]
