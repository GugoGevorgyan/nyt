/** @format */

import Vue from 'vue';
import Vuetify from 'vuetify';
import VeeValidate, { Validator } from 'vee-validate';
import router from './plugins/router';
import store from './store';
import { DatePicker } from 'element-ui';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
import _ from 'lodash';
import { csrf } from './common/bootstrap';
import { VueMaskDirective } from 'v-mask';
import ru from 'vee-validate/dist/locale/ru';
import VueHotkey from 'v-hotkey';
import axios from 'axios';

require('./plugins/filters');
require('./plugins/validators');
require('./common/bootstrap');

locale.use(lang);

Vue.use(Vuetify);
Vue.use(VueHotkey);
Vue.prototype.$dash = _;
Vue.prototype.$http = axios;

Validator.localize('ru', ru);
Vue.use(VeeValidate, {
    locale: 'ru',
});

Vue.use(DatePicker);

if ('production' === process.env.MIX_APP_ENV) {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
} else {
    Vue.config.devtools = true;
}

Vue.prototype.$csrf = csrf;
Vue.prototype.$lodash = _;

/**
 * Next, we will CreateTariffComponents a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
require('./components');
Vue.component('mobile-index', require('./views/MobileIndex').default);
Vue.component('auth', require('./views/auth/Auth').default);

Vue.directive('mask', VueMaskDirective);

// noinspection ObjectAllocationIgnored
new Vue({
    el: '#mobile-app',
    router,
    store,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi',
        },
    }),

    created() {
        navigator.geolocation.watchPosition(position => {
            let pos = { lat: position.coords.latitude, lut: position.coords.longitude };
            this.$store.commit('initApp', { navigateCord: pos });
        });
    },

    beforeDestroy() {
        axios.post('/online', { status: 0 }).then(r => {});
    },
});
