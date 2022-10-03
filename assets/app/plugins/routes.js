/** @format */

import Vue from "vue";
import Router from "vue-router";

import ClientLogin from "../components/auth/ClientLogin";
import CorporateLogin from "../components/auth/CorporateLogin";
import Maps from "../views/Maps/Maps";
import NavbarComponent from "../components/static/NavbarComponent";

Vue.use(Router);

export default new Router({
    mode: "history",
    base: process.env.APP_URL,
    routes: [
        { path: "/", component: Maps, props: true },
        { path: "/login-client", component: ClientLogin, props: true },
        { path: "/login-corporate", component: CorporateLogin, props: true },

        {
            props: true,
            name: "client_profile",
            path: `/profile/`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../views/Profile/Profile"),
            },
        },
        {
            props: true,
            name: "clientInfo",
            path: `/profile/info`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/PersonalInformation"),
            },
        },
        {
            props: true,
            name: "getOrders",
            path: `/profile/orders`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/Orders/OrderHistory"),
            },
        },
        {
            props: true,
            name: "getAddresses",
            path: `/profile/address`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/AddressList"),
            },
        },
        {
            props: true,
            name: "getCompanies",
            path: `/profile/companies`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/Companies"),
            },
        },
        {
            props: true,
            name: "getNotifications",
            path: `/profile/notifications`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/Notification"),
            },
        },
        {
            props: true,
            name: "getPreOrders",
            path: `/profile/preorders`,
            components: {
                navbar: NavbarComponent,
                default: () => import("../components/profile/PreOrder"),
            },
        },
    ],
});
