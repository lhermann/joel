import Vue from "vue";
import Streamcheck from "./streamcheck.vue";
import axios from "axios";
import format from "date-fns/format";
import locale from "date-fns/locale/de";
import parseISO from "date-fns/parseISO";
import isToday from 'date-fns/isToday'
import getDay from 'date-fns/getDay'
import instantiate from "../instantiate.js";

instantiate.add("livestream-meta", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "LivestreamMeta",
    components: { Streamcheck },
    data() {
      return {
        initDone: false,
        params: {},
        options: {},
        event: null,
        loading: true
      };
    },
    computed: {
      day () {
        if (isToday(this.event.StartDate)) {
          return "Heute"
        } else {
          return format(parseISO(this.event.StartDate), "iiii", { locale })
        }
      },
      start() {
        const time = this.event.StartDate + " " + this.event.StartTime;
        return format(parseISO(time), "k:mm", { locale });
      },
      finish() {
        const time = this.event.EndDate + " " + this.event.FinishTime;
        return format(parseISO(time), "k:mm", { locale });
      }
    },
    methods: {
      request() {
        axios
          .get("joel/v1/events", { params: this.params })
          .then(response => (this.event = response.data.pop()))
          .catch(error => console.log({ error }))
          .then(() => (this.loading = false));
      },
      init(options, params) {
        if (this.initDone) return;
        this.initDone = true;
        this.params = params;
        this.options = options;
        this.request();
      }
    }
  });
}
