import Vue from 'vue'
import axios from 'axios'
import instantiate from './instantiate.js'

/* Components */
import JoLivestreamDropdown from './components/livestream/JoLivestreamDropdown.vue'
import JoLivestreamMeta from './components/livestream/JoLivestreamMeta.vue'
import JoMedialist from './components/medialist/JoMedialist.vue'
import JoCookieConsent from './components/cookie-consent/JoCookieConsent.vue'

/* Util */
import toggle from './components/utils/toggle.js'
import dropdown from './components/utils/dropdown.js'

/* CSS */
import '../assets/css/main.scss'

console.log('main.js')

/* Options */
Vue.config.productionTip = false
Vue.prototype.$joel = Object.assign(
  { templatePath: '', assetPath: '' },
  window._joel,
)
axios.defaults.baseURL = '/wp-json/'

/* Instantiate Components */
instantiate.component('JoLivestreamDropdown', JoLivestreamDropdown)
instantiate.component('JoLivestreamMeta', JoLivestreamMeta)
instantiate.component('JoMedialist', JoMedialist)
instantiate.component('JoCookieConsent', JoCookieConsent)

/* Instantiate Utils */
instantiate.util('toggle', toggle)
instantiate.util('dropdown', dropdown)
