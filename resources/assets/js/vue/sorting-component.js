/*
 * Sorting Vue Component
 * @author: Lukas Hermann
 */

import Popper from "popper.js";

export default {
    name: "SortingComponent",
    template: "#sorting-component",
    props: ["options", "currentOption"],
    data() {
        return {
            popper: null,
            show: false,
            fadeIn: false
        };
    },
    computed: {},
    methods: {
        isCurrent(option) {
            return (
                this.currentOption && option.label === this.currentOption.label
            );
        },
        onButtonClick() {
            this.show = !this.show;
            setTimeout(() => {
                this.popper.scheduleUpdate();
            }, 20);
        },
        onSelectOption(option) {
            this.$emit("select", option);
            this.show = false;
        },
        onOutsideClick(event) {
            if (this.show && !this.$el.contains(event.target)) {
                this.show = false;
            }
        }
    },
    watch: {
        show(newState) {
            setTimeout(() => {
                this.fadeIn = newState;
            }, 20);
        }
    },
    mounted() {
        this.popper = new Popper(this.$refs.reference, this.$refs.popper, {
            placement: "bottom-end"
        });
    },
    created() {
        document.addEventListener("click", this.onOutsideClick);
    },
    destroyed() {
        document.removeEventListener("click", this.onOutsideClick);
    }
};
