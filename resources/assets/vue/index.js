/* Vue */
import Vue from "vue";
Vue.prototype.$joel = Object.assign(
  { templatePath: "", assetPath: "" },
  window._joel
);

/* Axios */
import axios from "axios";
axios.defaults.baseURL = "/wp-json/";

/*
 * Vue init scrips
 *
 * Don't use 'import' because they are hoisted and the settings above would
 * not be applied
 */
require("./medialist/init.js");
require("./pagination/init.js");

// import "./medialist/medialist.vue";
// import "./test/test.vue";
// import "./vue/slider.js";
// import "./vue/dropdown.js";
// import "./vue/pagination.js";
// import "./vue/livestream-dropdown.js";
// import "./vue/livestream-meta.js";
// import "./vue/cookie-consent.js";
