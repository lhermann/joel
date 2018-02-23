/*
 * Joel Media Slider
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";

/* Global Values
 **********************/
var slideDuration = 3000;
var slideTransition = 800;
var slideDelay = 200;

/* Axios
 **********************/
Vue.use(VueAxios, axios);

/* Instantiate Vue
 **********************/
var mainSlider = new Vue({
    el: "#main-slider",
    data() {
        return {
            slides: [],
            count: 0,
            currentSlide: 0,
            slideOrder: [],
            css: ["is-automatic"]
        };
    },
    methods: {
        changeSlide(i) {
            console.log("slide " + i);
            this.currentSlide = i;
            for (var i = 0; i < this.slideOrder.length; i++) {
                this.slideOrder[i]--;
            }
            this.slideOrder[this.currentSlide] = this.count;
        },
        nextSlide() {
            if (this.currentSlide >= this.count - 1) {
                this.changeSlide(0);
            } else {
                this.changeSlide(this.currentSlide + 1);
            }
        },
        previousSlide() {
            if (this.currentSlide <= 0) {
                this.changeSlide(this.count - 1);
            } else {
                this.changeSlide(this.currentSlide - 1);
            }
        }
    },
    mounted() {
        var self = this;
        axios
            .get("/wp-json/wp/v2/slides/")
            .then(function(response) {
                console.log(response);
                self.slides = response.data;
                self.count = response.data.length;
                for (var i = 0; i < self.slides.length; i++) {
                    self.slides[i].index = i;
                    self.slideOrder[i] = self.count - i;
                }
            })
            .catch(function(error) {
                console.log(error);
            });

        // this.interval = setInterval(function() {
        //     if (slider.object.isAutomatic) {
        //         slider.object.nextSlide();
        //     }
        // }, slider.slideDuration);
    }
});

/* Nav Component
 **********************/
Vue.component("nav-component", {
    template: "#nav-component",
    props: ["index", "currentSlide"],
    data: function() {
        return {};
    },
    computed: {
        css() {
            return {
                "is-active": this.currentSlide === this.index
            };
        }
    },
    methods: {
        changeSlide() {
            this.$emit("clicked", this.index);
        }
    }
});

/* Slide Component
 **********************/
Vue.component("slide-component", {
    template: "#slide-component",
    props: ["slide", "order", "currentSlide"],
    data: function() {
        return Object.assign(
            {
                liCss: []
            },
            this.slide
        );
    },
    computed: {
        liStyle() {
            return { "z-index": this.order };
        },
        css() {
            return [
                "c-slide--" + this.acf.slide_type,
                "c-slide--" + this.acf.color_scheme
            ];
        },
        style() {
            var bg =
                this.acf.background === "image"
                    ? this.acf.background_image.sizes.bg3x1
                    : this.acf.background;
            return { "background-image": "url(" + bg + ")" };
        },
        media() {
            if (
                this.acf.slide_type === "media-left" ||
                this.acf.slide_type === "media-right"
            ) {
                return this.acf.media_content[0];
            } else {
                return false;
            }
        }
    },
    methods: {
        animate(css) {
            this.liCss.push(css);
            setTimeout(
                function(obj) {
                    obj.liCss = [];
                },
                slideTransition,
                this
            );
        }
    },
    watch: {
        currentSlide: function(newVal, oldVal) {
            console.log("Prop changed: ", newVal, " | was: ", oldVal);
            if (newVal === this.index && newVal > oldVal) {
                this.animate("is-entering");
            } else if (oldVal === this.index && newVal < oldVal) {
                this.animate("is-leaving");
            }
        }
    }
});
