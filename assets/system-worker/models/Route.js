/** @format */

import Model from "../base/Model";

export default class Route extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "route";

    /**
     * @type {string[]}
     */
    hidden = ["baseUrl", "pickerOptions"];

    /**
     * @type {{}}
     */
    rules = {};

    /**
     * @param route
     */
    constructor(route = {}) {
        super("", process.env.MIX_APP_WORKER_URL, "", {});

        this.from = route.from || null;
        this.to = route.to || null;
    }
}
