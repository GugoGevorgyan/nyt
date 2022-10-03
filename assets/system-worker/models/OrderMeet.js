/** @format */

import Model from "../base/Model";

export default class OrderMeet extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "orderMeet";

    /**
     * @type {string}
     */
    primaryKey = "order_meet_id";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "pickerOptions"];

    /**
     * @type {object}
     */
    rules = {
        info: "",
        text: "",
        airport_id: "required",
        railway_station_id: "required",
        metro_id: "required",
    };

    /**
     * @param meet
     */
    constructor(meet = {}) {
        super("", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "create",
            update: "update",
        });
        this.airport_id = meet.place && meet.place.airport_id ? meet.place.airport_id : null;
        this.railway_station_id = meet.place && meet.place.railway_station_id ? meet.place.railway_station_id : null;
        this.metro_id = meet.place && meet.place.metro_id ? meet.place.metro_id : null;
        this.info = meet.info || null;
        this.text = meet.text || null;
    }
}
