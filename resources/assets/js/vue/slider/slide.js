/*
 * Slide Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "SlideComponent",
    template: "#slide-component",
    props: ["slide", "order", "currentSlide", "slideTransition"],
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
                this.slideTransition,
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
};
