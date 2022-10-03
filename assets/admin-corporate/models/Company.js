/** @format */

import Model from "../base/Model";
import axios from "axios";
import store from "../store";

export default class Company extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "Company";

    /**
     * @type {string}
     */
    primaryKey = "company_id";

    /**
     * @type {string[]}
     */
    hidden = ["contract_scan"];

    loading = false;

    /**
     * @type {object}
     */
    rules = {
        name: 'required|min:3|max:32',
        entity_id: 'required',
        email: 'required|email',
        details: 'required',
        phone: 'required|length:' + (store.state.phoneMask ? store.state.phoneMask.length : 0),
        additional_phones: 'array',
        order_canceled_timeout: 'required|numeric',
        period: 'required|numeric',
        frequency: 'required|numeric',
        limit: 'required|numeric',
        code: 'required|numeric|max:5',
        contract_start: 'required|date_format:yyyy-mm-dd',
        contract_end: 'required|date_format:yyyy-mm-dd',
    };

    /**
     * @param company
     */
    constructor(company = {}) {
        super('company', process.env.MIX_APP_CORPORATE_URL, '', {
            delete: "delete",
            create: "create",
            update: "",
        });

        this.company_id = company.company_id || null;
        this.entity_id = company.entity_id || null;
        this.franchise_id = company.franchise_id || null;
        this.phone = company.phone || null;
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

        this.phones.length ?? this.setPhones();
    }

    setPhones() {
        for (let i = 0; i < this.phones.length; i++) {
            0 === i ? (this.phone = this.phones[i].number) : this.additional_phones.push(this.phones[i].number);
        }
    }

    get companyInfo() {
        axios.get("/admin/corporate/company/info").then(response => {
            let company = response.data;

            this.company_id = company.company_id;
            this.franchise_id = company.franchise_id;
            this.name = company.name;
            this.entity_id = company.entity_id;
            this.email = company.email;
            this.details = company.details;
            this.phone = company.phone;
            this.phones = company.phones.map(phone => phone.number);
            this.order_canceled_timeout = company.order_canceled_timeout;
            this.period = company.period;
            this.frequency = company.frequency;
            this.logo = company.logo;
            this.limit = company.limit;
            this.code = company.code;
            this.contract_scan = company.contract_scan;
            this.contract_start = company.contract_start;
            this.contract_end = company.contract_end;
        });
    }
}
