/** @format */

import axios from "axios";
import Form from "../base/Form";

export default class CandidatePagination extends Form {
    /** @type Boolean */
    loading = false;

    /** @type Array */
    headers = [
        { text: "Репетитор", value: "tutor.name", sortable: false },
        { text: "Фамилия", value: "info.surname", sortable: false },
        { text: "Имя", value: "info.name", sortable: false },
        { text: "Отчество", value: "info.patronymic", sortable: false },
        { text: "Фотография", value: "photo", sortable: false },
        { text: "Телефон", value: "phone", sortable: false },
        { text: "Категории транспортных средств", value: "license_types", sortable: false },
        { text: "Номер водительского удостоверения", value: "info.license_code", sortable: false },
        { text: "Стаж", value: "experience", sortable: false },
        { text: "Количество контрактов", value: "contracts_count", sortable: false },
        { text: "Дата регистрации", value: "created_at", sortable: false },
        { text: "Действия", value: "action", sortable: false },
    ];

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50"];

    /** @type String */
    search = "";

    /**
     * CandidatePagination constructor
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
        this.search = pagination.search || "";
        this.data = pagination.data || [];
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let q = `${this.path}?page=${this.current_page}&per_page=${this.per_page}`;
        if (this.search) {
            q = q + `&search=${this.search}`;
        }
        return q;
    }

    get getCandidates() {
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

    /**
     * Delete franchise from paginated data
     *
     * @param {Array} id
     * @return {void}
     */
    deleteCandidate(id) {
        let index;

        if (1 <= id.length) {
            for (let ids of id) {
                let index = this.data.findIndex(item => item.driver_candidate_id === ids.driver_candidate_id);
                this.data.splice(index, id.length);
            }
        }

        index = this.data.findIndex(item => item.driver_candidate_id === id);
        this.data.splice(index, 1);
    }
}
