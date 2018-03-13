/*
 * Joel Media Medialist
 * @author: Lukas Hermann
 */

import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";

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

/* Axios
 **********************/
Vue.use(VueAxios, axios);

/* Vue Instance
 **********************/
function medialistInstance(_id) {
    return new Vue({
        el: _id,
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
                .get("/wp-json/wp/v2/recordings/", {
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

/* Nav Component
 **********************/
Vue.component("mediaitem-component", {
    template: "#mediaitem-component",
    props: ["item"],
    data: function() {
        return {
            type: "video"
        };
    },
    computed: {
        title() {
            return this.item.title.rendered;
        }
    },
    methods: {}
});
