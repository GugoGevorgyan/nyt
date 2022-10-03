/** @format */

import Snackbar from "../facades/Snackbar";
import moment from "moment";
import Vue from "vue";
import { mapState } from "vuex";
import { ORDER_STATUS } from "../plugins/config";

export const Order = Vue.util.mergeOptions("", {
    ...mapState(["client"]),

    computed: {
        location: {
            get() {
                return this.$store.state.app.navigateCord;
            },
            set(value) {
                this.$store.state.app.navigateCord = value;
            },
        },
    },

    methods: {
        makeOrder(event) {
            event.preventDefault();
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.$http
                        .post("/order", {
                            is_rent: this.order.is_rent,
                            rent_time: this.order.rent_time,
                            route: {
                                from: this.order.address_from_coordinates,
                                to: this.order.address_to_coordinates,
                                from_address: this.order.address_from,
                                to_address: this.order.address_to,
                            },
                            time: {
                                create_time: this.order.order_time.create_time,
                                time: this.order.order_time.time,
                                zone: this.order.order_time.zone,
                            },
                            payment: {
                                type: this.order.payment_type,
                                company: this.order.payment_type_company,
                                card: this.order.payment_type_card,
                            },
                            car: {
                                class: this.order.car_class_id,
                                options: this.order.car_option,
                                comments: this.order.comment,
                            },
                            phone: {
                                client: this.client.phone,
                                passenger: !this.imPassenger ? this.order.passenger_phone : null,
                            },
                            meet: {
                                is_meet: this.order.meet.is_meet,
                                place_id: this.order.meet.transport_id ? this.order.meet.transport_id : null,
                                place_type: this.order.meet.type ? this.order.meet.type : null,
                                number: this.order.meet.number ? this.order.meet.number : null,
                                text: this.order.meet.text ? this.order.meet.text : null,
                            },
                        })
                        .then(res => {
                            this.makeOrderAccept(res);
                        })
                        .catch(error => {
                            Snackbar.info(error.response.data.message);
                        });
                }
            });
        },

        makeOrderAccept(data) {
            let createDate = moment(this.order.order_time.create_time.slice(0, 16));
            let orderDate = moment(this.order.order_time.time);

            if (0 === createDate.diff(orderDate)) {
                // this.initOrderAction({ status: true, data: this.order, responseData: data });
                this.initOrderProgress({ status: true, cancel: true, showCordContent: false });
                // this.initOrderForm({ open: false });
            } else {
                Snackbar.info(data.message);
            }
        },

        getOrderPrice() {
            if (!this.$dash.isEmpty(this.order.address_from_coordinates)) {
                this.mainLoader = true;
                this.initOrderForm({ pricePending: true });
                this.$http
                    .post("m/init_coin", {
                        is_rent: this.order.is_rent,
                        rent_time: this.order.rent_time,
                        route: {
                            from: this.order.address_from_coordinates,
                            to: this.order.address_to_coordinates,
                            from_address: this.order.address_from,
                            to_address: this.order.address_to,
                        },
                        time: {
                            create_time: this.order.order_time.create_time,
                            time: this.order.order_time.time,
                            zone: this.order.order_time.zone,
                        },
                        payment: {
                            type: this.order.payment_type,
                            company: this.order.payment_type_company,
                            card: this.order.payment_type_card,
                        },
                        car: {
                            class: this.order.car_class_id,
                            options: this.order.car_option,
                            comments: this.order.comment,
                        },
                        phone: {
                            client: this.client.phone,
                            passenger: this.order.passenger_phone,
                        },
                    })
                    .then(response => {
                        this.mainLoader = false;
                        this.initOrderForm({ pricePending: false });
                        this.parseCoinResult(response.data);
                        this.errorHandler(response.data).forEach(error => this.errors.add(error));
                    })
                    .catch(error => {
                        this.mainLoader = false;
                        this.initOrderForm({ priceText: error.response.data.message, pricePending: false });
                        this.errorHandler(error.response).forEach(error => this.errors.add(error));
                    });
            }
        },

        initialize(client = true) {
            if (client) {
                this.initClient(this.initialClient);
                this.initialClient.hasOwnProperty("hash")
                    ? (document.cookie = `humanoid=${this.initialClient.hash};secure;SameSite=strict`)
                    : null;
            }

            this.$http.post("/online", { status: 1 }).then(res => {
                switch (res.data._payload.state.status) {
                    case ORDER_STATUS.STATELESS:
                        this.initPayloadData();
                        break;
                    case ORDER_STATUS.PENDING_SEARCH:
                        this.orderInit({ open: false });
                        this.initOrderForm({ status: true });
                        this.initOrderProgress({ status: true, cancel: true, showCordContent: false })
                        break;
                    case ORDER_STATUS.EXPECT_TAXI:
                        this.orderInit({ open: false });
                        this.initOrderProgress({ status: true, onWay: true });
                        break;
                    case ORDER_STATUS.WAITING_TAXI:
                        this.orderInit({ open: false });
                        this.initOrderProgress({ status: true, onWay: true });
                        break;
                    case ORDER_STATUS.IN_ORDER:
                        this.orderInit({ open: false });
                        this.initOrderProgress({ status: true, onWay: true });
                        break;
                    case ORDER_STATUS.ASSESSMENT:
                        this.orderInit({ open: false });
                        this.initOrderProgress({ status: true, onWay: true });
                        break;
                }
            });
        },

        initPayloadData() {
            this.mainLoader = true;
            let hasLocation = Object.keys(this.location).length;
            let url = "/init" + (hasLocation ? `/${this.location.lat}/${this.location.lut}` : "");
            this.$http.get(url).then(res => {
                !hasLocation ? (this.location = res.data._payload.location) : null;
                this.paymentMethods = res.data._payload.payment_types;
                this.carClasses = res.data._payload.car_classes;
                res.data._payload.phone_mask ? this.initOrderForm({ phoneMask: res.data._payload.phone_mask }) : null;
                let index = this.carClassPrice || 0;
                this.demands = res.data._payload.car_classes[index].options.map(option => {
                    return Object.assign(option, { option: option.option + " " + option.price });
                });
                let rent_times = res.data._payload.rent_times.map(item => {
                    return { id: item, name: item + " ч." };
                });
                this.order.rent_time = rent_times[0] ? rent_times[0].id : "";
                this.initClient({ companies: res.data._payload.companies });
                this.initOrderForm({ rent_times: rent_times });
                this.mainLoader = false;
            });
        },
    },
});
