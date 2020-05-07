const { createProxyMiddleware } = require('http-proxy-middleware')

const PROXY_OPTIONS = {
  target: 'http://localhost:8080/',
  ws: false,
  changeOrigin: true,
  xfwd: true,
}

module.exports = {
  publicPath: '/wp-content/themes/joel/dist',
  filenameHashing: false,
  runtimeCompiler: true,
  pages: {
    main: 'resources/vue/main.js',
    vanilla: 'resources/assets/js/main.js',
    // admin: 'resources/vue/admin.js',
    // 'vanilla-admin': 'resources/assets/js/admin.js',
  },
  devServer: {
    index: '',
    serveIndex: false,
    historyApiFallback: false,
    proxy: {
      '/': PROXY_OPTIONS,
    },
    after: app => app.use('/', createProxyMiddleware(PROXY_OPTIONS)),
  },
}
