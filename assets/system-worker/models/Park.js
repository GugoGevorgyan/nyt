/** @format */

import Model from "../base/Model";

export default class Park extends Model {
    scope = "park";

    formData = true;

    rules = {
        name: "required|max:100|min:3",
        city_id: "required|integer",
        address: "required|max:100|min:3",
        region: "required|integer",
        entity_id: "required",
        manager_id: "required",
    };

    constructor(park = {}) {
        super("parks", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "save-create",
            update: "edit-update",
        });

        this.park_id = park.park_id;
        this.name = park.name;
        this.city_id = park.city_id;
        this.city = park.city;
        this.address = park.address;
        this.entity_id = park.entity_id || null;
        this.manager_id = park.manager_id || null;
    }
}
