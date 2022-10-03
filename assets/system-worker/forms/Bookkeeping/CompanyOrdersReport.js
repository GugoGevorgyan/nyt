import Form from "../../base/Form";
import axios from "axios";
import Snackbar from "../../facades/Snackbar";
import moment from "moment";

export default class CompanyOrdersReport extends Form {
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
            text: "Номер",
            sortable: false,
            value: "code",
        },
        {
            text: "Наименование организации",
            sortable: false,
            value: "name",
        },
        {
            text: "Сумма",
            sortable: false,
            value: "cost",
        },
        {
            text: "НДС (%)",
            sortable: false,
            value: "vat",
        },
        {
            text: "Сумма с НДС",
            sortable: false,
            value: "total_with_VAT",
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
        this.path = pagination.path || "company-orders-report/paginate";
        this.download_path = pagination.download_path || "company-orders-report/download";
        this.from = pagination.from || 1;
        this.total = pagination.total || 0;
        this.sum = pagination.sum || 0;
        this._payload = pagination._payload || [];

        /*SORTS*/
        this.search = pagination.search || "";
        this.sort_by = pagination.sort_by || [];
        this.sort_desc = pagination.sort_desc || [];
        this.company = pagination.company || 0;
        this.date_start = pagination.date_start || moment().startOf("month").format("YYYY-MM-DD");
        this.date_end = pagination.date_end || moment().format("YYYY-MM-DD");
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
        if (this.company) {
            query += `&company=${this.company}`;
        }
        if (this.date_start) {
            query += `&date_start=${this.date_start}`;
        }
        if (this.date_end) {
            query += `&date_end=${this.date_end}`;
        }

        return query;
    }

    items() {
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

    get download_url() {
        let query = this.download_path;

        if (this.current_page) {
            query += `?page=${this.current_page}`;
        }
        if (this.per_page) {
            query += `&per_page=${this.per_page}`;
        }
        if (this.search) {
            query += `&search=${encodeURIComponent(this.search.trim())}`;
        }
        if (this.company) {
            query += `&company=${this.company}`;
        }
        if (this.date_start) {
            query += `&date_start=${this.date_start}`;
        }
        if (this.date_end) {
            query += `&date_end=${this.date_end}`;
        }

        return query;
    }

    download() {
        this.loading = true;

        axios
            .get(this.download_url, {
                responseType: "blob",
            })
            .then(response => {
                if (200 !== response.status) {
                    return;
                }

                let blob = new Blob([response.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "file.xlsx";
                link.click();
                link.remove();
                this.loading = false;
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);

                this.loading = false;
            });
    }
}
