import Vue from 'vue'
import axios from 'axios'
import instantiate from './instantiate.js'

/* Components */
import JoLivestreamDropdown from './components/livestream/JoLivestreamDropdown.vue'
import JoLivestreamMeta from './components/livestream/JoLivestreamMeta.vue'

/* CSS */
import '../assets/css/main.scss'

/* Options */
Vue.config.productionTip = false
Vue.prototype.$joel = Object.assign(
  { templatePath: '', assetPath: '' },
  window._joel,
)
axios.defaults.baseURL = '/wp-json/'

/* Instantiate Components */
instantiate.add('JoLivestreamDropdown', JoLivestreamDropdown)
instantiate.add('JoLivestreamMeta', JoLivestreamMeta)
