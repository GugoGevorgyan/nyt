/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class DispatcherBoardsPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type String */
    search = "";

    headers = [
        {
            text: "Марка",
            sortable: false,
            value: "mark",
        },
        {
            text: "Цвет",
            sortable: false,
            value: "color",
        },
        {
            text: "Федеральный номер",
            sortable: false,
            value: "state_license_plate",
        },
        {
            text: "Гаражный номер",
            sortable: false,
            value: "garage_number",
        },
        {
            text: "Класс",
            sortable: false,
            value: "classes",
        },
        {
            text: "Парк",
            sortable: false,
            value: "park",
        },
        {
            text: "Водитель",
            sortable: false,
            value: "driver",
        },
        {
            text: "Статус",
            sortable: false,
            value: "status",
        },
    ];

    /**
     * @type {[string, string, string, string]}
     */
    perPages = ["25", "50"];

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

    get getBoards() {
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
