/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class CallCenterOrderPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    /** @type String */

    headersFull = [
        {
            text: "Номер",
            sortable: false,
            value: "order_id",
        },
        {
            text: "Статус",
            sortable: false,
            value: "status",
        },
        {
            text: "Класс",
            sortable: false,
            value: "car_class",
        },
        {
            text: "Оплата",
            sortable: false,
            value: "payment_type",
        },
        {
            text: "Тип",
            sortable: false,
            value: "order_type",
        },
        {
            text: "Опции",
            sortable: false,
            value: "car_options",
        },
        {
            text: "Клиент",
            sortable: false,
            value: "client",
        },
        {
            text: "Пасажир",
            sortable: false,
            value: "passenger",
        },
        {
            text: "Борт",
            sortable: false,
            value: "board",
        },
        {
            text: "Откуда",
            sortable: false,
            value: "address_from",
        },
        {
            text: "Встреча",
            sortable: false,
            value: "meet",
        },
        {
            text: "Куда",
            sortable: false,
            value: "address_to",
        },
        {
            text: "Предзаказ",
            sortable: false,
            value: "preorder",
        },
        {
            text: "Начальная цена",
            sortable: false,
            value: "price",
        },
        {
            text: "Цена",
            sortable: false,
            value: "cost",
        },
        {
            text: "Еще",
            sortable: false,
            value: "more",
        },
    ];

    headers = [
        {
            text: "Номер",
            sortable: false,
            value: "order_id",
        },
        {
            text: "Статус",
            sortable: false,
            value: "status",
        },
        {
            text: "Клиент",
            sortable: false,
            value: "client",
        },
        {
            text: "Борт",
            sortable: false,
            value: "board",
        },
        {
            text: "Откуда",
            sortable: false,
            value: "address_from",
        },
        {
            text: "Начальная цена",
            sortable: false,
            value: "price",
        },
        {
            text: "Цена",
            sortable: false,
            value: "cost",
        },
        {
            text: "Еще",
            sortable: false,
            value: "more",
        },
    ];

    perPages = ["10", "25", "50", "100"];

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
        this.to = pagination.to || 25;
        this.total = pagination.total || 0;
        this.search = pagination.search || null;
        this.status = pagination.status || null;
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
            query += `&search=${encodeURIComponent(this.search.trim())}`;
        }
        if (this.sort_by) {
            query += `&sort_by=${this.sort_by}`;
        }
        if (this.sort_desc) {
            query += `&sort_desc=${this.sort_desc}`;
        }
        if (this.status) {
            query += `&status=${this.status}`;
        }
        if (this.type) {
            query += `&type=${this.type}`;
        }
        return query;
    }

    get getOrders() {
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
