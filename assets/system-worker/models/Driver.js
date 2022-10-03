/** @format */
import axios from "axios";
import Model from "../base/Model";

export default class Driver extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "driver";

    /**
     * @type {string}
     */
    primaryKey = "driver_id";

    /**
     * @type {string[]}
     */
    hidden = ["learnStatus", "baseUrl"];

    /**
     * @type {object}
     */
    rules = {
        candidate_id: "required",
        nickname: {
            required: true,
            unique: {
                table: "drivers",
                col: "nickname",
            },
        },
        phone: {
            required: true,
            min: 6,
            max: 32,
            unique: {
                table: "drivers",
                col: "phone",
            },
        },
        password: "required|min:3|max:16",
        graphic_id: "required",
        type_id: "required",
        subtype_id: "required",
        free_days_price: "required",
        busy_days_price: "required",
        entity_id: "required",

        driver_id: "required",
        expiration_day: "required",
        work_start_day: "required",
    };

    /**
     * @param driver
     */
    constructor(driver = {}) {
        super("driver", process.env.MIX_APP_WORKER_URL, "", {
            create: "create",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });
        this.driver_id = driver.driver_id || 0;
        this.candidate_id = driver.candidate_id || "";

        this.phone = driver.phone || "";
        this.nickname = driver.nickname || "";
        this.password = driver.password || "";

        this.graphic_id = driver.graphic_id || "";
        this.type_id = driver.type_id || "";
        this.subtype_id = driver.subtype_id || "";
        this.point = driver.point || "";
        this.free_days_price = driver.free_days_price || "";
        this.busy_days_price = driver.busy_days_price || "";
        this.entity_id = driver.entity_id || "";

        this.expiration_day = driver.expiration_day || "";
        this.work_start_day = driver.work_start_day || "";
        this.days_count = driver.days_count || "";
    }

    candidateDriverCreate() {
        let fD = new FormData();

        this.driver_id ? fD.append("driver_id", this.driver_id) : null;
        !this.driver_id ? fD.append("nickname", this.nickname) : null;
        !this.driver_id ? fD.append("password", this.password) : null;
        !this.driver_id ? fD.append("phone", this.phone) : null;

        fD.append("candidate_id", this.candidate_id);
        fD.append("graphic_id", this.graphic_id);
        fD.append("type_id", this.type_id);
        fD.append("subtype_id", this.subtype_id);
        fD.append("free_days_price", this.free_days_price);
        fD.append("busy_days_price", this.busy_days_price);

        fD.append("expiration_day", this.expiration_day);
        fD.append("work_start_day", this.work_start_day);
        fD.append("days_count", this.days_count);

        this.entity_id ? fD.append("entity_id", this.entity_id) : null;

        return axios.post("driver-candidates/driver-create", fD);
    }
}
