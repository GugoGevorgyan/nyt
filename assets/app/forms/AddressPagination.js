/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class ProfileClientPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    perPages = ["10", "25", "50", "100"];

    headers = [
        { text: "Name", value: "name", sortable: false },
        { text: "Address", value: "address", sortable: false },
        { text: "Actions", value: "action", sortable: false },
    ];

    /** @type String */
    search = "";

    status = null;

    type = null;

    /**
     *
     * @param pagination
     * @param path
     */
    constructor(pagination = {}, path = "") {
        super();

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || "";
        this.last_page_url = pagination.last_page_url || "";
        this.next_page_url = pagination.next_page_url || "";
        this.prev_page_url = pagination.prev_page_url || "";
        this.per_page = pagination.per_page || 10;
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 15;
        this.total = pagination.total || 0;
        this.search = pagination.search || "";
        this.sort_by = pagination.sort_by || [];
        this.sort_desc = pagination.sort_desc || [];
        this.client_id = Number;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let query = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;

        return query;
    }

    get getData() {
        this.loading = true;

        axios
            .get(this.url)
            .then(response => {
                this.client_id = response.data.data[0].client_id;
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
