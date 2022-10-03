/** @format */

import Form from "../../base/Form";
import axios from "axios";

export default class AllTransaction extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    /**
     * @type {[string, string, string, string]}
     */
    perPages = ["10", "25", "50", "100"];

    /** @type Array */
    headers = [
        {
            text: "Number",
            sortable: true,
            value: "number",
        },
        {
            text: "тип транзакции",
            sortable: false,
            value: "type.name",
        },
        {
            text: "часть ФР.",
            sortable: true,
            value: "franchise_cost",
        },
        {
            text: "часть сторонника",
            sortable: true,
            value: "side_cost",
        },
        {
            text: "остаток",
            sortable: true,
            value: "remainder",
        },
        {
            text: "общая сумма",
            sortable: true,
            value: "amount",
        },
        {
            text: "направление",
            sortable: false,
            value: "out",
        },
        {
            text: "коммент",
            sortable: false,
            value: "comment",
        },
        {
            text: "завершен",
            sortable: false,
            value: "payed",
        },
        {
            text: "дата транзакции",
            sortable: true,
            value: "created",
        },
        {
            text: "Actions",
            sortable: false,
            value: "actions",
        },
    ];

    /**
     *
     * @param pagination
     */
    constructor(pagination = {}) {
        super();

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || "";
        this.last_page_url = pagination.last_page_url || "";
        this.next_page_url = pagination.next_page_url || "";
        this.prev_page_url = pagination.prev_page_url || "";
        this.per_page = pagination.per_page || 25;
        this.path = pagination.path || "all/paginate";
        this.from = pagination.from || 1;
        this.total = pagination.total || 0;
        this.sum = pagination.sum || 0;
        this._payload = pagination._payload || [];

        /*SORTS*/
        this.payed = pagination.payed || false;
        this.search = pagination.search || "";
        this.sort_by = pagination.sort_by || [];
        this.sort_desc = pagination.sort_desc || [];
        this.driver = pagination.driver || null;
        this.payment_types = pagination.payment_types || [];
        this.transaction_types = pagination.transaction_types || [];
        this.parks = pagination.parks || [];
        this.date_start = pagination.date_start || "";
        this.date_end = pagination.date_end || "";
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let query = this.path;

        if (this.current_page) {
            query += `?page=${this.current_page}`;
        }
        if (this.per_page) {
            query += `&per_page=${this.per_page}`;
        }
        if (this.search) {
            query += `&search=${encodeURIComponent(this.search.trim())}`;
        }
        if (this.sort_by) {
            query += `&sort_by=${this.sort_by}`;
        }
        if (this.sort_desc) {
            query += `&sort_desc=${this.sort_desc}`;
        }
        if (this.parks) {
            query += `&parks=${this.parks}`;
        }
        if (this.driver) {
            query += `&driver=${this.driver}`;
        }
        if (this.payment_types) {
            query += `&payment_types=${this.payment_types}`;
        }
        if (this.date_start) {
            query += `&date_start=${this.date_start}`;
        }
        if (this.date_end) {
            query += `&date_end=${this.date_end}`;
        }
        if (true === this.payed || false === this.payed) {
            query += `&payed=${this.payed}`;
        }
        if (this.transaction_types) {
            query += `&trans_types=${this.transaction_types}`;
        }

        return query;
    }

    get items() {
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
