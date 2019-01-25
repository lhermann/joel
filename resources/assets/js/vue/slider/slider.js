/*
 * Slider Vue Component
 * @author: Lukas Hermann
 */

import axios from "axios";
import SlideComponent from "./slide.js";
import SliderNavComponent from "./nav.js";
import SliderTeaserComponent from "./teaser.js";

export default {
    name: "SliderComponent",
    template: "#slider-component",
    components: {
        SlideComponent,
        SliderNavComponent,
        SliderTeaserComponent
    },
    props: ["mode", "slideDuration", "slideTransition", "teaser"],
    data() {
        return {
            slides: [],
            count: 0,
            currentSlide: 0,
            slideOrder: [],
            slideChangeCount: 0,
            interval: null,
            isAutomatic: true
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
            .get("wp/v2/slides")
            .then(response => {
                self.slides = response.data;
                self.count = response.data.length;
                for (var i = 0; i < self.slides.length; i++) {
                    self.slides[i].index = i;
                    self.slideOrder[i] = self.count - i;
                }
            })
            .catch(error => console.log({ error }));

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
};
