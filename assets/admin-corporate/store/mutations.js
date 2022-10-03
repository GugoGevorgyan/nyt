/** @format */

export default {
    initialize(state, initialData) {
        state.company = Object.assign({}, initialData.company);
        state.carClasses = initialData.carClasses;
        state.orderStatuses = initialData.statuses;
        state.orderTypes = initialData.types;
    },

    //Company Info

    updateCompanyName(state, name) {
        state.company = Object.assign({}, state.company, { name });
    },

    setCompanyPhoneMask (state, phone) {
        state.phoneMask =  phone;
    },

    updateCompanyLegalAddress(state, legal_address) {
        state.company = Object.assign({}, state.company, { legal_address });
    },

    updateCompanyEmail(state, email) {
        state.company = Object.assign({}, state.company, { email });
    },

    updateCompanyActualAddress(state, actual_address) {
        state.company = Object.assign({}, state.company, { actual_address });
    },

    updateCompanyDetails(state, details) {
        state.company = Object.assign({}, state.company, { details });
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
