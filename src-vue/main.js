// import Vue from 'vue'
import axios from 'axios'
import instantiate from './instantiate.js'

/* Components */
import JoBoogleMain from './components/boogle/JoBoogleMain.vue'
import JoSearchMain from './components/search/JoSearchMain.vue'
import JoLivestreamDropdown from './components/livestream/JoLivestreamDropdown.vue'
import JoLivestreamMeta from './components/livestream/JoLivestreamMeta.vue'
import JoLivestreamMessage from './components/livestream/JoLivestreamMessage.vue'
import JoMedialist from './components/medialist/JoMedialist.vue'
import JoCookieConsent from './components/cookieConsent/JoCookieConsent.vue'
import JoEvents from './components/events/JoEvents.vue'
import JoSlider from './components/slider/JoSlider.vue'
import JoPaginationWrapper from './components/pagination/JoPaginationWrapper.vue'
import JoStudyCenter from './components/study-center/JoStudyCenter.vue'
import JoHeroSearch from './components/home/JoHeroSearch.vue'

/* Util */
import toggle from './components/utils/toggle.js'
import dropdown from './components/utils/dropdown.js'
import cardRowScroll from './components/utils/cardRowScroll.js'

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
instantiate.component('JoSearchMain', JoSearchMain)
instantiate.component('JoLivestreamDropdown', JoLivestreamDropdown)
instantiate.component('JoLivestreamMeta', JoLivestreamMeta)
instantiate.component('JoLivestreamMessage', JoLivestreamMessage)
instantiate.component('JoMedialist', JoMedialist)
instantiate.component('JoCookieConsent', JoCookieConsent)
instantiate.component('JoEvents', JoEvents)
instantiate.component('JoSlider', JoSlider)
instantiate.component('JoPaginationWrapper', JoPaginationWrapper)
instantiate.component('JoStudyCenter', JoStudyCenter)
instantiate.component('JoHeroSearch', JoHeroSearch)

/* Instantiate Utils */
instantiate.util('toggle', toggle)
instantiate.util('dropdown', dropdown)
instantiate.util('cardRowScroll', cardRowScroll)

/* Timestamp seeking */
function seekYouTube (seconds) {
  const iframe = document.querySelector('#head iframe')
  if (!iframe) return
  iframe.contentWindow.postMessage(JSON.stringify({
    event: 'command',
    func: 'seekTo',
    args: [seconds, true],
  }), '*')
}

document.addEventListener('click', (e) => {
  const link = e.target.closest('a[data-seek]')
  if (!link) return
  seekYouTube(parseInt(link.dataset.seek, 10))
})

// Auto-seek on page load via ?t= query parameter
const tParam = new URLSearchParams(window.location.search).get('t')
if (tParam) {
  const seconds = parseInt(tParam, 10)
  if (seconds > 0) {
    // Wait for YouTube iframe to be ready
    window.addEventListener('message', function onReady (e) {
      try {
        const data = JSON.parse(e.data)
        if (data.event === 'onReady' || data.event === 'initialDelivery') {
          seekYouTube(seconds)
          window.removeEventListener('message', onReady)
        }
      } catch { /* ignore non-JSON messages */ }
    })
    // Fallback: seek after a delay in case the ready event was missed
    setTimeout(() => seekYouTube(seconds), 2000)
  }
}
