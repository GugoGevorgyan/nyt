/** @format */

import Pagination from "./../base/Pagination";
import axios from "axios";

export default class FeedbackPagination extends Pagination {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: "Заказ", value: "order" },
        { text: "Клиент", value: "client" },
        { text: "Водитель", value: "driver" },
        { text: "Борт", value: "board" },
        { text: "Текст", value: "text" },
        { text: "Оценка", value: "assessment" },
        { text: "Кто оставил", value: "writer" },
        { text: "На кого", value: "reader" },
        { text: "Тип отзыва", value: "type" },
        { text: "Цена", value: "cost" },
        { text: "Дата создания", value: "created_at" },
        { text: "Статус заказа", value: "status" },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50", "100"];

    /** @type String */
    search = "";

    constructor(pagination = {}) {
        super(pagination);

        this.current_page = pagination.current_page || 1;
        this.last_page = pagination.last_page || null;
        this.first_page_url = pagination.first_page_url || null;
        this.last_page_url = pagination.last_page_url || null;
        this.next_page_url = pagination.next_page_url || null;
        this.prev_page_url = pagination.prev_page_url || null;
        this.per_page = pagination.per_page || "25";
        this.path = pagination.path || path;
        this.data = pagination.data || [];
        this.from = pagination.from || 1;
        this.to = pagination.to || null;
        this.total = pagination.total || null;
        this.status = pagination.status || null;
        this.type = pagination.type || null;
        this.writer = pagination.writer || null;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let q = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;
        if (this.search) {
            q += `&search=${this.search}`;
        }
        if (this.status) {
            q += `&status=${this.status}`;
        }
        if (this.type) {
            q += `&type=${this.type}`;
        }
        if (this.writer) {
            q += `&writer=${this.writer}`;
        }
        return q;
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
            .catch(() => {
                this.loading = false;
            });
    }
}
