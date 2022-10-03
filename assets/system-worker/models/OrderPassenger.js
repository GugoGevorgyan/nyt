/** @format */

import Model from "../base/Model";
import store from "../store";

export default class OrderPassenger extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "orderPassenger";

    /**
     * @type {string}
     */
    primaryKey = "client_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "pickerOptions"];

    /**
     * @type {object}
     */
    rules = {
        phone: "required|length:" + store.state.phoneMask.length,
        name: "max:100",
        surname: "max:100",
        patronymic: "max:100",
    };

    /**
     * @param passenger
     */
    constructor(passenger = {}) {
        super("", process.env.MIX_APP_WORKER_URL, "", {});

        this.client_id = passenger.client_id || null;
        this.phone = passenger.phone || null;
        this.name = passenger.name || null;
        this.surname = passenger.surname || null;
        this.patronymic = passenger.patronymic || null;
    }
}
