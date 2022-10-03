/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class ContractSigningPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        {
            text: "Фамилия",
            sortable: true,
            value: "driver_info.surname",
        },
        {
            text: "Имя",
            sortable: true,
            value: "driver_info.name",
        },
        {
            text: "Отчество",
            sortable: true,
            value: "driver_info.patronymic",
        },
        {
            text: "Тип работы",
            sortable: true,
            value: "type.type",
        },
        {
            text: "Тип контракта",
            sortable: true,
            value: "subtype.name",
        },
        {
            text: "Контрацкты",
            sortable: false,
            value: "contracts",
        },
        {
            text: "Распечатать контракт",
            sortable: false,
            value: "print",
        },
    ];

    /** @type Array */
    selected = [];

    /** @type String */
    search = "";

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
        this.to = pagination.to || 25;
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
        return `${this.path}?page=${this.current_page}&per-page=${this.per_page}&search=${this.search}&sort_by=${this.sort_by}&sort_desc=${this.sort_desc}`;
    }

    get getDrivers() {
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
