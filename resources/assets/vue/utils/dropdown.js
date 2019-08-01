import Vue from "vue";
import Popper from "popper.js";
import instantiate from "../instantiate.js";

instantiate.add("dropdown", vueInstance);

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
