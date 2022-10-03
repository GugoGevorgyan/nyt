/** @format */

import store from './../store';

export default class Snackbar {
    /**
     * Shows snackbar with message
     *
     * @param {String} text
     * @param {String} color
     */
    static show(text, color = 'default') {
        store.commit('SNACKBAR', { text, color, show: true, action: {} });

        return this;
    }

    /**
     * Show Success
     *
     * @param {String} text
     * @return {*}
     */
    static success(text) {
        return Snackbar.show(text, 'success');
    }

    /**
     * Show Error
     *
     * @param {String} text
     */
    static error(text) {
        return Snackbar.show(text, 'error');
    }

    /**
     * Show Info
     *
     * @param {String} text
     */
    static info(text) {
        return Snackbar.show(text, 'info');
    }

    /**
     * Set Snackbar Action
     *
     * @param {Action} action
     */
    static withAction(action) {
        store.commit('SNACKBARACTION', action);

        return this;
    }
}
