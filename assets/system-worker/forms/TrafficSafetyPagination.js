/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class TrafficSafetyPagination extends Form {
    /** @type Boolean */
    loading = false;

    headers = [
        {
            text: "Классы",
            value: "classes",
        },
        {
            text: "ГАР номер",
            value: "garage_number",
        },
        {
            text: "Марка",
            value: "mark",
        },
        {
            text: "Модель",
            value: "model",
        },
        {
            text: "Год выпуска",
            value: "year",
        },
        {
            text: "Гос. номер",
            value: "state_license_plate",
        },
        {
            text: "Текущий водитель",
            value: "current_driver",
        },
        {
            text: "Водители",
            value: "drivers",
        },
        {
            text: "Статус",
            value: "status",
        },
        {
            text: "Парк",
            value: "park",
        },
        {
            text: "ДТП",
            value: "crashes_count",
        },
        {
            text: "До страхования",
            value: "insurance_days_left",
        },
        {
            text: "До тех. осмотра",
            value: "inspection_days_left",
        },
        {
            text: "Действия",
            value: "action",
        },
    ];

    /**
     * @type {number[]}
     */
    perPages = [25, 50];

    /** @type Array */
    selected = [];

    /**
     * TrafficSafetyPagination constructor
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
        this.per_page = pagination.per_page || 25;
        this.path = pagination.path || path;
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || null;
        this.search = pagination.search || null;
        this.data = pagination.data || [];
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let request = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;

        if (this.search) {
            request += `&search=${this.search}`;
        }

        return request;
    }

    get getCars() {
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
