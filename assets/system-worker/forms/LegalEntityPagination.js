/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class LegalEntityPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: "Название", value: "name", align: "left", sortable: false },
        { text: "Тип", value: "type", align: "left", sortable: false },
        { text: "Эл. адрес", value: "email", sortable: false },
        { text: "Телефон", value: "phone", sortable: false },
        { text: "Страна", value: "country.name", sortable: false },
        { text: "Регион", value: "region.name", sortable: false },
        { text: "Город", value: "city.name", sortable: false },
        { text: "Адрес", value: "address", sortable: false },
        { text: "ZIP код", value: "zip_code", sortable: false },
        { text: "Дата создания", value: "created_at", sortable: false },
        { text: "Действия", value: "action", sortable: false },
    ];

    /** @type Array */
    selected = [];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50"];

    /**
     * ContractSigningPagination constructor
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
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || "25";
        this.total = pagination.total || 0;
        this.search = pagination.search || null;
        this.type = pagination.type || null;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let query = `${this.path}?page=${this.current_page}&per-page=${this.per_page}`;
        if (this.search) {
            query = query + `&search=${encodeURIComponent(this.search.trim())}`;
        }
        if (this.type) {
            query = query + `&type=${this.type}`;
        }
        return query;
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
