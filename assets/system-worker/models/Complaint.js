/** @format */

import Model from "../base/Model";
import axios from "axios";

export default class Complaint extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "complaint";

    /**
     * @type {string}
     */
    primaryKey = "complaint_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "previews", "complaint_id"];

    /**
     * @type {object}
     */
    rules = {
        subject: "required|min:3|max:100",
        complaint: "required|min:3|max:2500",
        recipient_id: "required",
        order_id: "",
        files: "",
    };

    /**
     * @param complaint
     */
    constructor(complaint = {}) {
        super("complaint", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "create",
            update: "update",
        });

        this.complaint_id = complaint.company_id || null;
        this.subject = complaint.subject || null;
        this.complaint = complaint.complaint || null;
        this.recipient_id = complaint.recipient_id || null;
        this.order_id = complaint.order_id || null;
        this.files = complaint.files || [];
        this.previews = complaint.previews || [];
    }
}
