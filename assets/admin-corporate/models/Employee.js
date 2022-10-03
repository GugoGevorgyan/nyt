/** @format */

import Model from "../base/Model";

export default class Employee extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "employee";

    /**
     * @type {string}
     */
    primaryKey = "corporate_client_id";

    /**
     * @type {{}}
     */
    rules = {
        name: "required|alpha",
        surname: "required|alpha",
        patronymic: "",
        phone: "required",
        limit: "required|numeric",
        available: "",
        allow_weekends: "",
        allow_order: "",
        car_classes: {
            required: true,
        },
    };

    constructor(employee = {}) {
        super("company/client", "admin/corporate/", "", {
            create: "create",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });


        this.corporate_client_id = employee.corporate_client_id || null;
        this.client_id = employee.client_id || null;
        this.phoneMask = employee.phoneMask || '';
        this.phone = this.client_id? this.getUsingPhoneAccordinglyPhoneMask(employee.phone): null;
        this.name = employee.name || null;
        this.surname = employee.surname || null;
        this.patronymic = employee.patronymic || null;
        this.limit = employee.limit || '';
        this.available = employee.available || null;
        this.allow_weekends = employee.allow_weekends || false;
        this.allow_order = employee.allow_order || false;
        this.car_classes = employee.car_classes || [];
    };

    getUsingPhoneAccordinglyPhoneMask(phone) {
        // One element is '+' in code; Example '+374';
        return phone.substr(this.phoneMask.indexOf('(') - 1)
    };
}
