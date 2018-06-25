/*
 * Medialist Vue Component
 * @author: Lukas Hermann
 */

import axios from "axios";
import MediaitemComponent from "./mediaitem.js";
import PaginationComponent from "./pagination.js";
import SortingComponent from "./sorting.js";

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
            // create 10 dummys
            this.items = Array(this.params.per_page).fill(this.createDummy());
            axios
                .get(this.namespace + this.route, {
                    params: this.params
                })
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
            this.params.order = option.order;
            this.params.orderby = option.orderby;
            this.requestRecordings();
        }
    },
    mounted() {
        this.setOptions(this.initOptions);
        this.setParams(this.initParams);
        this.requestRecordings();
    }
};
