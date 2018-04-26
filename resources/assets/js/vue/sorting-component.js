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
            show: false
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
            this.popper.scheduleUpdate();
            setTimeout(
                function(arg) {
                    arg.popper.scheduleUpdate();
                },
                60,
                this
            );
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
