/** @format */

import axios from 'axios';
import Form from '../base/Form';

export default class PermissionPagination extends Form {
    /** @type Boolean */
    loading = false;
    /** @type Array */
    headers = [
        { text: 'Name', value: 'name', sortable: false },
        { text: 'Alias', value: 'alias', sortable: false },
        { text: 'Guard Name', value: 'guard_name', sortable: false },
        { text: 'Role', value: 'role', sortable: false },
        { text: 'Description', value: 'description', sortable: false },
        { text: 'Actions', value: 'action', sortable: false },
    ];
    /** @type Array */
    selected = [];

    /**
     * CandidatePagination constructor
     *
     * @param {Object} pagination
     * @param {String} path
     */
    constructor(pagination = {}, path = '') {
        super();

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || '';
        this.last_page_url = pagination.last_page_url || '';
        this.next_page_url = pagination.next_page_url || '';
        this.prev_page_url = pagination.prev_page_url || '';
        this.per_page = pagination.per_page || 10;
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 15;
        this.total = pagination.total || null;
        this.search = pagination.search || null;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        return `${this.path}?page=${this.current_page}&per-page=${this.per_page}&search=${this.search}`;
    }

    getPermissions() {
        this.loading = true;

        axios
            .get(this.url)
            .then(response => {
                if (response.status === 200) {
                    for (let key in response.data) if (response.data.hasOwnProperty(key)) this[key] = response.data[key];
                }
                this.loading = false;
            })
            .catch(error => {
                this.loading = false;
            });
    }
}
