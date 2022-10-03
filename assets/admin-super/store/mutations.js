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
     * Toggle navbar mini variant
     *
     * @param state
     * @param {Boolean} mini
     * @constructor
     */
    TOGGLE(state, mini) {
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
};
