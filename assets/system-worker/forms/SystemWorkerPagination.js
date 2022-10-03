/** @format */

import Form from "../base/Form";
import axios from "axios";
import Snackbar from "../facades/Snackbar";

export default class SystemWorkerPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: "Фио", value: "worker", sortable: false },
        { text: "Телефон", value: "phone", sortable: false },
        { text: "Эл. адрес", value: "email", sortable: false },
        { text: "Роли", value: "roles", sortable: false },
        { text: "Активность", value: "activity", sortable: false },
        { text: "Дата регистрации", value: "created_at", sortable: false },
        { text: "Действия", value: "action", sortable: false },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50"];

    /** @type Array */
    selected = [];

    /** @type String */
    search = null;

    /**
     * CandidatePagination constructor
     *
     * @param {Object} pagination
     */
    constructor(pagination = {}) {
        super();

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || "";
        this.last_page_url = pagination.last_page_url || "";
        this.next_page_url = pagination.next_page_url || "";
        this.prev_page_url = pagination.prev_page_url || "";
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || "";
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || null;

        this.role_ids = pagination.role_ids || [];
        this.getWorkers;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let url = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;

        let role_ids = this.role_ids.length ? `&role_ids=${this.role_ids}` : undefined;
        let search = this.search ? `&search=${this.search}` : undefined;

        if (role_ids) {
            url += `${role_ids}`;
        }

        if (search) {
            url += `${search}`;
        }

        return url;
    }

    get getWorkers() {
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
                if (error.response && error.response.status === 422) {
                    Snackbar.error(error.response.data.message);
                }
                this.loading = false;
            });
    }
}
