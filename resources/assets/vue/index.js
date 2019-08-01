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
 * Init Vue components
 *
 * Don't use 'import' because they are hoisted and the settings above would
 * not be applied
 */
require("./medialist/init.js");
require("./pagination/init.js");
require("./slider/init.js");
require("./cookie-consent/init.js");
require("./livestream/init-dropdown.js");
require("./livestream/init-meta.js");

/*
 * Init Vue Utilities
 */
require("./utils/dropdown.js");
