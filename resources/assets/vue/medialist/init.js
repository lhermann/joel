import Vue from "vue";
import Medialist from "./medialist.vue";
import instantiate from "../instantiate.js";

instantiate.add("medialist", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "MedialistRoot",
    components: { Medialist },
    data() {
      return {
        initDone: false,
        params: {},
        options: {}
      };
    },
    methods: {
      init(options, params) {
        if (this.initDone) return;
        Object.assign(this.params, params);
        Object.assign(this.options, options);
        this.initDone = true;
      }
    }
  });
}
