import Vue from "vue";
import Streamcheck from "./streamcheck.vue";
import axios from "axios";
import format from "date-fns/format";
import locale from "date-fns/locale/de";
import parseISO from "date-fns/parseISO";
import instantiate from "../instantiate.js";

instantiate.add("livestream-dropdown", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "LivestreamDropdown",
    components: { Streamcheck },
    data() {
      return {
        initDone: false,
        params: {},
        options: {},
        events: []
      };
    },
    methods: {
      weekday(date) {
        return format(parseISO(date), "EEEE", { locale });
      },
      date(date) {
        return format(parseISO(date), "d. MMM", { locale });
      },
      time(time) {
        return format(parseISO(time), "k:mm", { locale });
      },
      request() {
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
