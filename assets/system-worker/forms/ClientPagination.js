/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class ClientPagination extends Form {
    /**
     * @type {boolean}
     */
    loading = false;

    /**
     * @type {[{text: string, sortable: boolean, value: string}]}
     */
    headers = [
        {
            text: "Имя",
            sortable: false,
            value: "client.name",
        },
        {
            text: "Фамилия",
            sortable: false,
            value: "client.surname",
        },
        {
            text: "Отчество",
            sortable: false,
            value: "client.patronymic",
        },
        {
            text: "Email",
            sortable: false,
            value: "client.email",
        },
        {
            text: "Телефон",
            sortable: false,
            value: "client.phone",
        },
        {
            text: "Средняя Оценка",
            sortable: true,
            value: "client.mean_assessment",
        },
        {
            text: "Оценили водители",
            sortable: true,
            value: "assessed_count",
        },
        {
            text: "Количество заказов",
            sortable: true,
            value: "orders_count",
        },
        {
            text: "Отмененные заказы",
            sortable: true,
            value: "canceled_orders_count",
        },
        {
            text: "Сумма заказов",
            sortable: true,
            value: "orders_sum",
        },
        {
            text: "В заказе",
            sortable: false,
            value: "client.in_order",
        },
        {
            text: "Зарегистрирован",
            sortable: false,
            value: "client.created_at",
        },
    ];
    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50", "100"];
    /**
     * @type {[]}
     */
    selected = [];
    /**
     * @type {string}
     */
    search = "";

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
        this.per_page = Number(pagination.per_page) || 25;
        this.path = pagination.path || path;
        this._payload = pagination._payload || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || 0;
        this.search = pagination.search || "";
        this.sort_by = pagination.sort_by || [];
        this.sort_desc = pagination.sort_desc || [];
        this.active = pagination.active || undefined;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let query = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;

        if (this.search) {
            query += `&search=${encodeURIComponent(this.search.trim())}`;
        }
        if (this.sort_by) {
            query += `&sort_by=${this.sort_by}`;
        }
        if (this.sort_desc) {
            query += `&sort_desc=${this.sort_desc}`;
        }
        if (this.active) {
            query += `&active=${this.active}`;
        }

        return query;
    }

    get clients() {
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
