/** @format */
import Vue from "vue";
import Vuetify from "vuetify";
import VeeValidate, { Validator } from "vee-validate";
import router from "./plugins/routes";
import store from "./store/index";
import { DatePicker } from "element-ui";
import _ from "lodash";
import { csrf } from "./common/bootstrap";
import { VueMaskDirective } from "v-mask";
import VueHotkey from "v-hotkey";
import axios from "axios";

import ru from "vee-validate/dist/locale/ru";
import locale from "element-ui/lib/locale";
import lang from "element-ui/lib/locale/lang/en";
import lang_element from "element-ui/lib/locale/lang/ru-RU";
import lang_validate from "vee-validate/dist/locale/ru";
import lang_vuetify from "vuetify/lib/locale/ru";
import { preset } from "vue-cli-plugin-vuetify-preset-reply/preset";
import Snackbar from "./facades/Snackbar";

require("./plugins/filters");
require("./plugins/ValidAddress");
require("./common/bootstrap");

locale.use(lang);
Validator.localize("ru", lang_validate);
locale.use(lang_element);
Vue.use(VeeValidate, { locale: "ru", mode: "passive" });
Vue.use(DatePicker);
Vue.use(Vuetify);
Vue.use(VueHotkey);

Vue.prototype.$csrf = csrf;
Vue.prototype.$dash = _;
Vue.prototype.$http = axios;

if (process.env.MIX_APP_ENV === "production") {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
} else {
    Vue.config.devtools = true;
}

/**
 * Next, we will CreateTariffComponents a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
require("./components");
Vue.component("login", require("./views/Auth/Login").default);
Vue.component("profile", require("./views/Profile/Profile").default);
const el = document.getElementById("app");
Vue.directive("mask", VueMaskDirective);
// noinspection ObjectAllocationIgnored
new Vue({
    el: el,
    router,
    store,
    vuetify: new Vuetify({
        preset,
        icons: {
            iconfont: "mdiSvg",
        },
        lang: {
            locales: { lang_vuetify },
            current: "ru",
        },
        theme: {
            dark: false,
            default: "light",
            disable: false,
            options: {
                cspNonce: undefined,
                customProperties: undefined,
                minifyTheme: undefined,
                themeCache: undefined,
            },
            themes: {
                light: {
                    primary: "#344955",
                    secondary: "#F9AA33",
                    tertiary: "#232F34",
                    quaternary: "#4A6572",
                    accent: "#D2DBE0",
                    error: "#FF5252",
                    info: "#2196F3",
                    success: "#4CAF50",
                    warning: "#FB8C00",
                },
            },
        },
    }),

    created() {
        let pos;
        if (localStorage.getItem("from_c")) {
            pos = localStorage.getItem("from_c");
            pos = pos.split(",");
            this.$store.commit("initApp", { navigateCord: { lat: pos[0], lut: pos[1] } });
        } else {
            navigator.geolocation.watchPosition(
                position => {
                    pos = { lat: position.coords.latitude, lut: position.coords.longitude };
                    this.$store.commit("initApp", { navigateCord: pos });
                },
                error => {
                    if (error.code === error.PERMISSION_DENIED) {
                        pos = { lat: 55.755819, lut: 37.617644 }; // @todo fix this by ip coords
                        this.$store.commit("initApp", { navigateCord: pos });
                    }
                },
            );
        }
    },
    beforeDestroy() {
        axios.post("/online", { status: 0 }).then(r => {});
    },
});
