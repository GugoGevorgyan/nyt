/** @format */

import _ from 'lodash';

export default class Form {
    rules;
    hidden = [];

    /**
     * Build form hidden
     *
     * @param {Array} hidden
     * @return {Object}
     */
    build(hidden = []) {
        let defaults = ['rules', 'service', 'hidden', 'except', 'only', 'FormData'].concat(hidden).concat(this.hidden);

        return _.omit(this, defaults);
    }

    /**
     * Server validation errors
     *
     * @param {Object} response
     * @param {Boolean} scoped
     * @return {Array}
     */
    static errors(response, scoped = false) {
        let errors;
        let err = [],
            field = '',
            msg = '',
            scope = '';

        if (422 === response.status) {
            errors = response.data.errors;

            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    field = key;
                    msg = errors[key][0];

                    err.push({ field, msg });
                }
            }
        }

        return err;
    }
}
