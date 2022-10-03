/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class OrdersPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    selected = [];

    perPages = ["10", "25", "50", "100"];

    headers = [
        { text: "Номер заказа", value: "order.order_id", align: "left", sortable: false },
        { text: "СЛИП", value: "order.slip", sortable: false },
        { text: "Статус", value: "status", sortable: false },
        { text: "Контактный телефон", value: "client.phone", sortable: false },
        { text: "Контакты персона", value: "client.phone", sortable: false },
        { text: "Телефон пассажира", value: "client.passenger.phone", sortable: false },
        { text: "Имя пассажира", value: "client.passenger.name", sortable: false },
        { text: "Откуда", value: "order.from", sortable: false },
        { text: "Время заказа ", value: "order.created", sortable: false },
        { text: "Маршрут", value: "road", sortable: false },
        { text: "Информация", value: "info", sortable: false },
        { text: "Цена", value: "order.price", sortable: false },
        { text: "Действия", value: "actions" },
    ];

    /** @type String */
    search = "";

    /**
     * @type {null}
     */
    status = null;

    /**
     * @type {null}
     */
    type = null;

    /**
     * @type {string}
     */
    date_start = null;

    /**
     * @type {null}
     */
    date_end = null;

    /**
     * @type {null}
     */
    total_coast = null;

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
        this.from = pagination.from || 1;
        this.to = pagination.to || 15;
        this.total = pagination.total || 0;
        this.search = pagination.search || "";
        this.sort_by = pagination.sort_by || [];
        this.sort_desc = pagination.sort_desc || [];
        this.date_start = pagination.date_start || null;
        this.date_end = pagination.date_end || null;
        this._payload = pagination._payload || [];
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let query = `${this.path}?page=${this.current_page}&per-page=${this.per_page}`;
        if (this.sort_by) {
            query = query + `&sort_by=${this.sort_by}`;
        }
        if (this.sort_desc) {
            query = query + `&sort_desc=${this.sort_desc}`;
        }
        if (this.status) {
            query = query + `&status=${this.status}`;
        }
        if (this.type) {
            query = query + `&type=${this.type}`;
        }
        if (this.date_start) {
            query = query + `&date_start=${this.date_start}`;
        }
        if (this.date_end) {
            query = query + `&date_end=${this.date_end}`;
        }

        return query;
    }

    generateExcell(id, datas, name) {
        axios
            .post("generate_excel", {
                date_start: this.date_start || false,
                date_end: this.date_end || false,
                status: this.status || false,
                type: this.type || false
            }, { responseType: "blob" })
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", `${name}.xlsx`);
                document.body.appendChild(link);
                link.click();
            })
            .catch(error => {
                console.log(error);
            });
    }

    printExcellData(company_id, datas, name) {
        return axios.post("print_excel", {
            date_start: this.date_start || false,
            date_end: this.date_end || false,
            status: this.status || false,
            type: this.type || false
        });
    }

    get datas() {
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

    cancelOrder(order_id, client_id, password) {
        let formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("password", password);

        return axios.post(`${process.env.MIX_APP_CORPORATE_URL}cancel_order/${order_id}/${client_id}`, formData);
    }
}
