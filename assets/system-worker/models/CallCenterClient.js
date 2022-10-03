/** @format */

import Model from "../base/Model";
import store from "../store";

export default class CallCenterClient extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "client";

    /**
     * @type {string}
     */
    primaryKey = "client_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl"];

    /**
     * @type {object}
     */
    rules = {
        phone: "required|length:" + store.state.phoneMask.length,
        name: "max:100",
        surname: "max:100",
        patronymic: "max:100",
        email: "email|max:100",
    };

    /**
     * @param client
     */
    constructor(client = {}) {
        super("call-center/client", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "create",
            update: "update",
        });

        this.client_id = client.client_id || null;
        this.phone = client.phone || null;
        this.name = client.name || null;
        this.surname = client.surname || null;
        this.patronymic = client.patronymic || null;
        this.email = client.email || null;
    }
}
