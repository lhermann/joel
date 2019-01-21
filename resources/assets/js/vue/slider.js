/*
 * Slider Init
 * @author: Lukas Hermann
 *
 * Modes:
 *  - always:   continually change slides
 *  - initial:  only change slides automatically for one cycle
 *  - none:     no automatic slide change
 */

import Vue from "vue";
import axios from "axios";
import SliderComponent from "./slider/slider.js";

/* Axios
 **********************/
axios.defaults.baseURL = "/wp-json/";

/* Global Values
 **********************/
var _slideTransition = 800;

/* Instantiate Sliders
 *********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="slider"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "SliderRoot",
        components: { SliderComponent },
        data() {
            return {
                initDone: false,
                mode: "none",
                slideDuration: 5000,
                slideTransition: 800,
                teaser: false
            };
        },
        methods: {
            init(options) {
                if (this.initDone) return;
                Object.assign(this, options);
                this.initDone = true;
            }
        }
    });
}

export default { instances };
