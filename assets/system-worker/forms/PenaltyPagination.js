/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class PenaltyPagination extends Form {
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
            value: "current_debt.driver_info.name",
        },
        {
            text: "Фамилия",
            sortable: false,
            value: "current_debt.driver_info.surname",
        },
        {
            text: "Телефон",
            sortable: false,
            value: "current_debt.phone",
            align: 'center',
            width: '200px'
        },
        {
            text: "Идентификатор наказания",
            sortable: false,
            value: "penalty.offense_id",
            align: 'center',
        },
        {
            text: "Автомобиль",
            sortable: false,
            value: "car_full_name",
        },
        {
            text: "ПТС",
            sortable: true,
            value: "current_debt.car.pts_number",
            align: 'center',
        },
        {
            text: "СТС",
            sortable: true,
            value: "current_debt.car.sts_number",
            align: 'center',
        },
        {
            text: "Название парка",
            sortable: false,
            value: "current_debt.park.name",
            align: 'center',
        },
        {
            text: "Дата и место нарушения",
            sortable: false,
            align: 'center',
            value: "penalty.offense_date",
            width: "5%"
        },
        {
            text: "Место нарушения на карте",
            sortable: false,
            align: 'center',
            value: "place",
            width: '5%'
        },
        {
            text: "Период оплаты",
            sortable: false,
            value: "payment_period",
            align: 'center',
            width: '200px'
        },
        {
            text: "Стоимость",
            sortable: false,
            value: "cost",
        },
        {
            text: "Водитель оплатил NJT",
            sortable: false,
            align: 'center',
            value: "firm_paid",
            width: '5%'
        },
        {
            text: "Статус",
            sortable: false,
            align: 'center',
            value: "penalty.status",
            width: '5%'
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
        this.date_start = pagination.date_start || [];
        this.date_end = pagination.date_end || [];
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
        if (this.date_start) {
            query += `&date_start=${this.date_start}`;
        }
        if (this.date_end) {
            query += `&date_end=${this.date_end}`;
        }

        return query;
    }

    get penalties() {
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
