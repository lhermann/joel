import Vue from "vue";
import instantiate from "../instantiate.js";

instantiate.add("toggle", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "Toggle",
    data() {
      return {
        toggled: false
      };
    },
    methods: {
      toggle(state) {
        console.log("toggle me");
        if (state && typeof state === Boolean) this.toggled = state;
        else this.toggled = !this.toggled;
      }
    }
  });
}
