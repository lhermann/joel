/*
 * Medialist
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import MediaitemComponent from "./mediaitem-component.js";
// import { cacheAdapterEnhancer } from 'axios-extensions';

/* Axios
 **********************/
axios.defaults.baseURL = '/wp-json/wp/v2/';
axios.defaults.headers = {'cache-control': 'max-age=31536000, public'};
// Disabled caching adapter for performance reasons
// axios.defaults.adapter = cacheAdapterEnhancer(axios.defaults.adapter, true);

/* Instantiate Medialists
 **********************/
var _medialistInstances = [];
var medialists = document.getElementsByClassName("jsMedialist");
for (var i = 0; i < medialists.length; i++) {
    let list = medialists.item(i);
    _medialistInstances.push(
        medialistInstance(
            "#" + list.getAttribute("id")
            // list.getAttribute("data-per-page") || 10
            // JSON.parse(list.getAttribute("data-filter")) || {}
            // Number(list.getAttribute("data-duration")) || 4000,
            // list.getAttribute("data-mode") || "none"
        )
    );
}

/* Vue Instance
 **********************/
function medialistInstance(_id) {
    return new Vue({
        el: _id,
        components: {
            MediaitemComponent
        },
        data() {
            return {
                recordings: [],
                params: {}
            };
        },
        computed: {},
        methods: {
            setParams(payload) {
                this.params = payload;
            }
        },
        mounted() {
            var self = this;
            axios
                .get("recordings", {
                    params: this.params
                })
                .then(function(response) {
                    self.recordings = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    });
}
