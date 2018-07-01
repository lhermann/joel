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
 **********************/
var _sliderInstances = [];
var sliders = document.getElementsByClassName("jsSlider");
for (var i = 0; i < sliders.length; i++) {
    let slider = sliders.item(i);
    _sliderInstances.push(sliderInstance("#" + slider.getAttribute("id")));
}

/* Vue Instance
 **********************/
function sliderInstance(_id) {
    return new Vue({
        el: _id,
        name: "SliderInstantiator",
        components: { SliderComponent },
        data() {
            return {
                initDone: false,
                mode: "none",
                slideDuration: 4000,
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
