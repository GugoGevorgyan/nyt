/** @format */

import Form from "../base/Form";
import axios from "axios";
import Snackbar from "../facades/Snackbar";

export default class WaybillPagination extends Form {
    /**
     * @type {boolean}
     */
    loading = false;

    /**
     * @type {[string, string]}
     */
    perPages = ["25", "50", "100"];

    /** @type Array */
    selected = [];

    /** @type String */
    search = "";

    /**
     * Filter By Driver
     * @type {[]}
     */
    driverIds = [];

    /**
     * Filter By Parks
     * @type {[]}
     */
    parkIds = [];

    /**
     * Filter By Date
     * @type {[]}
     */
    dateStart = "";

    /**
     * Filter By Date
     * @type {[]}
     */
    dateEnd = "";

    /**
     * All Sum Waybills
     * @type {number}
     */
    waybillsSum = 0.0;

    /**
     *
     * @type {boolean}
     */
    invalidPwd = true;

    /**
     *
     * @type {[]}
     */
    foundDrivers = [];

    /**
     * @type {boolean}
     */
    showCreateWaybillDialog = false;

    /**
     * @type {string}
     */
    searchForDrivers = "";

    /**
     * @type {null}
     */
    createWaybillDriver = null;

    /**
     * @type {number}
     */
    createWaybillDays = 1;
    /**
     * @type {number}
     */
    createWaybillChecked = false;

    /**
     * @type {boolean}
     */
    searchDriversLoading = false;

    headers = [
        { text: "Номер", value: "number", sortable: false },
        { text: "Водитель", value: "driver" },
        { text: "Автомобиль", value: "car" },
        { text: "Цена", value: "transaction_sum" },
        { text: "Годен от", value: "start_time" },
        { text: "Годен до", value: "end_time" },
        { text: "Аннулирован", value: "annulled" },
        { text: "Доп. инфо", value: "additional" },
        { text: "Комментария", value: "comment" },
        { text: "Детали проверки", value: "check_details" },
        { text: "Действия", value: "actions" },
    ];

    /**
     * CandidatePagination constructor
     *
     * @param {Object} pagination
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
        this.from = pagination.from || 1;
        this.to = pagination.to || 25;
        this.total = pagination.total || null;
        this._payload = pagination._payload || [];

        this.search = pagination.search || "";
        this.driverIds = pagination.drivers || [];
        this.parkIds = pagination.parks || [];
        this.dateStart = pagination.dateStart || "";
        this.dateEnd = pagination.dateEnd || "";

        this.waybills;
    }

    /**
     * Request url
     *
     * @return {string}
     */
    get url() {
        let url = this.path;
        let page = this.current_page ? `?page=${this.current_page}` : undefined;
        let perPage = this.per_page ? `&per_page=${this.per_page}` : undefined;
        let search = this.search ? `&search=${this.search}` : undefined;
        let driverIds = this.driverIds ? `&drivers=${this.driverIds}` : undefined;
        let parkIds = this.parkIds ? `&parks=${this.parkIds}` : undefined;
        let dateStart = this.dateStart ? `&date_start=${this.dateStart}` : undefined;
        let dateEnd = this.dateStart ? `&date_end=${this.dateEnd}` : undefined;

        if (page) {
            url += `${page}`;
        }

        if (perPage) {
            url += `${perPage}`;
        }

        if (search) {
            url += `${search}`;
        }

        if (driverIds) {
            url += `${driverIds}`;
        }

        if (parkIds) {
            url += `${parkIds}`;
        }

        if (dateStart) {
            url += `${dateStart}`;
        }

        if (dateEnd) {
            url += `${dateEnd}`;
        }

        return url;
    }

    get waybills() {
        this.loading = true;

        axios
            .get(this.url)
            .then(response => {
                if (200 === response.status) {
                    this.waybillsSum = 0.0;
                    response.data._payload.forEach(item => (this.waybillsSum += +item.transaction_sum));

                    for (let key in response.data) {
                        if (response.data.hasOwnProperty(key)) {
                            this[key] = response.data[key];
                        }
                    }
                }
                this.loading = false;
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
                this.loading = false;
            });
    }

    /**
     * Check Worker Pwd
     * @param id
     * @param password
     */
    checkWorkerPwd(id, password) {
        axios.post("check_pwd", { id, password }).then(valid => {
            if (200 === valid.status) {
                this.invalidPwd = false;
            }
        });
    }

    annulWaybill(waybillId) {
        return axios.put(`waybills/annul/${waybillId}`);
    }

    downloadWaybill(waybillId) {
        axios
            .get(`waybills/print/${waybillId}`, { responseType: "blob" })
            .then(response => {
                let blob = new Blob([response.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "file.xlsx";
                link.click();
                link.remove();
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
            });
    }

    searchDrivers(search, callback) {
        axios
            .get(`waybills/search-drivers/${search}`)
            .then(response => {
                this.foundDrivers = response.data;

                if (typeof callback == "function") {
                    callback();
                }
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
            });
    }

    createWaybillAdd(callback) {
        axios
            .post(`waybills/create`, {
                driver_id: this.createWaybillDriver,
                days: +this.createWaybillDays,
                checked: +this.createWaybillChecked,
            })
            .then(response => {
                if (!response.data.success) {
                    return;
                }

                this.showCreateWaybillDialog = false;
                this.searchForDrivers = "";
                this.createWaybillDriver = null;
                this.searchDriversLoading = false;
                this.foundDrivers = [];
                Snackbar.info(response.data.message);

                "function" === typeof callback ? callback() : "";
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
                "function" === typeof callback ? callback() : "";
            });
    }

    createWaybillRestore() {
        axios
            .post(`waybills/restore-current`, {
                driver_id: this.createWaybillDriver,
            })
            .then(response => {
                if (!response.data.success) {
                    return;
                }

                this.showCreateWaybillDialog = false;
                this.searchForDrivers = "";
                this.createWaybillDriver = null;
                this.searchDriversLoading = false;

                this.foundDrivers = [];

                Snackbar.info(response.data.message);

                if (typeof callback === "function") {
                    callback();
                }
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
            });
    }
}
