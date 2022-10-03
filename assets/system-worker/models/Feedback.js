/** @format */

import Model from "../base/Model";

export default class Feedback extends Model {
    /**
     * @type {string}
     */
    primaryKey = "feedback_id";

    rules = {};

    constructor(feedback = {}) {
        super("feedback", process.env.MIX_APP_WORKER_URL, "", {
            update: "edit",
            delete: "../feedback",
            deletes: "deletes",
        });

        this.id = feedback.id || null;
        this.name = feedback.name || "";
        this.status = feedback.status || null;
    }
}
