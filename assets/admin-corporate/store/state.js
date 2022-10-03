/** @format */

import Vue from "vue";
import Vuex from "vuex";
import mutations from "./mutations";
import { company, auth, snackbar } from "./initial";

export default {
    broadcast: null,
    company: company,
    carClasses: [],
    orderStatuses: [],
    orderTypes: [],
    phoneMask: '',

    // global functional
    mini: false,
    auth: auth,
    snackbar: snackbar,
};
