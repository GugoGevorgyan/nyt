/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class Schedule extends Form {
    /** @type Boolean */
    loading = false;

    headers = [];

    /**
     * @type {number[]}
     */
    perPages = [25, 50];

    /** @type Array */
    selected = [];

    /** @type String */
    search = "";

    /**
     * TrafficSafetyPagination constructor
     *
     * @param {Object} pagination
     */
    constructor(pagination = {}) {
        super();

        this.year = pagination.year || new Date().getFullYear();
        this.month = pagination.month || new Date().getMonth() + 1;
        this.park = pagination.park || null;
        this.driver_type = pagination.driver_type || null;
        this.schedule_type = pagination.schedule_type || null;

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || "";
        this.last_page_url = pagination.last_page_url || "";
        this.next_page_url = pagination.next_page_url || "";
        this.prev_page_url = pagination.prev_page_url || "";
        this.per_page = pagination.per_page || 25;
        this.path = pagination.path || "/paginate";
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || null;
        this.search = pagination.search || "";
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let request = `${this.path}?current_page=${this.current_page}&per_page=${this.per_page}`;

        request = this.search ? request + `&search=${this.search}` : request;
        request = this.year ? request + `&year=${this.year}` : request;
        request = this.month ? request + `&month=${this.month}` : request;
        request = this.park ? request + `&park=${this.park}` : request;
        request = this.driver_type ? request + `&driver_type=${this.driver_type}` : request;
        request = this.schedule_type ? request + `&schedule_type=${this.schedule_type}` : request;

        return request;
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
