/** @format */

import Order from "../../../models/Order";
import OrderMeet from "../../../models/OrderMeet";
import MultiModel from "../../../base/MultiModel";
import Snackbar from "../../../facades/Snackbar";
import moment from "moment";

export default {
    name: "OrderDialog",

    props: {
        employee: {
            required: true,
        },
    },

    data() {
        return {
            order: new Order(),
            meet: new OrderMeet(),

            preOrderMinutes: null,
            preOrderMinutesDisabled: false,
            preOrderMenu: false,
            preOrderTime: null,
            preOrderDate: null,
            oldToggle: "",

            stepWindow: 1,
            toggleActiveTrain: false,
            toggleActiveAirport: false,
            toggleActiveMetro: false,
            datePickerOptions: {
                disabledDate(date) {
                    return date.getTime() < Date.now() - 8.64e7;
                },
            },
        };
    },

    watch: {
        employee: function () {
            this.setOrderValues();
        },

        "order.car.class": function (val) {
            if (this.order.route.from_address) {
                this.order.getOrderPrice();
            }
            this.order.loadDialogData = false;
        },
        "order.limit_message.status": function (val) {
            if (this.order.status) {
                this.order.getOrderPrice();
            }
            this.order.loadDialogData = false;
        },

        "order.route.from": function (val) {
            if (this.order.route.from_address) {
                this.order.getOrderPrice();
            }
        },

        "order.route.to": function (val) {
            if (this.order.route.from_address) {
                this.order.getOrderPrice();
            }
        },

        "order.rent_time": function (val) {
            if (this.order.route.from_address) {
                this.order.getOrderPrice();
            }
        },

        "order.time.time": function (val) {
            this.order.time.time = moment(val).format("YYYY-MM-DD HH:mm");
            if (this.order.route.from_address) {
                this.order.getOrderPrice();
            }
        },

        "order.is_rent": function (val) {
            if (val) {
                if (this.order.route.from_address && this.order.rent_time) {
                    this.order.getOrderPrice();
                }
            } else {
                if (this.order.route.from_address) {
                    this.order.getOrderPrice();
                }
            }
        },

        "order.car.options": function (val, oldVal) {
            if (this.order.route.from_address) {
                if (val.length >= oldVal.length) {
                    let difference = val.filter(x => !oldVal.includes(x));
                    let values = difference.length ? difference : val;
                    let addCoin = 0;

                    for (let id of values) {
                        let index = this.order.carOptionValues.findIndex(item => item.id === id);
                        addCoin += parseFloat(this.order.carOptionValues[index].price);

                        this.order.optionCoin += addCoin;
                        this.order.coin += addCoin;

                        let text = this.order.priceText;
                        this.order.priceText = text.replace(/(\d+)/, this.order.coin);
                    }

                    this.order.carClassValues.forEach(item => {
                        item.coin += addCoin;
                    });
                } else {
                    let difference = val.filter(x => !oldVal.includes(x)).concat(oldVal.filter(x => !val.includes(x)));
                    let values = difference.length ? difference : val;
                    let takeCoin = 0;

                    for (let id of values) {
                        let index = this.order.carOptionValues.findIndex(item => item.id === id);
                        takeCoin += parseFloat(this.order.carOptionValues[index].price);

                        this.order.optionCoin -= takeCoin;
                        this.order.coin -= takeCoin;

                        let text = this.order.priceText;
                        this.order.priceText = text.replace(/(\d+)/, this.order.coin);
                    }

                    this.order.carClassValues.forEach(item => {
                        item.coin -= takeCoin;
                    });
                }
            }
        },
    },

    methods: {
        toggleWindow(transport) {
            if (transport === this.oldToggle) {
                this.stepWindow = 1;
                this.oldToggle = "";
                this.toggleActiveAirport = false;
                this.toggleActiveMetro = false;
                this.toggleActiveTrain = false;
                return;
            }

            if (transport === "train") {
                this.meet.getStations;
                this.stepWindow = 2;
                this.oldToggle = transport;
                this.toggleActiveTrain = true;
                this.toggleActiveAirport = false;
                this.toggleActiveMetro = false;
                return;
            }

            if (transport === "airport") {
                this.meet.getAirports;
                this.stepWindow = 3;
                this.oldToggle = transport;
                this.toggleActiveAirport = true;
                this.toggleActiveMetro = false;
                this.toggleActiveTrain = false;
                return;
            }

            if (transport === "metro") {
                this.meet.getMetros;
                this.stepWindow = 4;
                this.oldToggle = transport;
                this.toggleActiveMetro = true;
                this.toggleActiveAirport = false;
                this.toggleActiveTrain = false;
            }
        },

        selectCity() {
            if (this.stepWindow === 2) {
                this.meet.getStations;
            }

            if (this.stepWindow === 3) {
                this.meet.getAirports;
            }

            if (this.stepWindow === 4) {
                this.meet.getMetros;
            }
        },

        initSuggest() {
            let fromView = new ymaps.SuggestView("from");
            fromView.events.add("select", e => {
                this.order.route.from_address = e.get("item").value;

                ymaps.geocode(e.get("item").value).then(res => {
                    this.order.route.from = res.geoObjects.get(0).geometry.getCoordinates();
                });
            });

            let toView = new ymaps.SuggestView("to");
            toView.events.add("select", e => {
                this.order.route.to_address = e.get("item").value;

                ymaps.geocode(e.get("item").value).then(res => {
                    this.order.route.to = res.geoObjects.get(0).geometry.getCoordinates();
                });
            });
        },

        setOrderValues() {
            this.order.client_id = this.employee ? this.employee.client_id : null;
            this.order.company_id = this.employee ? this.employee.company_id : null;
            this.order.phone.client = this.employee && this.employee.client ? this.employee.client.phone : null;
            this.order.payment.company = this.employee ? this.employee.company_id : null;
        },

        createOrder() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.order.loading = true;
                    this.form = new MultiModel([this.order, this.meet]);
                    this.order.loading = true;
                    this.form
                        .send(`company/order/create`)
                        .then(response => {
                            this.$emit("cancel");
                            this.order.loading = false;
                            Snackbar.info(response.data.message);
                            this.employee.client.in_order = true;
                        })
                        .catch(error => {
                            this.order.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        cancel() {
            this.$emit("cancel");
            this.order = new Order();
        },

        clearFromField() {
            this.order.route.from = [];
            this.order.route.from_address = "";
        },

        clearToField() {
            this.order.route.to = [];
            this.order.route.to_address = "";
            this.order.getOrderPrice();
        },

        detectFromLocation() {
            let location = ymaps.geolocation.get();
            location.then(result => {
                let userAddress = result.geoObjects.get(0).properties.get("text");
                let userCoordinates = result.geoObjects.get(0).geometry.getCoordinates();
                this.order.route.from_address = userAddress;
            });
        },

        switchFromTo() {
            let from = this.order.route.from_address;
            let to = this.order.route.to_address;
            this.order.route.from_address = to;
            this.order.route.to_address = from;
        },

        close() {
            this.order.time.time = moment().format("YYYY-MM-DD HH:mm");
        },
    },

    created() {
        ymaps.ready(this.initSuggest);
        this.setOrderValues();
        this.order.openOrderModal();
    },

    beforeDestroy() {
        this.order.closeDialog(this.employee.client.phone, this.employee.company_id);
    },
};
