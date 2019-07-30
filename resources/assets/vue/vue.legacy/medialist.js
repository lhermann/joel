/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from "vue";
import MedialistComponent from "./medialist/medialist.js";

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
