/*
 * Slider Nav Vue Component
 * @author: Lukas Hermann
 */

import MedialistComponent from "../medialist/medialist.js";

export default {
    name: "SliderTeaserComponent",
    template: "#slider-teaser-component",
    components: { MedialistComponent },
    props: ["index", "currentSlide"],
    data: function() {
        return {
            teaserCollapsed: false,
            options: {},
            params: { per_page: 4 }
        };
    },
    computed: {},
    methods: {}
};
