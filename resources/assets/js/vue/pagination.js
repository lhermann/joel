/*
 * Pagination Init
 *
 * @author: Lukas Hermann
 */

import Vue from "vue";
import PaginationComponent from "./medialist/pagination.js";

/* Instantiate Dropdown
 **********************/
let instances = [];
let elements = document.querySelectorAll('[data-vue="pagination"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "Pagination",
        components: { PaginationComponent },
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

export default { instances };
