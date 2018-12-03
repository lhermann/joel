/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from "vue";
import StreamCheck from "./livestream/streamcheck.js";
import axios from "axios";
import format from "date-fns/format";
import locale from "date-fns/locale/de";

/* Instantiate Streamcheck
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="livestream-dropdown"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "LivestreamDropdown",
        components: { StreamCheck },
        data() {
            return {
                initDone: false,
                params: {},
                options: {},
                events: []
            };
        },
        methods: {
            weekday(date) {
                return format(date, "EEEE", { locale });
            },
            date(date) {
                return format(date, "d. MMM", { locale });
            },
            time(time) {
                return format(time, "k:mm", { locale });
            },
            request() {
                axios
                    .get("joel/v1/events", { params: this.params })
                    .then(response => (this.events = response.data));
            },
            init(options, params) {
                if (this.initDone) return;
                this.initDone = true;
                this.params = params;
                this.options = options;
            },
            onMouseover() {
                if (!this.events.length) this.request();
            }
        }
    });
}

export default { instances };
