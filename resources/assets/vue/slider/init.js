/*
 * Slider Init
 *
 * Modes:
 *  - always:   continually change slides
 *  - initial:  only change slides automatically for one cycle
 *  - none:     no automatic slide change
 */

import Vue from "vue";
import axios from "axios";
import Slider from "./slider.vue";

/* Global Values
 **********************/
const _slideTransition = 800;

/* Instantiate Sliders
 *********************/
const instances = [];
const elements = document.querySelectorAll('[data-vue="slider"]');
for (let i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "SliderRoot",
        components: { Slider },
        data() {
            return {
                initDone: false,
                mode: "none",
                slideDuration: 5000,
                slideTransition: 800,
                teaser: false,
                loaded: false,
                id: null,
                params: {}
            };
        },
        methods: {
            init(options, params) {
                if (this.initDone) return;
                Object.assign(this, options);
                Object.assign(this.params, params);
                this.initDone = true;
            }
        }
    });
}

export default { instances };
