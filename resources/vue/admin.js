import Vue from 'vue'
import axios from 'axios'
import instantiate from './instantiate.js'

/* Components */
import JoLivestreamDropdown from './components/livestream/JoLivestreamDropdown.vue'

/* CSS */
import '../assets/css/admin.scss'
import '../assets/css/editor.scss'

/* Options */
Vue.config.productionTip = false
Vue.prototype.$joel = Object.assign(
  { templatePath: '', assetPath: '' },
  window._joel,
)
axios.defaults.baseURL = '/wp-json/'

/* Instantiate Components */
instantiate.component('JoLivestreamDropdown', JoLivestreamDropdown)
