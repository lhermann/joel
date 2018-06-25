/*
 * Medialist
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
var _medialistInstances = [];
var medialists = document.getElementsByClassName("jsMedialist");
for (var i = 0; i < medialists.length; i++) {
    let list = medialists.item(i);
    _medialistInstances.push(medialistInstance("#" + list.getAttribute("id")));
}

/* Vue Instance
 **********************/
function medialistInstance(_id) {
    return new Vue({
        el: _id,
        name: "MedialistInstantiator",
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
