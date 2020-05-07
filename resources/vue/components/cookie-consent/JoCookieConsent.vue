<template>
  <div v-if="!hasCookie" class="c-cookie-consent">
    <div class="o-wrapper">
      <div class="o-flex o-flex--tiny o-flex--middle o-flex--wrap">
        <div class="o-flex__item u-1/1 u-2/3@desktop">
          <template v-if="doNotTrack">
            Die "Do Not Track"-Einstellung deines Browsers ist aktiv. Nur notwendige Cookies werden gesetzt.
          </template>
          <template v-else>
            {{ options['page-name'] }} verwendet Cookies. Manche Cookies sind für die Grundfunktionen dieser Seite, andere erfassen wie du diese Seite verwendest mithilfe von Matomo.
          </template>
            Weitere Infos in der <a class="c-link c-link--dotted" :href="options['privacy-policy-link']">Datenschutzerklärung</a>.
        </div>
        <div class="o-flex__item u-1/1 u-1/3@desktop u-text-right">
          <button v-if="!doNotTrack" class="c-link c-link--dotted u-nowrap" @click="deny">
            Nur notwendige Cookies erlauben
          </button>
          <button class="c-btn c-btn--small c-btn--green u-ml--" @click="allow">
            OK
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Cookies from 'js-cookie'

export default {
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      cookie: null,
      paq: window._paq,
    }
  },
  computed: {
    hasCookie () {
      return Boolean(this.cookie)
    },
    consentGiven () {
      return this.cookie === 'allow'
    },
    doNotTrack () {
      if (typeof window === 'undefined') return false
      return Boolean(
        window.doNotTrack ||
        window.navigator.doNotTrack ||
        window.navigator.msDoNotTrack,
      )
    },
  },
  methods: {
    init (options) {
      if (this.initDone) return
      this.initDone = true
      this.options = options
    },
    allow () {
      this.setCookie(this.doNotTrack ? 'deny' : 'allow')
      this.paq.push(['rememberConsentGiven'])
    },
    deny () {
      this.setCookie('deny')
      this.paq.push(['forgetConsentGiven'])
    },
    setCookie (value) {
      Cookies.set('consent-cookie', value, { expires: 365 })
      this.cookie = value
    },
    getCookie () {
      this.cookie = Cookies.get('consent-cookie')
    },
  },
  beforeMount () {
    this.getCookie()
  },
}
</script>
