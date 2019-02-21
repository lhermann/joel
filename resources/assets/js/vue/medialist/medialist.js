/*
 * Medialist Vue Component
 * @author: Lukas Hermann
 */

import axios from "axios";
import MediaitemComponent from "./mediaitem.js";
import PaginationComponent from "./pagination.js";
import SortingComponent from "./sorting.js";
import get from "lodash/get";

export default {
    name: "MedialistComponent",
    template: "#medialist-component",
    components: {
        MediaitemComponent,
        PaginationComponent,
        SortingComponent
    },
    props: ["initOptions", "initParams"],
    data() {
        return {
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
            const recordings = this.route === "recordings";
            const speakers = this.route === "speakers";
            const options = [
                {
                    label: "Neue zuerst",
                    params: {
                        order: "desc",
                        orderby: recordings ? "date" : "id"
                    }
                },
                {
                    label: "Alte zuerst",
                    params: {
                        order: "asc",
                        orderby: recordings ? "date" : "id"
                    }
                },
                {
                    label: speakers ? "Vorname (A-Z)" : "Alphabetisch (A-Z)",
                    params: {
                        order: "asc",
                        orderby: recordings ? "title" : "name"
                    }
                },
                speakers
                    ? {
                          label: "Nachname (A-Z)",
                          params: {
                              order: "asc",
                              orderby: "lastname"
                          }
                      }
                    : {
                          label: "Alphabetisch (Z-A)",
                          params: {
                              order: "desc",
                              orderby: recordings ? "title" : "name"
                          }
                      }
            ];
            if (this.route === "recordings") {
                options.push({
                    label: "Beliebtheit",
                    namespace: "wordpress-popular-posts/v1/",
                    route: "popular-posts",
                    params: {
                        post_type: "recordings",
                        range: "all",
                        limit: 50
                    }
                });
            } else {
                options.push({
                    label: "Anzahl Videos",
                    params: {
                        order: "desc",
                        orderby: "count"
                    }
                });
            }
            return options;
        }
    },
    methods: {
        setOptions(payload) {
            for (let option in payload) {
                this[option] = payload[option];
            }
        },
        setParams(payload) {
            Object.assign(this.params, payload);
            this.setInitialSortingOption();
        },
        /**
         * Choose a sorting option that fits with the params
         */
        setInitialSortingOption() {
            // this.currentSortingOption = this.sortingOptions[0];
            // for (let option of this.sortingOptions) {
            //     if (get(this.params, "order") !== option.params.order) continue;
            //     if (get(this.params, "orderby") !== option.params.orderby)
            //         continue;
            //     this.currentSortingOption = option;
            //     return;
            // }

            let i = this.sortingOptions.findIndex(
                opt =>
                    opt.params.orderby === get(this.params, "orderby") &&
                    opt.params.order === (get(this.params, "order") || "asc")
            );
            this.currentSortingOption =
                i >= 0 ? this.sortingOptions[i] : this.sortingOptions[0];
        },
        requestRecordings() {
            this.isLoading = true;
            // create 10 dummys
            const namespace =
                    this.currentSortingOption.namespace || this.namespace,
                route = this.currentSortingOption.route || this.route,
                params = Object.assign(
                    {},
                    this.params,
                    this.sorting ? this.currentSortingOption.params : {}
                );
            this.items = Array(this.params.per_page).fill(this.createDummy());
            axios
                .get(namespace + route, { params })
                .then(response => {
                    this.isLoading = false;
                    this.items = response.data;
                    this.totalPages = parseInt(
                        response.headers["x-wp-totalpages"]
                    );
                    this.total = parseInt(response.headers["x-wp-total"]);
                })
                .catch(error => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        createDummy() {
            return {
                name: "Loading ...",
                type: this.route,
                count: "XXX",
                dummy: true
            };
        },
        onChangePage(page) {
            this.params.page = page;
            this.requestRecordings();
        },
        onSelectOption(option) {
            this.currentSortingOption = option;
            this.params.page = 1;
            this.requestRecordings();
        }
    },
    mounted() {
        this.setOptions(this.initOptions);
        this.setParams(this.initParams);
        this.requestRecordings();
    }
};
