/** @format */

import Model from "../base/Model";
import store from "../store";

export default class ProfileWorker extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "profileWorker";

    /**
     * @type {string}
     */
    primaryKey = "system_worker_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "photo", "system_worker_id"];

    /**
     * @type {{photo_file: string, patronymic: string, password: string, phone: string, surname: string, name: string, nickname: string, email: string}}
     */
    rules = {
        name: "required|min:3|max:99",
        surname: "required|min:3|max:99",
        patronymic: "required|min:3|max:99",
        nickname: "required|min:3|max:16",
        email: "email|min:3|max:64",
        phone: "length:" + store.state.phoneMask.length - 1,
        photo_file: "ext:jpeg,jpg,png|size:512",
        password: "required|min:6|max:100"
    };

    /**
     * @param worker
     */
    constructor(worker = {}) {
        super("profile", process.env.MIX_APP_WORKER_URL, "", {
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

        this.change_password = worker.change_password || false;
        this.password = worker.password || null;
    }
}
