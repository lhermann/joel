import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  build: {
    rollupOptions: {
      input: {
        'vue-main': 'src-vue/main.js',
        'vue-admin': 'src-vue/admin.js',
        'vanilla-main': 'src-vanilla/main.js',
        'vanilla-admin': 'src-vanilla/admin.js'
      },
    },
  },
})
