/** @format */

import Model from "../base/Model";
import axios from "axios";
import { PAYMENT_TYPE } from "../plugins/config";
import moment from "moment";
import { integer } from "vee-validate/dist/rules.esm";

export default class Order extends Model {
    /**
     * @type {boolean}
     */
    formData = false;

    /**
     * @type {boolean}
     */
    loading = false;

    /**
     * @type {boolean}
     */
    loadingFromCoordinates = false;

    /**
     * @type {boolean}
     */
    loadingToCoordinates = false;

    /**
     * @type {boolean}
     */
    loadingCarOptions = false;

    /**
     * @type {boolean}
     */
    loadingCarClasses = false;

    /**
     * @type {boolean}
     */
    loadingPaymentTypes = false;

    loadDialogData = false;
    /**
     * @type {number}
     */
    priceText = "Для расчёта заполните поля «Откуда» и «Куда».";
    /**
     * @type {[]}
     */
    rentTimes = [];
    /**
     * @type {number}
     */
    optionCoin = 0;

    /**
     * @type {number}
     */
    coin = 0;

    /**
     * @type {*[]}
     */
    carClassValues = [];

    /**
     * @type {*[]}
     */
    carOptionValues = [];

    /**
     * @type {*[]}
     */
    paymentTypeValues = [];

    /**
     * @type {string}
     */
    pricePending = false;

    /**
     * @type {string}
     */
    scope = "order";

    /**
     * @type {number}
     */
    status = integer;

    /**
     * @type {string}
     */
    message = "";

    /**
     * @type {string}
     */
    primaryKey = "order_id";

    /**
     * @type {string[]}
     */
    hidden = [
        "pricePending",
        "priceText",
        "paymentTypeValues",
        "loadingCarClasses",
        "loadingCarOptions",
        "loadingFromCoordinates",
        "loadingPaymentTypes",
        "loadingToCoordinates",
        "carClassValues",
        "carOptionValues",
        "loading",
        "baseUrl",
        "coin",
        "rentTimes",
        "loadDialogData",
        "optionCoin",
        "adminUrl",
        "_method",
    ];

    /**
     * @type {object}
     */
    rules = {
        client_id: "required",
        car_class_id: "required",
        payment: "required",
        orderable_phone: "required",
        address_from: "required",
        address_to: "",
        comment: "",
        car_option: "array",
        meet_vagon_number: "",
        meet_flight_number: "",
        meet_from: "",
        meet_text: "",
    };

    /**
     * @param order
     */

    constructor(order = {}) {
        super("order", "admin/corporate/", "", {
            create: "create",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });

        order.order_id ? (this.order_id = order.order_id || null) : null;
        this.client_id = order.client_id || null;
        this.company_id = order.company_id || null;
        this.phone = { client: order.phone, passenger: false } || null;
        this.payment = { type: PAYMENT_TYPE.COMPANY || "1", company: order.payment_company_id || null };
        this.is_rent = false;
        this.rent_time = null;

        this.route = {
            from_address: order.address_from,
            from: order.from,
            to_address: order.address_to || "",
            to: order.to || [],
        };
        this.car = {
            comments: order.comment || "",
            options: order.options || "",
            class: order.car_class_id,
        };
        this.time = {
            create_time: moment().format("YYYY-MM-DD HH:mm"),
            time: order.time || moment().format("YYYY-MM-DD HH:mm"),
            zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        };
    }

    getOrderPrice() {
        this.loading = true;
        axios
            .post("init_coin", this.buildForm())
            .then(response => {
                if (response.data) {
                    this.#parseCoinResult(response.data);
                }

                this.loadingPaymentTypes = false;
                this.loading = false;
            })
            .catch(error => {});
    }

    #parseCoinResult(data) {
        console.log(this.car.class);
        let index = data.findIndex((item, index) => {
            if (item.car_class_id === this.car.class || item.class_id === this.car.class) {
                return index;
            }

            if (this.car.class === item.selected) {
                return index;
            }
        });

        let newData = data[-1 === index ? 0 : index];
        let newCoin = newData.coin + this.optionCoin;

        if (newData.initial && newData.sitting_fee) {
            this.priceText = `Цена за поездку от ${newCoin} ${newData.currency}`;
        }
        if (!newData.initial && newData.sitting_fee) {
            this.priceText = `Цена за поездку ${newCoin} ${newData.currency}`;
        }
        if (!newData.initial && !newData.sitting_fee) {
            this.priceText = `Цена за поездку ${newCoin} ${newData.currency}`;
        }
        if (newData.initial && !newData.sitting_fee) {
            this.priceText = `Цена за поездку от ${newCoin} ${newData.currency}`;
        }

        data.forEach(item => {
            item["class_id"] = item["car_class_id"];
            delete item["car_class_id"];
            item.coin += this.optionCoin;
        });

        this.coin = newCoin;
        this.carClassValues = data;
        this.rentTimes = newData.rent_times;
    }

    openOrderModal() {
        this.loadingPaymentTypes = true;
        this.loadingCarClasses = true;
        this.loadingCarOptions = true;

        axios.post("open_order_card", { client_phone: this.phone, company_id: this.company_id }).then(response => {
            this.status = response.data.limit_message.status;
            this.message = response.data.limit_message.message;
            this.carOptionValues = response.data.car_options;
            this.carClassValues = response.data.car_class;
            this.paymentTypeValues = response.data.payment_types;
            this.rentTimes = response.data.rent_times;
            this.rent_time = response.data.rent_times[0];

            this.loadingPaymentTypes = false;
            this.loadingCarClasses = false;
            this.loadingCarOptions = false;
            this.loadDialogData = false;
        });
    }

    closeDialog(phone, companyId) {
        axios.post("close_order_card", { client_phone: phone, company_id: companyId }).then(response => {});
    }
}
