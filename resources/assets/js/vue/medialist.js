/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import MedialistComponent from "./medialist/medialist.js";
// import { cacheAdapterEnhancer } from "axios-extensions";

/* Axios
 **********************/
axios.defaults.baseURL = "/wp-json/";
axios.defaults.headers = { "cache-control": "max-age=31536000, public" };
// Disabled caching adapter for performance reasons
// axios.defaults.adapter = cacheAdapterEnhancer(axios.defaults.adapter, true);

/* Instantiate Medialists
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="medialist"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "MedialistRoot",
        components: { MedialistComponent },
        data() {
            return {
                initDone: false,
                params: {},
                options: {}
            };
        },
        methods: {
            init(options, params) {
                if (this.initDone) return;
                Object.assign(this.params, params);
                Object.assign(this.options, options);
                this.initDone = true;
            }
        }
    });
}

export default { instances };
