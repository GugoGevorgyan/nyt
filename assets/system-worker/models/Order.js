/** @format */

import Model from "../base/Model";
import moment from "moment";

export default class Order extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "order";

    /**
     * @type {string}
     */
    primaryKey = "order_id";

    /**
     * @type {string[]}
     */
    hidden = [
        "loadingFromCoordinates",
        "loadingToCoordinates",
        "baseUrl",
        "price",
        "errFrom",
        "errTo",
        "displayFrom",
        "displayTo",
        "pickerOptions",
        "preOrderDateTime",
    ];

    /**
     * @type {object}
     */
    rules = {
        client_id: "required",
        operator_id: "",
        company_id: "required",
        car_class_id: "required",
        car_option: "array",
        payment_type_id: "required",
        address_from: "required",
        address_to: "",
        comment: "",
        is_passenger: "required|number",
        is_meet: "required|number",
    };

    loadingFromCoordinates = false;
    loadingToCoordinates = false;

    /**
     * @param order
     */
    constructor(order = {}) {
        super("", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "create",
            update: "update",
        });

        this.order_id = order.order_id || null;
        this.client_id = order.client_id || null;
        this.operator_id = order.operator_id || null;
        this.company_id = order.company_id || null;
        this.car_class_id = order.car_class_id || 1;
        this.is_rent = order.is_rent || false;
        this.car_option = order.car_option ? order.car_option.ids : [];
        this.payment_type_id = order.payment_type_id || 1;
        this.address_from = order.address_from || null;
        this.address_to = order.address_to || null;
        this.from_coordinates = order.from_coordinates || null;
        this.to_coordinates = order.to_coordinates || null;
        this.comments = order.comments || null;
        this.is_passenger = order.is_passenger || null;
        this.is_meet = order.is_meet || null;

        this.rent_time = order.rent_time || null;
        this.create_time = moment().format("YYYY-MM-DD HH:mm");
        this.time_zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        this.start_time =  order.start_time || moment().format("YYYY-MM-DD HH:mm:ss")

        this.is_preorder = order.is_preorder || null;
        this.creating_type = order.creating_type || null;

        this.price = order.price || null;

        this.displayFrom = order.displayFrom || "";
        this.displayTo = order.displayTo || "";

        this.errFrom = order.errFrom || { value: false, msg: null };
        this.errTo = order.errFrom || { value: false, msg: null };
    }
}
