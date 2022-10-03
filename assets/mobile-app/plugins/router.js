/** @format */

import Vue from 'vue';
import Router from 'vue-router';
import state from './../store/index';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    base: process.env.APP_URL,
    routes: [
        {
            name: 'mobile_index',
            path: '/m',
            components: {
                default: () => import('.././views/MobileIndex'),
            },
            meta: {
                requiresAuth: false,
            },
        },
        {
            name: 'mobile_auth',
            path: '/m/auth',
            components: {
                default: () => import('../views/auth/Auth'),
            },
            meta: {
                requiresAuth: false,
            },
        },
        {
            name: 'mobile_tariffs',
            path: '/m/tariffs',
            components: {
                default: () => import('./../views/Tariffs'),
            },
            meta: {
                requiresAuth: false,
            },
        },
        {
            name: 'mobile_preorders',
            path: '/m/preorders',
            components: {
                default: () => import('./../views/Preorders'),
            },
            meta: {
                requiresAuth: true,
            },
        },
    ],
});

export default router;

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (state.isAuth) {
            next();
        } else {
            next('/m/auth');
        }
    } else {
        next();
    }
});
