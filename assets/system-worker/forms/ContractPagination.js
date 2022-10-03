/** @format */

import Form from "../base/Form";
import axios from "axios";
import Snackbar from "../facades/Snackbar";

export default class ContractPagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        {
            text: "Фамилия",
            sortable: false,
            value: "driver.driver_info.surname",
        },
        {
            text: "Имя",
            sortable: false,
            value: "driver.driver_info.name",
        },
        {
            text: "Отчество",
            sortable: false,
            value: "driver.driver_info.patronymic",
        },
        {
            text: "Ник",
            sortable: true,
            value: "driver.nickname",
        },
        {
            text: "Автомобиль",
            sortable: false,
            value: "car",
        },
        {
            text: "Тип работы",
            sortable: false,
            value: "type.type",
        },
        {
            text: "График",
            sortable: false,
            value: "graphic.name",
        },
        {
            text: "Дата подписания",
            sortable: true,
            value: "signing_day",
        },
        {
            text: "Дата истечения",
            sortable: true,
            value: "expiration_day",
        },
        {
            text: "Длительность",
            sortable: true,
            value: "duration",
        },
        {
            text: "Активность",
            sortable: false,
            value: "active",
        },
        {
            text: "Расторгнуть",
            sortable: false,
            value: "terminate",
        },
        {
            text: "Дата создания",
            sortable: false,
            value: "created_at",
        },
        {
            text: "Контракт",
            sortable: false,
            value: "contract_download",
        },
        {
            text: "Цена контракта",
            sortable: false,
            value: "busy_days_price",
            align: "center",
        },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50"];

    /** @type Array */
    selected = [];

    /** @type String */
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
        this.data = pagination.data || [];
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
        if (this.active) {
            query += `&active=${this.active}`;
        }
        return query;
    }

    get getContracts() {
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

    downloadContract(contractId) {
        axios
            .get(`park-management/download-contract/${contractId}`, { responseType: "blob" })
            .then(response => {
                let blob = new Blob([response.data], { type: "application/pdf" });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "test.pdf";
                link.click();
            })
            .catch(error => {
                Snackbar.error(error.data.message);
            });
    }
}
