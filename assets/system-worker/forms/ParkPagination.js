/** @format */

import Pagination from "./../base/Pagination";
import Park from "./../models/Park";
import _ from "lodash";
import axios from "axios";

export default class ParkPagination extends Pagination {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: "Имя", value: "name" },
        { text: "Регион", value: "region" },
        { text: "Город", value: "city" },
        { text: "Адрес", value: "address" },
        { text: "Управляющий", value: "manager" },
        { text: "Юридическое лицо", value: "entity" },
        { text: "Дата создания", value: "created_at" },
        { text: "Действия", value: "action" },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50"];

    /** @type String */
    search = "";

    /**
     * @param pagination
     */
    constructor(pagination = {}) {
        super(pagination);

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || null;
        this.last_page_url = pagination.last_page_url || null;
        this.next_page_url = pagination.next_page_url || null;
        this.prev_page_url = pagination.prev_page_url || null;
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || path;
        this.from = pagination.from || 1;
        this.to = pagination.to || null;
        this.total = pagination.total || null;
        this._payload = pagination._payload || [];
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let q = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;
        if (this.search) {
            q += `&search=${this.search}`;
        }
        return q;
    }

    get getParks() {
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
