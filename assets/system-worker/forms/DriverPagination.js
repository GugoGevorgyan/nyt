/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class DriverPagination extends Form {
    /** @type Boolean */
    loading = false;

    selected = [];

    /** @type Array */
    headers = [
        {
            text: "Фио",
            sortable: false,
            value: "driver",
        },
        {
            text: "Телефон",
            sortable: false,
            value: "phone",
        },
        {
            text: "Эл. адрес",
            sortable: false,
            value: "driver_info.email",
        },
        {
            text: "Рейтинг",
            sortable: false,
            value: "rating",
        },
        {
            text: "Борт",
            sortable: false,
            value: "board",
        },
        {
            text: "Парк",
            sortable: false,
            value: "park",
        },
        {
            text: "График",
            sortable: false,
            value: "graphic",
        },
        {
            text: "Тип водителя",
            sortable: false,
            value: "type",
        },
        {
            text: "Тип контракта",
            sortable: false,
            value: "contract_type",
        },
        {
            text: "Активность",
            sortable: false,
            value: "activity",
        },
        {
            text: "Заблокирован",
            sortable: false,
            value: "lockes.locked",
        },
        {
            text: "Зарегистрирован",
            sortable: false,
            value: "created_at",
        },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50", "100"];

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
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || null;
        this.search = pagination.search || "";
        this.park = pagination.park || "";
        this.type = pagination.type || "";
        this.activity = pagination.activity || "";
        this.contract = pagination.contract || "";
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let url = `${this.path}?page=${this.current_page}&per_page=${this.per_page}&search=${this.search}`;

        if (this.park) {
            url = url + "&park=" + this.park;
        }
        if (this.type) {
            url = url + "&type=" + this.type;
        }

        url = url + "&activity=" + this.activity;
        url = url + "&contract=" + this.contract;

        return url;
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
            .catch(() => {
                this.loading = false;
            });
    }
}
