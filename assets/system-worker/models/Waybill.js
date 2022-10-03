/** @format */

import Model from "../base/Model";
import axios from "axios";

export default class Waybill extends Model {
    formData = true;

    /**
     * @type {string}
     */
    scope = "waybills";

    /**
     * @type {string}
     */
    primaryKey = "waybill_id";

    /**
     * @type {string[]}
     */
    hidden = [];

    /**
     * @type {object}
     */
    rules = {};

    constructor(waybill = {}) {
        super("waybills", process.env.MIX_APP_WORKER_URL, "", {
            create: "store",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });
    }

    waybillInfo(waybillId) {
        return axios.get(`waybills/info/${waybillId}`);
    }

    waybillImages(waybillId) {
        return axios.get(`waybills/images/${waybillId}`);
    }
}
