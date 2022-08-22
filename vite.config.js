import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  build: {
    target: ['es2015'],
    manifest: true,
    sourcemap: true,
    rollupOptions: {
      input: {
        'vue-main': 'src-vue/main.js',
        'vue-admin': 'src-vue/admin.js',
        'vanilla-main': 'src-vanilla/main.js', // TODO: refactor legacy depts
        'vanilla-admin': 'src-vanilla/admin.js',
      },
    },
  },
  server: {
    proxy: {
      '^/(?!src-|fonts|images|styles|node_modules|@).*': {
        target: 'http://localhost:8080',
        xfwd: true,
      },
    },
  },
})
