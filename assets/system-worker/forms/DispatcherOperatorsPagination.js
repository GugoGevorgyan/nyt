/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class DispatcherOperatorsPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type String */
    search = "";

    /**
     * @type {[string, string, string, string]}
     */
    perPages = ["25", "50"];

    headers = [
        {
            text: "Фамилия",
            sortable: false,
            value: "surname",
        },
        {
            text: "Имя",
            sortable: false,
            value: "name",
        },
        {
            text: "Отчество",
            sortable: false,
            value: "patronymic",
        },
        {
            text: "Ник",
            sortable: false,
            value: "nickname",
        },
        {
            text: "Эл. адрес",
            sortable: false,
            value: "email",
        },
        {
            text: "Внутренний номер",
            sortable: false,
            value: "worker_operator.sub_phone.number",
        },
        {
            text: "В системе",
            sortable: false,
            value: "signed",
        },
        {
            text: "На линии",
            sortable: false,
            value: "online",
        },
        {
            text: "Дата регистрации",
            sortable: false,
            value: "created_at",
        },
    ];

    /**
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
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || "25";
        this.total = pagination.total || 0;
        this.search = pagination.search || "";
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
        return query;
    }

    get getOperators() {
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
