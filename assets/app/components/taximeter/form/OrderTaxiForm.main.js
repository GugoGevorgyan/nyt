/** @format */

import { mapMutations, mapState } from "vuex";
import PreOrder from "../PreOrder/PreOrder.component";
import { Map, Order } from "../../../services";
import { PAYMENT_TYPE } from "../../../plugins/config";
import Snackbar from "../../../facades/Snackbar";
import moment from "moment";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "OrderTaxiForm",

    /**
     * @Components ☺☺☺
     */
    components: { PreOrder },

    /**
     * @Data ☺☺☺
     */
    data() {
        return {
            PAYMENT_TYPE: PAYMENT_TYPE,
            imPassenger: true,
            comment: false,
            disabledPhone: true,
            calcCoin: false,

            repeatOrderMenu: false,
            repeatOrder: [new Date().toISOString().slice(0, 10)],
        };
    },

    mixins: [Map, Order],

    /**
     * @Computed ☺☺☺
     */
    computed: {
        ...mapState(["maps", "orderForm", "order", "client", "transports"]),

        preorder() {
            return this.$store.state.orderForm.disablePreorder;
        },

        validateDisabledButton() {
            return this.order.address_from && this.client.phone && this.order.payment_type && this.order.car_class_id;
        },

        rentTimes() {
            return this.$store.state.orderForm.rent_times;
        },

        carOptionPrice: {
            get() {
                return this.$store.state.order.car_option;
            },
            set(value) {
                this.$store.state.order.car_option = value;
            },
        },

        carClassPrice: {
            get() {
                return this.$store.state.order.car_class_id;
            },
            set(value) {
                this.$store.state.order.car_class_id = value;
            },
        },

        paymentTypePrice: {
            get() {
                return this.$store.state.order.payment_type;
            },
        },

        paymentTypeCompany: {
            get() {
                return this.$store.state.order.payment_type_company;
            },
        },

        priceCoin: {
            get() {
                return this.$store.state.orderForm.coin;
            },
            set(val) {
                this.$store.state.orderForm.coin = val;
            },
        },

        priceText: {
            get() {
                return this.$store.state.orderForm.priceText;
            },
            set(val) {
                this.$store.state.orderForm.priceText = val;
            },
        },

        paymentCards: {
            get() {
                return this.$store.state.client.pay_cards;
            },
        },

        paymentDialog: {
            get() {
                return this.$store.state.app.paymentDialog;
            },
            set(val) {
                this.$store.state.app.paymentDialog = val;
            },
        },

        optionsPrice: {
            get() {
                return this.$store.state.orderForm.optionsPrice;
            },
            set(val) {
                this.$store.state.orderForm.optionsPrice = val;
            },
        },

        isRent: {
            get() {
                return this.$store.state.order.is_rent;
            },
            set(value) {
                this.$store.state.order.is_rent = value;
            },
        },

        rentTime: {
            get() {
                return this.$store.state.order.rent_time;
            },
            set(value) {
                this.$store.state.order.rent_time = value;
            },
        },
    },

    /**
     * @Watchers ☺☺☺
     */
    watch: {
        "orderForm.displayFrom": function () {
            if (this.orderForm.displayFrom === "" && this.orderForm.displayTo === "") {
                this.$validator.reset();
                this.clearFromField();
                this.clearToField();
            }
        },

        priceCoin(val) {
            this.orderForm.priceText = this.orderForm.priceText.replace(/(\d+)/, val);
        },

        carClassPrice() {
            if (this.order.address_from) {
                this.getOrderPrice();
            }
        },

        carOptionPrice(newVal, oldVal) {
            if (JSON.stringify(newVal) === JSON.stringify(oldVal)) {
                return;
            }

            if (this.isRent) {
                if (this.rentTime && this.order.address_from) {
                    this.getOrderPrice();
                }
            } else {
                if (this.order.address_from) {
                    this.getOrderPrice();
                }
            }
        },

        paymentTypePrice(val) {
            if (this.order.address_from) {
                this.getOrderPrice();
            }

            if (val !== PAYMENT_TYPE.COMPANY) {
                this.orderInit({ payment_type_company: null });
            }
        },

        paymentTypeCompany(val) {
            if (this.order.address_from) {
                this.getOrderPrice();
            }
        },

        isRent(val) {
            if (val) {
                if (this.order.address_from && this.rentTime) {
                    this.getOrderPrice();
                }
            } else {
                if (this.order.address_from) {
                    this.getOrderPrice();
                }
            }
        },

        rentTime(val) {
            if (val && this.order.address_from) {
                this.getOrderPrice();
            }
        },
    },

    /**
     * @Methods ☻☻☻
     */
    methods: {
        ...mapMutations({
            initOrderProgress: "initOrderProgress",
            orderInit: "orderInit",
            initClient: "initClient",
            initMap: "initMap",
            initOrderForm: "initOrderForm",
            INIT_IN_ORDER_ACTION: "INIT_IN_ORDER_ACTION",
            initTransports: "initTransports",
        }),

        getUsingPhoneAccordinglyPhoneMask(phone) {
            // One element is '+' in code; Example '+374';
            if (this.$store.state.orderForm.phoneMask) {
                return phone.substr(this.$store.state.orderForm.phoneMask.indexOf("(") - 1);
            }
        },

        openTransportMenu(target) {
            if ("from" === target) {
                this.initOrderForm({ fromClick: true, toClick: false });

                if (
                    !this.transports.airports.airport_id ||
                    !this.transports.metros.metro_id ||
                    !this.transports.stations.railway_station_id
                ) {
                    this.getTransports();
                }

                return;
            }

            if ("to" === target) {
                this.initOrderForm({ fromClick: false, toClick: true });

                if (
                    !this.transports.airports.airport_id ||
                    !this.transports.metros.metro_id ||
                    !this.transports.stations.railway_station_id
                ) {
                    this.getTransports();
                }
            }
        },

        detectFromLocation() {
            let location = ymaps.geolocation.get();

            location.then(result => {
                let userAddress = result.geoObjects.get(0).properties.get("text");
                let userCoordinates = result.geoObjects.get(0).geometry.getCoordinates();
                this.orderInit({ address_from: userAddress, address_from_coordinates: userCoordinates });
            });
        },

        clearFromField() {
            this.orderInit({ address_from: "", address_from_coordinates: [] });
            this.initMap({ from: {}, from_name: "" });
            this.initOrderForm({ priceText: this.orderForm.priceTextDefault });
            this.maps.map.geoObjects.remove(this.maps.from);
            localStorage.removeItem("from_c");
            localStorage.removeItem("from");

            ymaps.ready(this.initSuggest(true, false));
        },

        clearToField() {
            this.orderInit({ address_to: "", address_to_coordinates: [] });
            this.initMap({ to: {}, to_name: "" });
            this.maps.map.geoObjects.remove(this.maps.to);
            localStorage.removeItem("to_c");
            localStorage.removeItem("to");

            ymaps.ready(this.initSuggest(false, true));
        },

        closeRepeatOrder() {
            this.repeatOrderMenu = false;
            this.repeatOrder = [new Date().toISOString().slice(0, 10)];
        },

        initSuggest(from = true, to = true) {
            if (from) {
                if (localStorage.getItem("from") && localStorage.getItem("from").length) {
                    this.orderInit({ address_from: localStorage.getItem("from") });
                    this.initOrderForm({ displayFrom: localStorage.getItem("from").replace(/,[^,]+$/, "") });

                    ymaps.geocode(localStorage.getItem("from")).then(res => {
                        this.orderInit({ address_from_coordinates: res.geoObjects.get(0).geometry._coordinates });
                    });
                } else {
                    let suggestViewFrom = new ymaps.SuggestView("from", {
                        width: "386",
                        offset: [-45, 6],
                        layout: "islands#suggestView",
                    });

                    suggestViewFrom.events.add("select", e => {
                        this.orderInit({ address_from: e.get("item").value });
                        this.initOrderForm({ displayFrom: e.get("item").value });

                        ymaps.geocode(e.get("item").value).then(res => {
                            this.orderInit({ address_from_coordinates: res.geoObjects.get(0).geometry._coordinates });
                            suggestViewFrom.destroy();
                        });
                    });
                }
            }

            if (to) {
                if (localStorage.getItem("to") && localStorage.getItem("to").length) {
                    this.orderInit({ address_to: localStorage.getItem("to") });
                    this.initOrderForm({ displayTo: localStorage.getItem("to").replace(/,[^,]+$/, "") });

                    ymaps.geocode(localStorage.getItem("to")).then(res => {
                        this.orderInit({ address_to_coordinates: res.geoObjects.get(0).geometry._coordinates });
                    });
                } else {
                    let suggestViewTo = new ymaps.SuggestView("to", {
                        width: "391",
                        offset: [-45, 6],
                        layout: "islands#suggestView",
                    });

                    suggestViewTo.events.add("select", e => {
                        this.orderInit({ address_to: e.get("item").value });
                        this.initOrderForm({ displayTo: e.get("item").value });

                        ymaps.geocode(e.get("item").value).then(res => {
                            this.orderInit({ address_to_coordinates: res.geoObjects.get(0).geometry._coordinates });
                            suggestViewTo.destroy();
                        });
                    });
                }
            }
        },

        switchFromTo() {
            this.orderInit({
                address_from: this.order.address_to,
                address_to: this.order.address_from,
                address_from_coordinates: this.order.address_to_coordinates,
                address_to_coordinates: this.order.address_from_coordinates,
            });
        },

        toggleOrderForm() {
            this.initOrderForm({ open: false });
        },

        makeOrderAccept(response) {
            let createDate = moment(this.order.order_time.create_time.slice(0, 16));
            let orderDate = moment(this.order.order_time.time);

            if (0 === createDate.diff(orderDate)) {
                this.INIT_IN_ORDER_ACTION({ status: true, data: this.order, responseData: response });
                this.initOrderProgress({
                    status: true,
                    cancel: true,
                    pending: true,
                    text: "Поиск машины",
                });
                this.initOrderForm({ open: false, fromClick: false, toClick: false });
            } else {
                this.initOrderForm({ displayFrom: "", displayTo: "" });
                Snackbar.info(response.data.message);
            }
        },
    },

    /**
     * @Hook Created ☺☺☺
     */
    created() {
        ymaps.ready(this.initSuggest);
        !this.client.phone ? (this.disabledPhone = false) : (this.disabledPhone = true);
    },
};
