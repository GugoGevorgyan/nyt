/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class CarCrashPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    /** @type String */
    search = "";

    /**
     * CarCrashPagination constructor
     *
     * @param {Object} pagination
     * @param {String} path
     */
    constructor(pagination = {}, path = "") {
        super();

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || "";
        this.last_page_url = pagination.last_page_url || "";
        this.next_page_url = pagination.next_page_url || "";
        this.prev_page_url = pagination.prev_page_url || "";
        this.per_page = pagination.per_page || 15;
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 10;
        this.total = pagination.total || null;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        return `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;
    }

    get getCrashes() {
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
            .catch(error => {
                this.loading = false;
            });
    }
}
