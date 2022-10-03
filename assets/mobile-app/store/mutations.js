/** @format */

import lodash from 'lodash';
import { car, client, driver, maps, notification, order, orderFeedback, orderForm, orderProgress, snackbar } from './initial';

// example version 2 state.orderForm = Object.assign({},state.orderForm, payload);
export default {
    /**
     *
     * @param state
     * @param payload
     */
    updateToday(state, payload) {
        state.today = payload;
    },

    /**
     *
     * @param state
     * @param payload
     */
    updateDate(state, payload) {
        state.date = payload;
    },

    /**
     *
     * @param state
     * @param payload
     */
    updateTime(state, payload) {
        state.time = payload;
    },

    /**
     *
     * @param state
     * @param payload
     */
    initMap(state, payload) {
        state.maps = lodash.isEmpty(payload) ? maps : { ...state.maps, ...payload };
    },

    /**
     * Toggling snackbar
     *
     * @param state
     * @param {Object} payload
     * @constructor
     */
    SNACKBAR(state, payload) {
        state.snackbar = lodash.isEmpty(payload) ? snackbar : { ...state.snackbar, ...payload };
    },

    /**
     * Modules add action
     *
     * @param state
     * @param payload
     * @constructor
     */
    initClient(state, payload) {
        state.client = lodash.isEmpty(payload) ? client : { ...state.client, ...payload };
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    orderInit(state, payload) {
        state.order = lodash.isEmpty(payload) ? order : { ...state.order, ...payload };
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    initDriver(state, payload) {
        state.driver = lodash.isEmpty(payload) ? driver : { ...state.driver, ...payload };
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    initCar(state, payload) {
        state.car = lodash.isEmpty(payload) ? car : { ...state.driver, ...payload };
    },

    /**
     * OrderProgress
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderProgress(state, payload) {
        state.orderProgress = lodash.isEmpty(payload) ? orderProgress : { ...state.orderProgress, ...payload };
    },

    /**
     * OrderFeedback
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderFeedback(state, payload) {
        state.orderFeedback = lodash.isEmpty(payload) ? orderFeedback : { ...state.orderFeedback, ...payload };
    },

    /**
     * Data in after refresh client
     *
     * @param state
     * @param payload
     * @constructor
     */
    initClientInOrderData(state, payload) {
        state.clientInOrder = lodash.isEmpty(payload) ? payload : { ...state.clientInOrder, ...payload };
    },

    /**
     * Init to from OrderForm
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderForm(state, payload) {
        state.orderForm = lodash.isEmpty(payload) ? orderForm : { ...state.orderForm, ...payload };
    },

    /**
     * Init to from Notifications
     *
     * @param state
     * @param payload
     * @constructor
     */
    initNotification(state, payload) {
        state.notification = lodash.isEmpty(payload) ? notification : { ...state.notification, ...payload };
    },

    /**
     * Initialize App data
     *
     * @param state
     * @param payload
     */
    initApp(state, payload) {
        state.app = lodash.isEmpty(payload) ? payload : { ...state.app, ...payload };
    },
};
