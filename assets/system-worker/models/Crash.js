/** @format */

import Model from "../base/Model";

export default class Crash extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "traffic-safety-crash";

    /**
     * @type {string}
     */
    primaryKey = "car_crash_id";

    /**
     * @type {string[]}
     */
    hidden = ["learnStatus", "baseUrl"];

    /**
     * @type {object}
     */
    rules = {
        car_id: "required",
        driver_id: "required",
        date: "required",
        time: "",
        address: "required",
        description: "",
        our_fault: "",
        images: "ext:jpeg,jpg,png|size:4048",
        inspector_info: "",
        participant_info: "",
        act: "",
        act_sum: "",
    };

    /**
     * @param crash
     */
    constructor(crash = {}) {
        super("crash", "traffic-safety", process.env.MIX_APP_WORKER_URL, {
            create: "create",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });

        (this.car_crash_id = crash.car_crash_id || 0), (this.car_id = crash.car_id);
        this.driver_id = crash.driver_id;
        this.date = crash.date;
        this.time = crash.time;
        this.address = crash.address;
        this.description = crash.description;
        this.our_fault = crash.our_fault || false;
        this.images = crash.images;
        this.inspector_info = crash.inspector_info;
        this.participant_info = crash.participant_info;
        this.act = crash.act;
        this.act_sum = crash.act_sum;
        this.dateTime = crash.dateTime;
    }
}
