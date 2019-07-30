/*
 * Slide Vue Component
 * @author: Lukas Hermann
 */
import get from "lodash/get";

const colors = {
    "dark-blue": ["#ffffff", "#d1e4f9"],
    teal: ["#ffffff", "#8aeec6"],
    red: ["#ffffff", "#ff9a99"],
    green: ["#2d4000", "#629303"],
    blue: ["#001f5c", "#004fb8"],
    image: ["#ffffff", "#ffffff"]
};

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
        colors() {
            const bg = get(this.acf, "colors", ""),
                match = /slide-(.+?).\w{1,4}$/.exec(bg),
                theme = get(match, "1", bg);
            if (theme === "image") {
                return {
                    theme,
                    bg: get(this.acf, "background_image.sizes.bg3x1", ""),
                    title: get(this.acf, "text_color", "black"),
                    text: get(this.acf, "text_color", "black")
                };
            } else {
                return {
                    theme,
                    bg,
                    title: colors[theme][0],
                    text: colors[theme][1]
                };
            }
        },
        slideStyle() {
            return { "background-image": `url(${this.colors.bg})` };
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
