<template>
  <div v-if="!hasCookie" class="c-cookie-consent">
    <div class="o-wrapper">
      <div class="o-flex o-flex--tiny o-flex--middle o-flex--wrap">
        <div class="o-flex__item u-1/1 u-2/3@desktop">
          <!-- <template v-if="doNotTrack">
            Die "Do Not Track"-Einstellung deines Browsers ist aktiv. Nur notwendige Cookies werden gesetzt.
          </template> -->
          {{ options['page-name'] }} verwendet Cookies. Manche Cookies sind für die Grundfunktionen dieser Seite, andere erfassen wie du diese Seite verwendest mithilfe von Matomo. Weitere Infos in der <a class="c-link c-link--dotted" :href="options['privacy-policy-link']">Datenschutzerklärung</a>.
        </div>
        <div class="o-flex__item u-1/1 u-1/3@desktop u-text-right">
          <button class="c-link c-link--dotted u-nowrap" @click="deny">
            Nur notwendige Cookies erlauben
          </button>
          <button class="c-btn c-btn--small c-btn--green u-ml-" @click="allow">
            OK
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Cookies from 'js-cookie'
import addYears from 'date-fns/addYears'

export default {
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      reevaluateCookie: 1,
    }
  },
  computed: {
    matomo () {
      return window._paq
    },
    cookie: {
      get () {
        return this.reevaluateCookie && Cookies.get('consent-cookie')
      },
      set (value) {
        Cookies.set('consent-cookie', value, { expires: addYears(new Date(), 1) })
        this.reevaluateCookie++
      },
    },
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
        window.navigator.doNotTrack === '1' ||
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
      this.cookie = 'allow'
      this.matomo.push(['rememberConsentGiven'])
    },
    deny () {
      this.cookie = 'deny'
      this.matomo.push(['forgetConsentGiven'])
    },
  },
}
</script>
