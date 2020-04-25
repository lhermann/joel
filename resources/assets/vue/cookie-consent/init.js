import Vue from "vue";
import addYears from "date-fns/addYears";
import get from "lodash/get";
import instantiate from "../instantiate.js";
import Cookies from "js-cookie"

instantiate.add("cookie-consent", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "Cookie Consent",
    data() {
      return {
        initDone: false,
        options: {},
        cookie: null,
      };
    },
    computed: {
      hasCookie (){
        return Boolean(this.cookie);
      },
      consentGiven () {
        return this.cookie === "allow";
      },
      doNotTrack () {
        if (typeof window === 'undefined') return false;
        return Boolean(
          window.doNotTrack
          || window.navigator.doNotTrack
          || window.navigator.msDoNotTrack
        );
      },
    },
    methods: {
      init(options) {
        if (this.initDone) return;
        this.initDone = true;
        this.options = options;
      },
      allow() {
        this.setCookie(this.doNotTrack ? "deny" : "allow");
        _paq.push(["rememberConsentGiven"]);
      },
      deny() {
        this.setCookie("deny");
        _paq.push(["forgetConsentGiven"]);
      },
      setCookie(value) {
        Cookies.set('consent-cookie', value, { expires: 365 });
        this.cookie = value;
      },
      getCookie() {
        this.cookie = Cookies.get('consent-cookie');
      },
    },
    beforeMount() {
      this.getCookie();
    }
  });
}
