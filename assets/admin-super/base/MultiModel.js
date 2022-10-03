/** @format */

import axios from 'axios';
import {serialize} from 'object-to-formdata';

export default class MultiModel {
    /** @type {Object} */
    #form = {};
    /** @type {Array} */
    #models = [];
    /** @type {Boolean} */
    #toFormData = false;

    /**
     * Multiple Form Constructor
     *
     * @param {Array} models
     * @param {Boolean} toFormData
     */
    constructor(models, toFormData = false) {
        this.#models = models;
        this.#toFormData = toFormData;
    }

    /**
     * Send forms to server
     *
     * @param {String} url
     * @param {String} method
     * @return { AxiosPromise }
     */
    send(url, method = 'post') {
        let build = this.build();

        if ('put' === method) {
            build.append('_method', 'PUT');
            return axios.post(url, build);
        } else {
            return axios[method](url, build);
        }
    }

    /**
     * Builds data for sending to server
     *
     * @return {Object|FormData}
     */
    build() {
        this.#models.forEach(model => {
            this.#form[model.scope] = model.buildWithout();
        });

        if (this.#toFormData)
            return serialize(this.#form, {
                /**
                 * whether or not to include array indices in formData keys
                 * defaults to false
                 */
                indices: true,
                /**
                 * whether or not to include null values as empty strings in formData instance
                 * defaults to true
                 */
                nulls: true,
            });

        return this.#form;
    }

    /**
     * Get server validation errors with scopes
     *
     * @param {Object} response
     * @return {Array}
     */
    static errors(response) {
        let err = [],
            field = '',
            msg = '',
            scope = '';

        if (response.status === 422) {
            let errors = response.data.errors;

            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    scope = key.split('.')[0];
                    field = key.split('.')[1];
                    msg = errors[key][0];

                    err.push({ scope, field, msg });
                }
            }
        }

        return err;
    }
}
