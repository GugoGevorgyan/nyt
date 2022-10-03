/** @format */

import Model from "../base/Model";
import store from "../store";

export default class Candidate extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "candidate";

    /**
     * @type {string}
     */
    primaryKey = "driver_candidate_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "adminUrl"];

    /**
     * @type {{tutor_id: {exists: {col: string, table: string}, required: boolean}, phone: string, learn_status: string, learn_end: string, learn_start: string}}
     */
    rules = {
        tutor_id: "required",
        phone: "required|length:" + store.state.phoneMask.length,
        learn_status: "required",
        learn_start: "",
        learn_end: "",
        password_confirm: "min:3|max:16",
    };

    /**
     * @param candidates
     * @param onlyIds
     */
    constructor(candidates = {}, onlyIds = false) {
        super("driver-candidates", process.env.MIX_APP_WORKER_URL, "", {
            create: "save-create",
            delete: "delete",
            deletes: "deletes",
            update: "update",
        });

        this.driver_candidate_id = candidates.driver_candidate_id || null;
        this.tutor_id = candidates.tutor_id || null;
        this.phone = candidates.phone || null;
        this.learn_status_id = candidates.learn_status_id || null;
        this.learn_start = candidates.learn_start || null;
        this.learn_end = candidates.learn_end || null;
        this.deleted_at = candidates.deleted_at || null;
        this.ids = candidates.ids || [];
    }
}
