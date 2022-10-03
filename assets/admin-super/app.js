/** @format */

import Vue from "vue";
import store from "./store/index";
import router from "./plugins/router";
import vuetify from "./plugins/vuetify";
import VeeValidate, { Validator } from "vee-validate";
import ru from "vee-validate/dist/locale/ru";
import { csrf } from "./common/bootstrap";
import { VueMaskDirective } from "v-mask";
import Vuetify from "vuetify";
import { preset } from "vue-cli-plugin-vuetify-preset-basil/preset";
import ru_vuetify from "vuetify/es5/locale/ru";
require("./common/bootstrap");

Vue.use(VeeValidate, {
    locale: "ru",
});
Validator.localize("ru", ru);
Vue.config.devtools = true;
Vue.prototype.$csrf = csrf;

/**
 * Next, we will CreateTariffComponents a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.directive("mask", VueMaskDirective);
Vue.component("snackbar", require("./components/Snackbar").default);

const el = document.getElementById("admin-super");

new Vue({
    el,
    props: ["auth"],
    propsData: {
        auth: JSON.parse(el.dataset.auth),
    },
    store,
    router,
    vuetify: new Vuetify({
        preset,
        iconfont: "mdi",
        lang: {
            locales: { ru_vuetify },
            current: "en",
        },
    }),
    created() {
        this.$store.commit("INITAUTH", this.auth);
    },
});
