/* Global Variable */
window._joel = Object.assign({ templatePath: "", assetPath: "" }, window._joel);
console.log(window._joel);

/* Vue */
import Vue from "vue";
Vue.prototype.$joel = window._joel;
// Vue.mixin({
//   data() {
//     return { $joel: window._joel };
//   }
// });

/* Axios */
import axios from "axios";
axios.defaults.baseURL = "/wp-json/";

/* Vue init scrips */
require("./medialist/init.js");
// import "./medialist/init.js";

// import "./medialist/medialist.vue";
// import "./test/test.vue";
// import "./vue/slider.js";
// import "./vue/dropdown.js";
// import "./vue/pagination.js";
// import "./vue/livestream-dropdown.js";
// import "./vue/livestream-meta.js";
// import "./vue/cookie-consent.js";
