/** @format */

import Model from "../base/Model";
import store from "../store";

export default class AdminCorporate extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "adminCorporate";

    /**
     * @type {string}
     */
    primaryKey = "admin_corporate_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl"];

    /**
     * @type {object}
     */
    rules = {
        name: "min:3|max:32",
        surname: "min:3|max:32",
        patronymic: "min:3|max:32",
        email: "required|email|min:3|max:32",
        phone: "length:" + store.state.phoneMask.length,
        password: "required|min:6|max:32",
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

        this.admin_corporate_id = company.admin_corporate_id || null;
        this.phone = company.phone || null;
        this.name = company.name || null;
        this.surname = company.surname || null;
        this.patronymic = company.patronymic || null;
        this.email = company.email || null;
        this.password = company.password || null;
    }
}
