import Vue from "vue";
import instantiate from "../instantiate.js";
import Events from "./events.vue";

instantiate.add("events", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "EventsRoot",
    components: { Events },
    data() {
      return {
        initDone: false,
        options: {},
        params: {}
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
