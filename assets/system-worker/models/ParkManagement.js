/** @format */

import Model from "../base/Model";

export default class ParkManagement extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "park-management";

    /**
     * @type {string}
     */
    primaryKey = "car_id";

    /**
     * @type {string[]}
     */
    hidden = ["learnStatus", "baseUrl"];

    /**
     * @type {object}
     */
    rules = {
        status: "required",
        park_id: "required",
    };

    /**
     * @param car
     * @param onlyIds
     */
    constructor(car = {}) {
        super("park-management", process.env.MIX_APP_WORKER_URL, "", {
            create: "create",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });
        this.car_id = car.car_id || 0;
        this.park_id = car.park_id;
        this.status = car.status;
    }
}
