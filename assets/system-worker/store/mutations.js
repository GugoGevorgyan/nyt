/** @format */

export default {
    createMap(state, map) {
        state.map = map;
    },
    /**
     * Init Auth
     *
     * @param {Object} state
     * @param {{check: boolean, user: {Object}}} payload
     * @constructor
     */
    INITAUTH(state, payload) {
        state.auth = { ...payload };
    },

    /**
     * INIT Url
     *
     * @param state
     * @param payload
     * @constructor
     */
    INIT_URL(state, payload) {
        state.initUrl = payload;
    },

    /**
     * Toggle navbar mini variant
     *
     * @param state
     * @param {Boolean} mini
     * @constructor
     */
    TOGGLE(state, mini) {
        localStorage.setItem("navigate", mini);
        state.mini = mini;
    },

    /**
     * Toggling snackbar
     *
     * @param state
     * @param {Object} payload
     * @constructor
     */
    SNACKBAR(state, payload) {
        state.snackbar = { ...payload };
    },

    phoneMask(state, mask) {
        state.phoneMask = mask;
    },

    /**
     * Toggling snackbar with action
     *
     * @param state
     * @param payload
     * @constructor
     */
    SNACKBARACTION(state, payload) {
        state.snackbar.action = { ...payload };
    },

    /**
     * Toggling snackbar with action
     *
     * @param state
     * @param payload
     * @constructor
     */
    ROLES_ACTION(state, payload) {
        state.roles = [...payload];
    },

    /**
     * Toggling snackbar with action
     *
     * @param state
     * @param payload
     * @constructor
     */
    PERMISSIONS_ACTION(state, payload) {
        state.permissions = [...payload];
    },

    /**
     * Menu add action
     *
     * @param state
     * @param payload
     * @constructor
     */
    MENU_ACTION(state, payload) {
        state.menu = { ...payload };
    },

    /**
     * Modules add action
     *
     * @param state
     * @param payload
     * @constructor
     */
    MODULES_ACTION(state, payload) {
        state.modules = [...payload];
    },

    /**
     * CHAT STATE INITIATOR
     *
     * @param state
     * @param payload
     */
    initMessenger(state, payload) {
        state.messeanger = { ...state.messeanger, ...payload };
    },

    /**
     * DASHBOARD INIT
     *
     * @param state
     * @param payload
     */
    initDashboard(state, payload) {
        state.dashboard = { ...state.dashboard, ...payload };
    },

    /**
     * WORKER INIT
     *
     * @param state
     * @param payload
     */
    initWorker(state, payload) {
        state.worker = { ...state.worker, ...payload };
    },
};
