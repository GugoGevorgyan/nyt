/** @format */
import { csrf } from "./common/bootstrap";
import Vue from "vue";
import Vuetify from "vuetify";
import lodash from "lodash";
import axios from "axios";
import moment from "moment";
import { router } from "./plugins/index";
import store from "./store";
import VeeValidate, { Validator } from "vee-validate";
import { VueMaskDirective } from "v-mask";
import { DatePicker } from "element-ui";
import VueHotkey from "v-hotkey";
import VueHtmlToPaper from "vue-html-to-paper";
import HighchartsVue from "highcharts-vue";
import { preset } from "vue-cli-plugin-vuetify-preset-reply/preset"; //rally, reply, basil
import VueI18n from "vue-i18n";
import locale_element from "element-ui/lib/locale";
import lang_element from "element-ui/lib/locale/lang/ru-RU";
import lang_validate from "vee-validate/dist/locale/ru";
import lang_vuetify from "vuetify/lib/locale/ru";
import helpers from "./mixins/helpers";

let sip = require("./../shared/sip.min");
require("./common/bootstrap");
require("./components");
require("./views/index");

Vue.use(VueHtmlToPaper);
Vue.use(DatePicker);
Vue.use(Vuetify);
Vue.use(VueHotkey);
Vue.use(HighchartsVue);
Vue.use(VueI18n);
locale_element.use(lang_element);
Vue.use(VeeValidate, { locale: "ru" });
Validator.localize("ru", lang_validate);

Vue.prototype.$dash = lodash;
Vue.prototype.$csrf = csrf;
Vue.prototype.$http = axios;
Vue.prototype.$moment = moment;
Vue.prototype.$sip = sip;
Vue.prototype.$helps = helpers;

Vue.config.devtools = "production" !== process.env.NODE_ENV;

Vue.directive("mask", VueMaskDirective);
const el = document.getElementById("app-system-worker");
const isLogin = lodash.endsWith(window.location.href, "worker/login");

/**
 * Next, we will CreateTariffComponents a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
new Vue({
    el: "#app-system-worker",
    props: ["auth", "menu", "roles", "permissions", "modules", "mask"],
    propsData: {
        auth: isLogin ? {} : JSON.parse(el.dataset.auth),
        menu: isLogin ? {} : JSON.parse(el.dataset.menu),
        roles: isLogin ? {} : JSON.parse(el.dataset.roles),
        permissions: isLogin ? {} : JSON.parse(el.dataset.permissions),
        modules: isLogin ? {} : JSON.parse(el.dataset.modules),
        mask: isLogin ? {} : el.dataset.mask,
    },
    router,
    store,
    vuetify: new Vuetify({
        preset,
        iconfont: "mdiSvg",
        customVariables: ["./sass/_variables.scss"],
        treeShake: true,
        lang: {
            locales: { lang_vuetify },
            current: "ru",
        },
        themes: {
            dark: false,
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
                smoke: "#f5f5f5",
            },
        },
    }),

    computed: {
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },

    mounted() {
        let appColor = document.getElementById("app");
        if (appColor) {
            appColor.style.backgroundColor = this.darkMode ? "#303030" : "#eee";
        }
    },

    created() {
        if ("system-show-login-form" !== this.$route.name) {
            this.$store.commit("initDashboard", { session: this.auth.user.in_session });
            this.$store.commit("INITAUTH", this.auth);
            this.$store.commit("initWorker", {
                worker_id: this.auth.user.system_worker_id,
                franchise_id: this.auth.user.franchise_id,
                is_admin: this.auth.user.is_admin,
                name: this.auth.user.name,
                surname: this.auth.user.surname,
                patronymic: this.auth.user.patronymic,
                rating: this.auth.user.rating,
                logged: this.auth.user.logged,
                in_session: this.auth.user.in_session,
            });
            this.$store.commit("INIT_URL", process.env.MIX_APP_WORKER_URL);
            this.$store.commit("MENU_ACTION", this.menu);
            this.$store.commit("ROLES_ACTION", this.roles);
            this.$store.commit("phoneMask", this.mask);
            this.$store.commit("PERMISSIONS_ACTION", this.permissions);
            this.$store.commit("MODULES_ACTION", this.modules);
        }
    },
});
