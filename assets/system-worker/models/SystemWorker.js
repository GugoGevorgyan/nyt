/** @format */

import Model from "../base/Model";
import store from "../store";

export default class SystemWorker extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "worker";

    /**
     * @type {string}
     */
    primaryKey = "system_worker_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "photo", "role_ids"];

    /**
     * @type {{password_confirmation: string, description: string, role_ids: string, photo_file: string, patronymic: string, password: string, old_password: string, phone: string, operator_sub_phone_id: string, dispatcher_sub_phone_id: string, surname: string, name: string, nickname: string, email: string}}
     */
    rules = {
        name: "required|min:3|max:99",
        surname: "required|min:3|max:99",
        patronymic: "required|min:3|max:99",
        nickname: "required|min:3|max:16",
        email: "email",
        phone: "length:" + store.state.phoneMask.length,
        photo_file: "ext:jpeg,jpg,png|size:512",
        description: "max:999",
        password: "required|min:6|max:100",
        role_ids: "required",
        role_permissions: "required",
        operator_sub_phone_id: "required",
        dispatcher_sub_phone_id: "required",
    };

    /**
     * @param worker
     */
    constructor(worker = {}) {
        super("workers", process.env.MIX_APP_WORKER_URL, "", {
            create: "store",
            update: "update",
        });

        this.system_worker_id = worker.system_worker_id || null;
        this.name = worker.name || null;
        this.surname = worker.surname || null;
        this.patronymic = worker.patronymic || null;
        this.nickname = worker.nickname || null;
        this.email = worker.email || null;
        this.phone = worker.phone || null;
        this.photo = worker.photo || null;
        this.photo_file = worker.photo_file || null;
        this.description = worker.description || null;

        this.change_password = worker.change_password || false;
        this.password = worker.password || null;

        this.role_ids = worker.role_ids || [];
        this.role_permissions = worker.role_permissions || {};

        this.operator_sub_phone_id = worker.worker_operator ? worker.worker_operator.franchise_sub_phone_id : null;
        this.dispatcher_sub_phone_id = worker.worker_dispatcher
            ? worker.worker_dispatcher.franchise_sub_phone_id
            : null;
    }
}
