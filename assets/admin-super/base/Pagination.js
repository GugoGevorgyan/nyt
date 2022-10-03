/** @format */

import axios from 'axios';

export default class Pagination {
    headers = [];

    search = '';

    filterBy = '';

    sortBy = 'name';

    sortDesc = false;

    loading = false;

    itemsPerPage = [10, 15, 25];

    #params = {};

    constructor(pagination = {}) {
        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || '';
        this.last_page_url = pagination.last_page_url || '';
        this.next_page_url = pagination.next_page_url || '';
        this.prev_page_url = pagination.prev_page_url || '';
        this.per_page = pagination.per_page || 10;
        this.path = pagination.path || path;
        /** @type {Array} **/
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 10;
        this.total = pagination.total || null;
    }

    getItems() {
        this.loading = true;

        this.#params['page'] = this.current_page;
        this.#params['per-page'] = this.per_page;

        if (this.search) this.#params['search'] = this.search;
        else delete this.#params['search'];

        if (this[this.filterBy]) this.#params[this.filterBy] = this[this.filterBy];
        else delete this.#params[this.filterBy];

        return axios.get(this.path, { params: this.#params }).then(response => {
            this.current_page = response.data.current_page;
            this.last_page = response.data.last_page;
            this.total = response.data.total;
            this.data = response.data.data;
            this.from = response.data.from;
            this.to = response.data.to;
            this.loading = false;

            return this;
        });
    }

    next() {
        if (this.current_page < this.last_page) this.current_page++;

        return this.getItems();
    }

    previous() {
        if (this.current_page > 1) this.current_page--;

        return this.getItems();
    }

    setPerPage(number) {
        this.per_page = number;

        return this.getItems();
    }

    /**
     * Finding index from data Collection
     *
     * @param {Object} item
     * @param {String} by
     * @return {Number}
     */
    findIndex(item, by = 'id') {
        return this.data.findIndex(dataItem => dataItem[by] === item[by]);
    }
}
