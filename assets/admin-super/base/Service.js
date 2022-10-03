/** @format */

import $http from 'axios';
import pluralize from 'pluralize';

export default class Service {
    /**
     *
     * @type {number}
     */
    static MODE_ALL = -1;
    /**
     *
     * @type {number}
     */
    static MODE_FIND = -2;
    /**
     *
     * @type {number}
     */
    static MODE_CREATE = 1;
    /**
     *
     * @type {number}
     */
    static MODE_UPDATE = 2;
    /**
     *
     * @type {number}
     */
    static MODE_DELETE = 0;
    /**
     *
     * @type {number}
     */
    static MODE_REMOVE = 3;
    /**
     *
     * @type {number}
     */
    static MODE_RESTORE = 4;
    /**
     * @type {string}
     */
    path = '';
    /**
     * @type {string}
     */
    prefix = '';
    /**
     * @type {string}
     */
    group = '';
    /**
     *
     * @type {Object}
     */
    options = {};

    /**
     * Regular expression for checking url parameters
     *
     * @type {RegExp}
     */
    #reg = /{\w+}/g;

    /**
     * Service Constructor
     *
     * @param {String} path
     * @param {String} prefix
     * @param {String} group
     * @param {{
     * restore: {mode: number, uri: string},
     * CreateTariffComponents: {mode: number, uri: string},
     * update: {mode: number, uri: string},
     * delete: {mode: number, uri: string},
     * remove: {mode: number, uri: string}
     * }} options
     */
    constructor(path, prefix = '', group = '', options) {
        this.path = path;
        this.prefix = prefix;
        this.group = group;
        this.options = options;
    }

    /**
     * Create Endpoint url for request
     *
     * @param {Object} params route parameters
     * @param {Boolean} args
     * @param {Number} mode
     *
     * @return {String}
     */
    #route(params = {}, args, mode) {
        let url = this.#createUrl(args, mode);

        if (this.#isCorrect(params, url)) return Service.#replaceUrlParams(params, url);

        return window.location.origin + '/' + url;
    }

    /**
     * Create full url
     *
     * @param {Boolean} parameterised
     * @param {Number} mode
     * @return {String}
     */
    #createUrl(parameterised, mode) {
        return (
            (this.group ? `${this.group}/` : '') +
            (this.prefix ? `${this.prefix}/` : '') +
            this.path +
            (mode !== Service.MODE_REMOVE || mode !== Service.MODE_RESTORE ? `${this.#getModeUri(mode)}` : '') +
            (parameterised ? `/{${pluralize.singular(this.path)}}` : '') +
            (mode === Service.MODE_REMOVE || mode === Service.MODE_RESTORE ? `${this.#getModeUri(mode)}` : '')
        );
    }

    /**
     *
     * @param {Number} mode
     * @return {string|*}
     */
    #getModeUri(mode) {
        for (let action in this.options) {
            if (this.options[action].mode === mode) return this.options[action].uri ? this.options[action].uri : '';
        }

        return '';
    }

    /**
     * Check Route parameter matching
     *
     * @param {Object} params route parameters
     * @param {string} url
     * @return {Boolean}
     * @throws {Error}
     */
    #isCorrect(params, url) {
        let matches = url.match(this.#reg);

        if (!matches) return true;

        if (!Object.keys(params).length) throw new Error(`Missing required route params ${matches.join(',')}`);

        for (let key in params) {
            if (!matches.includes(`{${key}}`)) {
                throw new Error(`Missing required route parameter: {${key}}`);
            }

            if (!params[key]) {
                throw new Error(`Not correct route parameter for {${key}}`);
            }
        }

        return true;
    }

    /**
     * Replace route parameters
     *
     * @param {Object} params
     * @param {String} url
     * @return {string}
     */
    static #replaceUrlParams(params, url) {
        for (let key in params) {
            let reg = new RegExp(`{${key}}`);

            url = url.replace(reg, params[key]);
        }

        return window.location.origin + '/' + url;
    }

    /**
     * Get Resource collection
     *
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @return {AxiosPromise<any>}
     */
    all(params = {}, query = {}, args = false) {
        return $http.get(this.#route(params, args, Service.MODE_ALL), { params: query });
    }

    /**
     * Get resource
     *
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @return {AxiosPromise<any>}
     */
    find(params = {}, query = {}, args = true) {
        return $http.get(this.#route(params, args, Service.MODE_FIND), { params: query });
    }

    /**
     * Store Resource
     *
     * @param {Object} resource
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @return {AxiosPromise<any>}
     */
    store(resource, params = {}, query = {}, args = false) {
        return $http.post(this.#route(params, args, Service.MODE_CREATE), resource, { params: query });
    }

    /**
     * @param resource
     * @param params
     * @param query
     * @param args
     * @returns {*}
     */
    update(resource, params = {}, query = {}, args = true) {
        resource instanceof FormData ? resource.append('_method', 'PUT') : (resource['_method'] = 'PUT');

        return $http.post(this.#route(params, args, Service.MODE_UPDATE), resource, {
            params: query,
        });
    }

    /**
     * Delete resource
     *
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @return {AxiosPromise}
     */
    delete(params = {}, query = {}, args = true) {
        return $http.delete(this.#route(params, args, Service.MODE_DELETE), { params: query });
    }

    /**
     * Soft Delete specified resource from db
     *
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @returns {AxiosPromise}
     */
    remove(params = {}, query = {}, args = true) {
        return $http.delete(this.#route(params, args, Service.MODE_REMOVE), { params: query });
    }

    /**
     * Restore soft deleted resource
     *
     * @param {Object} params
     * @param {Object} query
     * @param {Boolean} args
     * @returns {AxiosPromise}
     */
    restore(params = {}, query = {}, args = true) {
        return $http.put(this.#route(params, args, Service.MODE_RESTORE), null, { params: query });
    }
}
