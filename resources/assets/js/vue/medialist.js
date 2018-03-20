/*
 * Medialist
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import MediaitemComponent from "./mediaitem-component.js";
import PaginationComponent from "./pagination-component.js";
// import { cacheAdapterEnhancer } from "axios-extensions";

/* Axios
 **********************/
axios.defaults.baseURL = "/wp-json/";
axios.defaults.headers = { "cache-control": "max-age=31536000, public" };
// Disabled caching adapter for performance reasons
// axios.defaults.adapter = cacheAdapterEnhancer(axios.defaults.adapter, true);

/* Instantiate Medialists
 **********************/
var _medialistInstances = [];
var medialists = document.getElementsByClassName("jsMedialist");
for (var i = 0; i < medialists.length; i++) {
    let list = medialists.item(i);
    _medialistInstances.push(medialistInstance("#" + list.getAttribute("id")));
}

/* Vue Instance
 **********************/
function medialistInstance(_id) {
    return new Vue({
        el: _id,
        components: {
            MediaitemComponent,
            PaginationComponent
        },
        data() {
            return {
                namespace: "wp/v2/",
                route: "recordings",
                columns: 1,
                items: [],
                total: 0,
                userParams: {},
                perPage: 10,
                currentPage: 1,
                totalPages: 1,
                pagination: false, // false, 'minimal', 'normal', 'verbose'
                isLoading: false
            };
        },
        computed: {
            params() {
                var defaults = {};
                var overwrites = {
                    page: this.currentPage,
                    per_page: this.perPage
                };
                return Object.assign(defaults, this.userParams, overwrites);
            },
            medialistClass() {
                var css = [];
                if (this.columns > 1)
                    css.push("c-medialist--" + this.columns + "col");
                return css;
            }
        },
        methods: {
            setOptions(payload) {
                if (typeof payload.pagination !== "undefined")
                    this.pagination = payload.pagination;
                if (typeof payload.namespace !== "undefined")
                    this.namespace = payload.namespace;
                if (typeof payload.route !== "undefined")
                    this.route = payload.route;
                if (typeof payload.columns !== "undefined")
                    this.columns = payload.columns;
            },
            setParams(payload) {
                this.userParams = payload;
                // default params
                if (typeof payload.page !== "undefined")
                    this.currentPage = payload.page;
                if (typeof payload.per_page !== "undefined")
                    this.perPage = payload.per_page;
            },
            changePage(n) {
                this.currentPage = n;
                this.requestRecordings();
            },
            requestRecordings() {
                this.isLoading = true;
                // create 10 dummys
                var self = this;
                axios
                    .get(this.namespace + this.route, {
                        params: this.params
                    })
                    .then(function(response) {
                        self.isLoading = false;
                        self.items = response.data;
                        self.totalPages = parseInt(
                            response.headers["x-wp-totalpages"]
                        );
                        self.total = parseInt(response.headers["x-wp-total"]);
                    })
                    .catch(function(error) {
                        self.isLoading = false;
                        console.log(error);
                    });
            }
        },
        mounted() {
            this.requestRecordings();
        }
    });
}
