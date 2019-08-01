import Vue from "vue";
import Pagination from "./pagination.vue";
import instantiate from "../instantiate.js";

instantiate.add("pagination", vueInstance);

/* Vue Instance
 **********************/
function vueInstance(_id) {
  return new Vue({
    el: _id,
    name: "PaginationRoot",
    components: { Pagination },
    data() {
      return {
        initDone: false,
        options: {}
      };
    },
    methods: {
      init(options, params) {
        if (this.initDone) return;
        Object.assign(this.options, options);
        this.initDone = true;
      },
      onPageClick(page) {
        console.log(page);
        window.location = this.options.baseUrl + page;
      }
    },
    mounted() {}
  });
}
