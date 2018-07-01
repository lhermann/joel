/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from "vue";
import Popper from "popper.js";

/* Instantiate Dropdown
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="dropdown"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "Dropdown",
        data() {
            return {
                visible: false,
                popper: null
            };
        },
        methods: {
            toggleDropdown(event, force = null) {
                this.visible = force === null ? !this.visible : force;
                this.$nextTick(() => {
                    this.popper.scheduleUpdate();
                });
            },
            onOutsideClick(event) {
                if (this.visible && !this.$el.contains(event.target)) {
                    this.toggleDropdown(event, false);
                }
            }
        },
        mounted() {
            this.popper = new Popper(this.$refs.button, this.$refs.dropdown, {
                placement: this.$refs.dropdown.dataset.placement
            });
        },
        created() {
            document.addEventListener("click", this.onOutsideClick);
        },
        destroyed() {
            document.removeEventListener("click", this.onOutsideClick);
        }
    });
}

export default { instances };
