/** @format */

import Form from './Form';
import Service from './Service';

import _ from 'lodash';
import pluralize from 'pluralize';
import {objectToFormData} from 'object-to-formdata';

export default class Model extends Form {
    /**
     * Service Configurations
     *
     * @type {{restore: {mode: *, uri: string}, CreateTariffComponents: {mode: *, uri: string}, update: {mode: *, uri: string}, delete: {mode: *, uri: string}, remove: {mode: *, uri: string}}}
     */
    configs = {
        create: { uri: '', mode: Service.MODE_CREATE },
        update: { uri: '', mode: Service.MODE_UPDATE },
        delete: { uri: '', mode: Service.MODE_DELETE },
        deletes: { uri: '', mode: Service.MODE_DELETES },
        remove: { uri: '/remove', mode: Service.MODE_REMOVE },
        restore: { uri: '/restore', mode: Service.MODE_RESTORE },
    };

    baseUrl = process.env.MIX_APP_URL;

    adminUrl = process.env.MIX_ADMIN_URN;

    /**
     * The relations that must be in builtin form
     *
     * @type {Array}
     */
    only = [];
    /**
     * The attributes that must be hidden from builtin model
     *
     * @type {Array}
     */
    hidden = ['baseUrl', 'adminUrl', '_method'];
    /**
     * The relations that must not be in builtin form
     *
     * @type {Array}
     */
    except = ['baseUrl', 'adminUrl', '_method'];
    /**
     * Convert model build to javascript formData
     *
     * @type {boolean}
     */
    formData = false;
    /**
     * Convert model build to javascript formData
     *
     * @type {Array}
     */
    deleteIds = [];
    /**
     * The relation names that model has
     *
     * @type {Array}
     */
    #relations = [];

    /**
     * Model constructor
     *
     * @param {String} endpoint
     * @param {String} prefix
     * @param {String} group
     * @param {Object} options
     */
    constructor(endpoint, prefix = '', group = '', options = {}) {
        super();
        this.configureServiceOptions(options);
        this.service = new Service(endpoint, prefix, group, this.configs);
    }

    /**
     * Has one relationship
     *
     * @param {FunctionConstructor} child
     * @param {Object} resource
     * @return {*}
     */
    hasOne(child, resource) {
        let propertyName = _.camelCase(child.prototype.constructor.name);

        this.#relations.push(propertyName);

        this[`${propertyName}_id`] = Object.keys(resource).length ? resource[`${propertyName}_id`] : null;

        this[propertyName] = Object.keys(resource).length ? new child(resource) : null;

        return this;
    }

    /**
     * Has Many Relationship
     *
     * @param {FunctionConstructor} child
     * @param {Array} resources
     * @return {*}
     */
    hasMany(child, resources) {
        let propertyName = _.camelCase(pluralize.plural(child.prototype.constructor.name));

        this.#relations.push(propertyName);

        this[`${propertyName}_ids`] = resources.length ? resources.map(resource => resource[`${propertyName}_ids`]) : [];

        this[propertyName] = resources.length ? resources.map(resource => new child(resource)) : [];

        return this;
    }

    /**
     * Builds Model Form
     *
     * @return {Object|formData}
     */
    buildForm() {
        let form = this.build();

        this.#relations.forEach(item => {
            if (!form[item]) {
                delete form[item];
                return;
            }

            if (form.hasOwnProperty(item) && form[item].constructor === Array) {
                form[item] = form[item].map(relation => relation.build());
            } else {
                form[item] = form[item].build();
            }
        });

        if (this.except.length && !this.only.length) {
            this.except.forEach(relation => {
                if (form.hasOwnProperty(relation)) {
                    delete form[relation];
                }
            });
        }

        if (this.only.length && !this.except.length) {
            _.difference(this.#relations, this.only).forEach(relation => {
                if (form.hasOwnProperty(relation)) {
                    delete form[relation];
                }
            });
        }

        if (0 < this.deleteIds.length) {
            form = { ids: this.deleteIds };
        }

        if (this.formData) {
            return objectToFormData(form, {
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
        }

        return form;
    }

    /**
     * Builds form without relations
     *
     * @return {Object}
     */
    buildWithout() {
        let form = this.build();

        this.#relations.forEach(relation => {
            if (form.hasOwnProperty(relation)) {
                delete form[relation];
            }
        });

        return form;
    }

    /**
     * @return {{
     * restore: {mode: number, uri: string},
     * CreateTariffComponents: {mode: number, uri: string},
     * update: {mode: number, uri: string},
     * delete: {mode: number, uri: string},
     * remove: {mode: number, uri: string}
     * }}
     */
    configureServiceOptions(options) {
        this.configs.create.uri = options.hasOwnProperty('create') ? options.create : '';
        this.configs.update.uri = options.hasOwnProperty('update') ? options.update : '';
        this.configs.delete.uri = options.hasOwnProperty('delete') ? options.delete : '';
        this.configs.deletes.uri = options.hasOwnProperty('deletes') ? options.deletes : '/deleteIds';
        this.configs.remove.uri = options.hasOwnProperty('remove') ? options.remove : '/remove';
        this.configs.restore.uri = options.hasOwnProperty('restore') ? options.restore : '/restore';
    }

    /**
     * Store Resource
     *
     * @param {Object} params
     * @param {Object} query
     * @return {*|AxiosPromise<any>|number}
     */
    store(params = {}, query = {}) {
        return this.service.store(this.buildForm(), params, query);
    }

    /**
     * Update settings resource
     *
     * @param {Object} params
     * @param {Object} query
     * @return {AxiosPromise<any>}
     */
    update(params = {}, query = {}) {
        return this.service.update(this.buildForm(), params, query);
    }

    /**
     * Destroy Resource
     *
     * @param params
     * @param query
     * @return {*}
     */
    delete(params = {}, query = {}) {
        return this.service.delete(params, query);
    }

    /**
     * Trash Policy
     *
     * @param {Object} params
     * @param {Object} query
     * @return {AxiosPromise}
     */
    remove(params = {}, query = {}) {
        return this.service.remove(params, query);
    }

    /**
     * Restore trashed Policy
     *
     * @param {Object} params
     * @param {Object} query
     * @return {AxiosPromise}
     */
    restore(params = {}, query = {}) {
        return this.service.restore(params, query);
    }

    /**
     * Delete Multiply rows
     *
     * @param params
     * @param query
     * @returns {Promise<AxiosResponse<T>>}
     */
    deletes(params = {}, query = {}) {
        return this.service.deletes(this.buildForm(), params, query, false, params.confirm);
    }
}
