import Vue from "vue";
import instantiate from "../instantiate.js";
import axios from "axios";
import Streamcheck from "./streamcheck.vue";
import Event from "./event.vue";

instantiate.add("livestream-dropdown", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "LivestreamDropdown",
    components: { Streamcheck, Event },
    data() {
      return {
        initDone: false,
        params: {},
        options: {},
        events: []
      };
    },
    methods: {
      request() {
        this.params.thumbnail_size = "72p";
        axios
          .get("joel/v1/events", { params: this.params })
          .then(response => (this.events = response.data))
          .catch(error => console.log({ error }));
      },
      init(options, params) {
        if (this.initDone) return;
        this.initDone = true;
        this.params = params;
        this.options = options;
      },
      onMouseover() {
        if (!this.events.length) this.request();
      }
    }
  });
}
