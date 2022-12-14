/** @format */

import Vue from "vue";
import Router from "vue-router";
import { routes } from "./routes";
require("./filters");

Vue.use(Router);

export const router = new Router({
    mode: "history",
    routes,
});
