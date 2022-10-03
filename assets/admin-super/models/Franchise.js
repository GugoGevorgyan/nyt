/** @format */

import Model from "../base/Model";
import axios from "axios";

export default class Franchise extends Model {
    scope = "franchise";

    FormData = true;

    nameLoading = false;

    newLogo = true;

    rules = {
        name: "required|string|min:3|max:36",
        file: this.file ? "image" : "",
        phone: "required|string|min:3|max:36",
        email: "email|string|min:3|max:36",
        text: "max:250",
        address: "",
        zip_code: "",
        country_id: "required",
        entity_id: "required",
        region_ids: "required|array",
        module_ids: "required|array",

        default_rate_assessment: 'required|decimal|min_value:1|max_value:5',
        default_rate_rating: 'required|numeric|min_value:100|max_value:999',
        waybill_max_days: 'required|numeric|min_value:1|max_value:20',
        dispatching_minute: 'numeric|max_value:240'
    };

    hidden = [
        "franchise_id",
        "newLogo",
        "logo",
        "module_ids",
        "regionsSelectable",
        "regionsLoading",
        "region_ids",
        "all_admins",
        "nameLoading:",
    ];

    constructor(franchise = {}) {
        super("franchises", "admin/super");

        this.franchise_id = franchise.franchise_id || null;
        this.name = franchise.name || null;
        this.logo = franchise.logo || null;
        this.file = franchise.file || null;
        this.phone = franchise.phone || null;
        this.email = franchise.email || null;
        this.text = franchise.text || null;
        this.entity_id = franchise.entity_id || null;
        this.address = franchise.address || null;
        this.zip_code = franchise.zip_code || null;
        this.dispatching_minute = franchise.dispatching_minute || null;

        this.module_ids = franchise.module_ids || [];

        this.country_id = franchise.country_id || null;
        this.region_ids = franchise.region_ids || [];

        this.option = franchise.option || {};

        this.call_center_phones = franchise.call_center_phones || [];

        this.regions_cities = {};
        this.module_roles = {};

        this.all_admins = franchise.all_admins || [];
        this.new_admins = franchise.new_admins || [];
    }

    checkUniqueName() {
        let data = {
            table: "franchisee",
            col: "name",
            id: this.franchise_id,
            idCol: "franchise_id",
            name: this.name,
        };

        return axios.post("/validate/custom-unique", data);
    }


}
