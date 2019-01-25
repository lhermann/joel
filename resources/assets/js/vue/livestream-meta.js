/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from "vue";
import StreamCheck from "./livestream/streamcheck.js";
import axios from "axios";
import format from "date-fns/format";
import locale from "date-fns/locale/de";
import parseISO from "date-fns/parseISO";

/* Instantiate Streamcheck
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="livestream-meta"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "LivestreamMeta",
        components: { StreamCheck },
        data() {
            return {
                initDone: false,
                params: {},
                options: {},
                event: null,
                loading: true
            };
        },
        computed: {
            start() {
                const time = this.event.StartDate + " " + this.event.StartTime;
                return format(parseISO(time), "k:mm", { locale });
            },
            finish() {
                const time = this.event.EndDate + " " + this.event.FinishTime;
                return format(parseISO(time), "k:mm", { locale });
            }
        },
        methods: {
            request() {
                axios
                    .get("joel/v1/events", { params: this.params })
                    .then(response => (this.event = response.data.pop()))
                    .catch(error => console.log({ error }))
                    .then(() => (this.loading = false));
            },
            init(options, params) {
                if (this.initDone) return;
                this.initDone = true;
                this.params = params;
                this.options = options;
                this.request();
            }
        }
    });
}

export default { instances };
