/** @format */

import Model from "../base/Model";
import store from "../store";

export default class Company extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "company";

    /**
     * @type {string}
     */
    primaryKey = "company_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "phones", "contract_scan"];

    /**
     * @type {object}
     */
    rules = {
        name: "required|min:3|max:32",
        address: "min:3|max:120",
        entity_id: "required",
        email: "email",
        details: "max:1500",
        phone: "length:" + store.state.phoneMask.length,
        additional_phones: "array",
        order_canceled_timeout: "numeric",
        period: "numeric",
        frequency: "numeric",
        limit: "numeric",
        code: "required|numeric|max:5",
        contract_start: "date_format:yyyy-mm-dd",
        contract_end: "date_format:yyyy-mm-dd",
        contract_scan_file: "ext:jpeg,jpg,png",
    };

    /**
     * @param company
     */
    constructor(company = {}) {
        super("company", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "create",
            update: "update",
        });

        this.company_id = company.company_id || null;
        this.address = company.address || null;
        this.entity_id = company.entity_id || null;
        this.phone = company.phone || null;
        this.additional_phones = company.additional_phones || [];
        this.phones = company.phones || [];
        this.name = company.name || null;
        this.email = company.email || null;
        this.details = company.details || null;
        this.order_canceled_timeout = company.order_canceled_timeout || null;
        this.period = company.period || null;
        this.frequency = company.frequency || null;
        this.limit = company.limit || null;
        this.code = company.code || null;
        this.contract_start = company.contract_start || null;
        this.contract_end = company.contract_end || null;
        this.contract_scan = company.contract_scan || null;
        this.contract_scan_file = company.contract_scan_file || undefined;

        this.admin_added = company.admin_added || false;

        this.setPhones();
    }

    setPhones() {
        let i;
        for (i = 0; i < this.phones.length; i++) {
            0 === i ? (this.phone = this.phones[i].number) : this.additional_phones.push(this.phones[i].number);
        }
    }
}
