/** @format */

import Vue from 'vue';
import VueRouter from 'vue-router';
import Toolbar from '../components/Toolbar';
import Dashboard from '../views/Dashboard';
import Navigation from '../components/Navigation';
import Regions from '../views/Regions/Regions';
import Cities from "../views/Regions/Cities";
require('./filters')

Vue.use(VueRouter);

const URN = process.env.MIX_SUPER_ADMIN_URN;

export default new VueRouter({
    mode: 'history',
    base: process.env.APP_URL,

    routes: [
        {
            props: true,
            name: 'admin.super.login',
            path: '/admin/super/login',
            component: () => import('../views/auth/Login'),
        },
        {
            props: true,
            name: 'admin.super.dashboard',
            path: '/admin/super/dashboard',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: Dashboard,
            },
        },
        {
            props: true,
            name: 'admin.super.regions',
            path: '/admin/super/regions',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: Regions,
            },
        },
        {
            props: true,
            name: 'admin.super.cities',
            path: '/admin/super/cities',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: Cities,
            },
        },
        {
            props: true,
            name: 'admin.super.modules',
            path: '/admin/super/modules',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Modules'),
            },
        },
        {
            props: true,
            name: 'admin.super.roles',
            path: '/admin/super/roles',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Roles'),
            },
        },
        {
            props: true,
            name: 'admin.super.permissions',
            path: '/admin/super/permissions',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Permissions'),
            },
        },
        {
            props: true,
            name: 'admin.super.franchises',
            path: '/admin/super/franchises',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Franchises/Index'),
            },
        },
        {
            props: true,
            name: 'admin.super.franchises.create',
            path: '/admin/super/franchises/create',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Franchises/Create'),
            },
        },
        {
            props: true,
            name: 'admin.super.franchises.edit',
            path: '/admin/super/franchises/:franchise/edit',
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('../views/Franchises/Edit'),
            },
        },
        {
            props: true,
            name: 'admin.super.tariff.index',
            path: `/admin/super/tariff`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Tariff/Index'),
            },
        },
        {
            props: true,
            name: 'admin.super.get_tariff_create',
            path: `/admin/super/tariff/create`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Tariff/Create'),
            },
        },
        {
            props: true,
            name: 'admin.super.get_tariff_edit',
            path: `/admin/super/tariff/edit/:tariff_id`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Tariff/Edit'),
            },
        },

        {
            props: true,
            name: 'admin.super.area.index',
            path: `/admin/super/area`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Area/Index'),
            },
        },
        {
            props: true,
            name: 'admin.super.area.create',
            path: `/admin/super/tariff/create`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Tariff/Create'),
            },
        },
        {
            props: true,
            name: 'admin.super.area.edit',
            path: `/admin/super/tariff/edit/:tariff_id`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Tariff/Edit'),
            },
        },
        {
            props: true,
            name: 'admin.super.station.airport',
            path: `/admin/super/station/airports`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Stations/Airport'),
            },
        },
        {
            props: true,
            name: 'admin.super.station.railway',
            path: `/admin/super/station/railways`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Stations/Railways'),
            },
        },
        {
            props: true,
            name: 'admin.super.station.metro',
            path: `/admin/super/station/metros`,
            components: {
                toolbar: Toolbar,
                navigation: Navigation,
                default: () => import('./../views/Stations/Metros'),
            },
        },
    ],
});
