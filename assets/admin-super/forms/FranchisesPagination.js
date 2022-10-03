/** @format */

import axios from 'axios';
import Form from '../base/Form';

export default class FranchisesPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: 'Название', value: 'name', sortable: false },
        { text: 'Модули', value: 'modules', sortable: false },
        { text: 'Администраторы', value: 'admins', sortable: false },
        { text: 'Регионы деятельности', value: 'regions', sortable: false },
        { text: 'Создан', value: 'created_at', sortable: false },
        { text: 'Действия', value: 'action', sortable: false },
    ];

    /** @type String */
    search = '';

    /**
     * @type {[string, string, string, string]}
     */
    perPages = ['25', '50'];

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
        this.per_page = pagination.per_page || '25';
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || '25';
        this.total = pagination.total || null;
        this.search = pagination.search || null;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let q = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;
        if (this.search) {
            q = q + `&search=${this.search}`;
        }
        return q;
    }

    getFranchises() {
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

    /**
     * Delete franchise from paginated data
     *
     * @param {Number} id
     * @return {void}
     */
    deleteFranchise(id) {
        let index = this.data.findIndex(item => item.franchise_id === id);

        this.data.splice(index, 1);
    }
}
