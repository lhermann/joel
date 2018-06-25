/*
 * Slider Nav Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "SliderNavComponent",
    template: "#slider-nav-component",
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
};
