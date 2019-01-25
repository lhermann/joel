/*
 * Streamcheck
 *
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";

/* Instantiate Streamcheck
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="streamcheck"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "Streamcheck",
        data() {
            return {
                initDone: false,
                streamId: "",
                loading: false,
                live: false
            };
        },
        methods: {
            request() {
                if (this.streamId) {
                    this.loading = true;
                    axios
                        .get("//streamcheck.joelmedia.de/" + this.streamId)
                        .then(response => (this.live = response.data.live))
                        .catch(error => console.log({ error }))
                        .then(() => (this.loading = false));
                }
            },
            init(streamId) {
                if (this.initDone) return;
                this.streamId = streamId;
                this.initDone = true;
                this.request();
            }
        },
        beforeMount() {
            // this.request();
        }
    });
}

export default { instances };
