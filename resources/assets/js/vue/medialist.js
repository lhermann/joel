/*
 * Medialist
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import MediaitemComponent from "./mediaitem-component.js";
import PaginationComponent from "./pagination-component.js";
import SortingComponent from "./sorting-component.js";
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
        name: "Medialist",
        components: {
            MediaitemComponent,
            PaginationComponent,
            SortingComponent
        },
        data() {
            return {
                initDone: false,
                namespace: "wp/v2/",
                route: "recordings",
                columns: 1,
                items: [],
                params: {
                    page: 1,
                    per_page: 10,
                    order: null,
                    orderby: null
                },
                title: false,
                tabs: false,
                sorting: false,
                pagination: false, // false, 'minimal', 'normal', 'verbose'
                total: 0,
                totalPages: 1,
                isLoading: false,
                currentSortingOption: { label: "" }
            };
        },
        computed: {
            perPage() {
                return this.params["per_page"];
            },
            currentPage() {
                return this.params.page;
            },
            medialistClass() {
                if (this.columns > 1)
                    return ["c-medialist--" + this.columns + "col"];
                return [];
            },
            sortingOptions() {
                return [
                    {
                        label: "Neue zuerst",
                        order: "desc",
                        orderby: this.route === "recordings" ? "date" : "id"
                    },
                    {
                        label: "Alte zuerst",
                        order: "asc",
                        orderby: this.route === "recordings" ? "date" : "id"
                    },
                    {
                        label: "Alphabetisch (A-Z)",
                        order: "asc",
                        orderby: this.route === "recordings" ? "title" : "name"
                    },
                    {
                        label: "Alphabetisch (Z-A)",
                        order: "desc",
                        orderby: this.route === "recordings" ? "title" : "name"
                    }
                ];
            }
        },
        methods: {
            init(options, params) {
                if (this.initDone) return;
                this.setOptions(options);
                this.setParams(params);
                this.initDone = true;
            },
            setOptions(payload) {
                for (let option in payload) {
                    this[option] = payload[option];
                }
            },
            setParams(payload) {
                Object.assign(this.params, payload);
                this.setCurrentSortingOption();
            },
            setCurrentSortingOption() {
                for (let option of this.sortingOptions) {
                    if (this.params.order && this.params.order !== option.order)
                        continue;
                    if (
                        this.params.orderby &&
                        this.params.orderby !== option.orderby
                    )
                        continue;
                    this.currentSortingOption = option;
                    return;
                }
            },
            requestRecordings() {
                this.isLoading = true;
                this.items = [];
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
            },
            onChangePage(page) {
                this.params.page = page;
                this.requestRecordings();
            },
            onSelectOption(option) {
                this.currentSortingOption = option;
                this.params.order = option.order;
                this.params.orderby = option.orderby;
                this.requestRecordings();
            }
        },
        mounted() {
            this.requestRecordings();
        }
    });
}
