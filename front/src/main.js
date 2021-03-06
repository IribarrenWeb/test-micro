/*!

=========================================================
* Vue Argon Dashboard - v2.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/vue-argon-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/vue-argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import ArgonDashboard from "./plugins/argon-dashboard";
import "element-plus/lib/theme-chalk/index.css";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import { store } from "./store";
import "@fortawesome/fontawesome-free/css/all.min.css"

import { defineRule } from "vee-validate";
import AllRules from "@vee-validate/rules";

Object.keys(AllRules).forEach((rule) => {
    defineRule(rule, AllRules[rule]);
});

const appInstance = createApp(App);
appInstance.use(VueSweetalert2);
appInstance.use(router);
appInstance.use(store);
appInstance.use(ArgonDashboard);
appInstance.mount("#app");