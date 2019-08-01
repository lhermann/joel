import Vue from "vue";
import Medialist from "./medialist.vue";

/* Instantiate Medialists
 **********************/
const instances = [];
const elements = document.querySelectorAll('[data-vue="medialist"]');
for (var i = 0; i < elements.length; i++) {
    instances.push(vueInstance("#" + elements[i].getAttribute("id")));
}

/* Vue Instance
 **********************/
function vueInstance(_id) {
    return new Vue({
        el: _id,
        name: "MedialistRoot",
        components: { Medialist },
        data() {
            return {
                initDone: false,
                params: {},
                options: {}
            };
        },
        methods: {
            init(options, params) {
                if (this.initDone) return;
                Object.assign(this.params, params);
                Object.assign(this.options, options);
                this.initDone = true;
            }
        }
    });
}

export default { instances };
