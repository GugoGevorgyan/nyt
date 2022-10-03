/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class CorporateClientsPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    perPages = ["10", "25", "50", "100"];

    headers = [
        { text: "Фамилия", value: "surname", align: "left", sortable: false },
        { text: "Имя", value: "name", align: "left", sortable: false },
        { text: "Отчество", value: "patronymic", align: "left", sortable: false },
        { text: "Телефон", value: "client.phone", sortable: false },
        { text: "Лимит", value: "limit", sortable: false },
        { text: "Потрачено", value: "spent", sortable: false },
        { text: "Заказы", value: "allow_order", sortable: false },
        { text: "Выходные", value: "allow_weekends", sortable: false },
        { text: "Статус", value: "client.in_order", sortable: false },
        { text: "Actions", value: "actions", align: "right", sortable: false },
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
        if (this.sort_by) {
            query = query + `&sort_by=${this.sort_by}`;
        }
        if (this.sort_desc) {
            query = query + `&sort_desc=${this.sort_desc}`;
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
            .catch(error => {
                this.loading = false;
            });
    }
}
