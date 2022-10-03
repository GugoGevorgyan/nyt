/** @format */
import { csrf } from "./common/bootstrap";

import Vue from "vue";
import Vuetify from "vuetify";
import store from "./store/index";
import { router } from "./plugins/index";
import { VueMaskDirective } from "v-mask";
import Lodash from "lodash";
import VeeValidate from "vee-validate";
import { DatePicker } from "element-ui";
import VueI18n from "vue-i18n";
import VueHtmlToPaper from "vue-html-to-paper";
import axios from "axios";
import lang from "element-ui/lib/locale/lang/ru-RU";
import locale from "element-ui/lib/locale";
import ru_vuetify from "vuetify/es5/locale/ru";

// configure language
Vue.prototype.$lodash = Lodash;
Vue.prototype.$csrf = csrf;
Vue.prototype.$http = axios;

const paperOptions = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
    styles: [
        "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css",
        "https://unpkg.com/kidlat-css/css/kidlat.css",
    ],
};
Vue.use(Vuetify);
Vue.use(VeeValidate, {
    locale: 'ru'
});
Vue.use(DatePicker);
Vue.use(VueI18n);
Vue.use(VueHtmlToPaper, paperOptions);
locale.use(lang);

Vue.config.devtools = "production" !== process.env.NODE_ENV;

Vue.directive("mask", VueMaskDirective);
require("./components/index");
Vue.component("admin-corporate", require("./views/AdminCorporate").default);

/**
 * Next, we will CreateTariffComponents a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: "#personal-admin-app",
    router,
    store,
    vuetify: new Vuetify({
        icons: {
            iconfont: "mdi",
        },
        lang: {
            locales: { ru_vuetify },
            current: "ru",
        },
    }),
});
