/*
 * Slider Nav Vue Component
 * @author: Lukas Hermann
 */

import MedialistComponent from "../medialist/medialist.js";

export default {
    name: "SliderTeaserComponent",
    template: "#slider-teaser-component",
    components: { MedialistComponent },
    props: ["currentSlide"],
    data: function() {
        return {
            teaserCollapsed: false,
            options: {},
            params: { per_page: 5 },
            userIntervened: false
        };
    },
    methods: {
        onCollapseClick() {
            this.teaserCollapsed = !this.teaserCollapsed;
            this.userIntervened = true;
        }
    },
    watch: {
        currentSlide(newSlide) {
            if (this.userIntervened) return;
            if (newSlide === 0) {
                this.teaserCollapsed = false;
            } else {
                this.teaserCollapsed = true;
            }
        }
    }
};
