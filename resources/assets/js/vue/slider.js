/*
 * Joel Media Slider
 * @author: Lukas Hermann
 *
 * Modes:
 *  - always:   continually change slides
 *  - initial:  only change slides automatically for one cycle
 *  - none:     no automatic slide change
 */

import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";

/* Global Values
 **********************/
var _slideTransition = 800;
var _sliderInstances = [];

/* Instantiate Sliders
 **********************/
var sliders = document.getElementsByClassName("jsSlider");
for (var i = 0; i < sliders.length; i++) {
    let slider = sliders.item(i);
    _sliderInstances.push(
        sliderInstance(
            "#" + slider.getAttribute("id"),
            Number(slider.getAttribute("data-duration")) || 4000,
            slider.getAttribute("data-mode") || "none"
        )
    );
}

/* Axios
 **********************/
Vue.use(VueAxios, axios);

/* Vue Instance
 **********************/
function sliderInstance(_id, _slideDuration, _mode) {
    return new Vue({
        el: _id,
        data() {
            return {
                slides: [],
                count: 0,
                currentSlide: 0,
                slideOrder: [],
                mode: _mode,
                slideChangeCount: 0,
                interval: null,
                isAutomatic: true,
                slideDuration: _slideDuration
            };
        },
        computed: {
            containerClass() {
                return {
                    "is-automatic": this.isAutomatic
                };
            }
        },
        methods: {
            changeSlide(i) {
                if (this.currentSlide !== i) this.slideChangeCount++;
                this.currentSlide = i;
                for (var i = 0; i < this.slideOrder.length; i++) {
                    // decrease order of all slides
                    this.slideOrder[i]--;
                }
                // give current slide the highest order
                this.slideOrder[this.currentSlide] = this.count;
            },
            manuallyChangeSlide(i) {
                this.stopAutomaticSlideChange();
                switch (i) {
                    case "next":
                        this.nextSlide();
                        break;
                    case "previous":
                        this.previousSlide();
                        break;
                    default:
                        this.changeSlide(i);
                }
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
            },
            stopAutomaticSlideChange() {
                clearInterval(this.interval);
                this.isAutomatic = false;
            }
        },
        mounted() {
            var self = this;
            axios
                .get("/wp-json/wp/v2/slides/")
                .then(function(response) {
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

            // if (this.$el.hasAttribute("data-mode")) {
            //     this.mode = this.$el.getAttribute("data-mode");
            // }

            if (this.mode !== "none") {
                this.isAutomatic = true;
                this.interval = setInterval(
                    function(context) {
                        context.nextSlide();
                        if (
                            context.mode === "initial" &&
                            context.slideChangeCount >= context.count
                        ) {
                            context.stopAutomaticSlideChange();
                        }
                    },
                    this.slideDuration,
                    this
                );
            }
        }
    });
}

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
                _slideTransition,
                this
            );
        }
    },
    watch: {
        currentSlide: function(newVal, oldVal) {
            if (newVal === this.index && newVal > oldVal) {
                this.animate("is-entering");
            } else if (oldVal === this.index && newVal < oldVal) {
                this.animate("is-leaving");
            }
        }
    }
});
