/** @format */

import Pagination from "./../base/Pagination";
import axios from "axios";

export default class ProfileComplaintPagination extends Pagination {
    /** @type Boolean */
    loading = false;

    /**
     * @type {[string, string]}
     */
    perPages = ["10", "20"];

    /** @type String */
    search = "";

    constructor(pagination = {}) {
        super(pagination);

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || null;
        this.last_page_url = pagination.last_page_url || null;
        this.next_page_url = pagination.next_page_url || null;
        this.prev_page_url = pagination.prev_page_url || null;
        this.per_page = pagination.per_page || "10";
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || null;
        this.total = pagination.total || null;
        this.status = pagination.status || null;
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
        if (this.status) {
            q = q + `&status=${this.status}`;
        }
        return q;
    }

    get getData() {
        this.loading = true;

        axios
            .get(this.url)
            .then(response => {
                if (200 === response.status) {
                    for (let key in response.data) {
                        if (response.data.hasOwnProperty(key)) {
                            this[key] = response.data[key];
                        }
                    }
                }

                this.loading = false;
            })
            .catch(() => {
                this.loading = false;
            });
    }
}
