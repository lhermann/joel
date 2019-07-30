/*
 * Cookie Consent
 *
 * @author: Lukas Hermann
 */

import Vue from "vue";
import addYears from "date-fns/addYears";
import get from "lodash/get";

/* Instantiate Streamcheck
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="cookie-consent"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

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
                hasCookie: false,
                consentGiven: null,
                doNotTrack: false
            };
        },
        methods: {
            allow() {
                this.setCookie(this.doNotTrack ? "deny" : "allow");
                _paq.push(["rememberConsentGiven"]);
            },
            deny() {
                this.setCookie("deny");
                _paq.push(["forgetConsentGiven"]);
            },
            init(options) {
                if (this.initDone) return;
                this.initDone = true;
                this.options = options;
            },
            setCookie(value) {
                let expire = addYears(Date.now(), value === "allow" ? 2 : 1);
                document.cookie = `consent-cookie=${value}; expires=${expire.toUTCString()}; path=/;"`;
                this.getCookie();
            },
            getCookie() {
                let cookie = document.cookie.match(
                    "(^|;) ?consent-cookie=([^;]*)(;|$)"
                );
                this.hasCookie = !!cookie;
                this.consentGiven = cookie ? cookie[2] : null;
            },
            getDoNotTrack() {
                if (
                    window.doNotTrack ||
                    navigator.doNotTrack ||
                    navigator.msDoNotTrack ||
                    get(window.external, "msTrackingProtectionEnabled")
                ) {
                    if (
                        window.doNotTrack == "1" ||
                        navigator.doNotTrack == "yes" ||
                        navigator.doNotTrack == "1" ||
                        navigator.msDoNotTrack == "1" ||
                        (get(window.external, "msTrackingProtectionEnabled") &&
                            window.external.msTrackingProtectionEnabled())
                    ) {
                        this.doNotTrack = true;
                    } else {
                        this.doNotTrack = false;
                    }
                }
            }
        },
        beforeMount() {
            this.getCookie();
            this.getDoNotTrack();
        }
    });
}

export default { instances };
