/** @format */

// example version 2 state.orderForm = Object.assign({},state.orderForm, payload);
export default {
    /**
     *
     * @param state
     * @param payload
     */
    initMap(state, payload) {
        state.maps = { ...state.maps, ...payload };
    },

    /**
     *
     * @param state
     * @param initialData
     */
    initialize(state, initialData) {
        state.orderStatuses = initialData.statuses;
        state.orderTypes = initialData.types;
    },

    /**
     * Toggling snackbar
     *
     * @param state
     * @param {Object} payload
     * @constructor
     */
    SNACKBAR(state, payload) {
        state.snackbar = { ...state.snackbar, ...payload };
    },

    /**
     *
     * @param state
     * @param today
     */
    updateToday(state, today) {
        state.today = today;
    },

    /**
     *
     * @param state
     * @param date
     */
    updateDate(state, date) {
        state.date = date;
    },

    /**
     *
     * @param state
     * @param time
     */
    updateTime(state, time) {
        state.time = time;
    },

    /**
     * Modules add action
     *
     * @param state
     * @param payload
     * @constructor
     */
    initClient(state, payload) {
        state.client = { ...state.client, ...payload };
    },

    /**
     * DETECT IN ORDER STATUS AND PASS DATUM
     *
     * @param state
     * @param payload
     * @constructor
     */
    INIT_IN_ORDER_ACTION(state, payload) {
        state.inOrder = payload.status;
        state.inOrderData = payload.data;
        state.responseOrderData = payload.responseData;
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    orderInit(state, payload) {
        state.order = { ...state.order, ...payload };
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    initDriver(state, payload) {
        state.driver = { ...state.driver, ...payload };
    },

    /**
     * Global order data init
     *
     * @param state
     * @param payload
     * @constructor
     */
    initCar(state, payload) {
        state.car = { ...state.car, ...payload };
    },

    /**
     * OrderProgress
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderProgress(state, payload) {
        state.orderProgress = { ...state.orderProgress, ...payload };
    },

    /**
     * OrderFeedback
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderFeedback(state, payload) {
        state.orderFeedback = { ...state.orderFeedback, ...payload };
    },

    initOrderRepeat(state, payload) {
        state.orderRepeat = { ...state.orderRepeat, ...payload };
    },

    /**
     * Data in after refresh client
     *
     * @param state
     * @param payload
     * @constructor
     */
    initClientInOrderData(state, payload) {
        state.clientInOrder = { ...state.clientInOrder, ...payload };
    },

    /**
     * Init to from placeMarks
     *
     * @param state
     * @param payload
     * @constructor
     */
    markInit(state, payload) {
        state.mark = { ...state.mark, ...payload };
    },

    /**
     * Init to from OrderForm
     *
     * @param state
     * @param payload
     * @constructor
     */
    initOrderForm(state, payload) {
        state.orderForm = { ...state.orderForm, ...payload };
    },

    /**
     * Init to from Notifications
     *
     * @param state
     * @param payload
     * @constructor
     */
    initNotification(state, payload) {
        state.notification = { ...state.notification, ...payload };
    },

    /**
     * Init to from Notifications
     *
     * @param state
     * @param payload
     * @constructor
     */
    initTransports(state, payload) {
        state.transports = { ...state.transports, ...payload };
    },

    /**
     * Initialize App data
     *
     * @param state
     * @param payload
     */
    initApp(state, payload) {
        state.app = { ...state.app, ...payload };
    },
};
