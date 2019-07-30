/*
 * Slider Nav Vue Component
 * @author: Lukas Hermann
 */

import MedialistComponent from "../medialist/medialist.js";
import get from "lodash/get";

export default {
    name: "SliderTeaserComponent",
    template: "#slider-teaser-component",
    components: { MedialistComponent },
    props: {
        slides: Array,
        currentSlide: Number,
        auto: Boolean
    },
    data: function() {
        return {
            collapsed: false,
            options: {},
            params: {
                per_page: 5,
                series_exclude: 368 // mit Gott leben
            },
            userIntervened: false
        };
    },
    methods: {
        onCollapseClick() {
            this.collapsed = !this.collapsed;
            this.userIntervened = true;
            this.$refs.button.blur();
        }
    },
    watch: {
        slides(slides) {
            this.collapsed =
                get(slides[this.currentSlide], "acf.slide_type") !== "teaser";
        },
        currentSlide(index) {
            if (this.userIntervened) return;
            if (get(this.slides[index], "acf.slide_type") === "teaser")
                this.collapsed = false;
            else if (!this.auto && index === 0) this.collapsed = false;
            else this.collapsed = true;
        }
    }
};
