// import Vue from 'vue'
import axios from 'axios'
import instantiate from './instantiate.js'

/* Components */
import JoBoogleMain from './components/boogle/JoBoogleMain.vue'
import JoLivestreamDropdown from './components/livestream/JoLivestreamDropdown.vue'
import JoLivestreamMeta from './components/livestream/JoLivestreamMeta.vue'
import JoLivestreamMessage from './components/livestream/JoLivestreamMessage.vue'
import JoMedialist from './components/medialist/JoMedialist.vue'
import JoCookieConsent from './components/cookieConsent/JoCookieConsent.vue'
import JoEvents from './components/events/JoEvents.vue'
import JoSlider from './components/slider/JoSlider.vue'
import JoPaginationWrapper from './components/pagination/JoPaginationWrapper.vue'

/* Util */
import toggle from './components/utils/toggle.js'
import dropdown from './components/utils/dropdown.js'

/* CSS */
import '../styles/tailwind.css'
import '../styles/main.scss'

/* Options */
// Vue.config.productionTip = false
// Vue.prototype.$joel = Object.assign(
//   { templatePath: '', assetPath: '' },
//   window._joel,
// )
axios.defaults.baseURL = '/wp-json/'

/* Instantiate Components */
instantiate.component('JoBoogleMain', JoBoogleMain)
instantiate.component('JoLivestreamDropdown', JoLivestreamDropdown)
instantiate.component('JoLivestreamMeta', JoLivestreamMeta)
instantiate.component('JoLivestreamMessage', JoLivestreamMessage)
instantiate.component('JoMedialist', JoMedialist)
instantiate.component('JoCookieConsent', JoCookieConsent)
instantiate.component('JoEvents', JoEvents)
instantiate.component('JoSlider', JoSlider)
instantiate.component('JoPaginationWrapper', JoPaginationWrapper)

/* Instantiate Utils */
instantiate.util('toggle', toggle)
instantiate.util('dropdown', dropdown)
