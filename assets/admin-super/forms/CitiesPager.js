/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class CitiesPager extends Form {
    /** @type Boolean */
    loading = false;
    /**
     * @type {number[]}
     */
    perPages = [25, 50, 100];
    /**
     * @type {[]}
     */
    selected = [];
    /** @type Array */
    headers = [
        { text: "Name", value: "name" },
        { text: "Country", value: "country" },
        { text: "ISO", value: "iso_2" },
        { text: "Created", value: "created_at" },
        { text: "Actions", value: "action", sortable: false },
    ];

    /**
     * CandidatePagination constructor
     *
     * @param {Object} pager
     * @param {String} path
     */
    constructor(pager = {}, path = "") {
        super();

        this.current_page = pager.current_page || 1;
        this.last_page = pager.last_page || null;
        this.first_page_url = pager.first_page_url || "";
        this.last_page_url = pager.last_page_url || "";
        this.next_page_url = pager.next_page_url || "";
        this.prev_page_url = pager.prev_page_url || "";
        this.per_page = pager.per_page || 10;
        this.path = pager.path || path;
        this.from = pager.from || 1;
        this.to = pager.to || 15;
        this.total = pager.total || null;
        this._payload = pager._payload || [];
        this.search = pager.search || null;
        this.countries = pager.countries || [];
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        return `${this.path}?page=${this.current_page}&per_page=${this.per_page}&search=${this.search}&countries=${this.countries}`;
    }

    get cities() {
        this.loading = true;

        axios
            .get(this.url)
            .then(response => {
                if (response.status === 200) {
                    for (let key in response.data)
                        if (response.data.hasOwnProperty(key)) this[key] = response.data[key];
                }
                this.loading = false;
            })
            .catch(error => {
                this.loading = false;
            });
    }
}
