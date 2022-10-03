/** @format */

import axios from 'axios';
import { mapMutations } from 'vuex';
import { ORDER_STATUS } from '../plugins/config';
import Snackbar from '../facades/Snackbar';
import moment from 'moment';

export default {
    computed: {
        location: {
            get() {
                return this.$store.state.app.navigateCord;
            },
            set(value) {
                this.$store.state.app.navigateCord = value;
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
    },

    methods: {
        ...mapMutations({
            initOrderProgress: "initOrderProgress",
            initOrderAction: "initOrderAction",
            orderInit: "orderInit",
            initOrderFeedback: "initOrderFeedback",
            initOrderForm: "initOrderForm",
            initCar: "initCar",
            initDriver: "initDriver",
        }),

        clientInOrderData(client = true) {
            if (client) {
                this.initClient(this.initialClient);
                this.initialClient
                    ? this.initialClient.hasOwnProperty("hash")
                        ? (document.cookie = `humanoid=${this.initialClient.hash};secure;SameSite=strict`)
                        : null
                    : "";
            }

            axios
                .post("/online", { status: 1 })
                .then(res => {
                    this.initOrderForm({ disablePreorder: res.data._payload.preorder });

                    switch (res.data._payload.state.status) {
                        case ORDER_STATUS.STATELESS:
                            this.initOrderForm({ open: true });
                            this.initPayloadData(res.data._payload);
                            break;

                        case ORDER_STATUS.PENDING_SEARCH:
                            this.setOrderFields(res.data._payload.order);
                            this.initOrderForm({ open: false });
                            this.initOrderProgress({
                                status: true,
                                cancel: true,
                                pending: true,
                                text: res.data._payload.state.message,
                            });
                            this.initOrderAction({
                                status: true,
                                data: this.order,
                                responseData: res.data._payload.action
                            });
                            break;

                        case ORDER_STATUS.ACCEPT_ORDER:
                            this.initOrderProgress({
                                status: true,
                                cancel: true,
                                accept: true,
                                text: res.data._payload.state.message,
                            });
                            this.generalInits(res.data._payload);
                            break;

                        case ORDER_STATUS.EXPECT_TAXI:
                            this.initOrderProgress({
                                status: true,
                                cancel: true,
                                onWay: true,
                                text: res.data._payload.state.message,
                                minute: res.data._payload.state.minute,
                            });
                            this.generalInits(res.data._payload);
                            break;

                        case ORDER_STATUS.WAITING_TAXI:
                            this.initOrderProgress({
                                status: true,
                                cancel: true,
                                inPlace: true,
                                text: res.data._payload.state.message,
                                features: {
                                    free_wait_minute: res.data._payload.state.tariff_features.free_wait_minutes,
                                    paid_wait_minute: res.data._payload.state.tariff_features.paid_wait_minute
                                }
                            });
                            this.generalInits(res.data._payload);
                            break;

                        case ORDER_STATUS.IN_ORDER:
                            this.initOrderForm({ open: false });
                            this.orderInit({ open: false });
                            this.initOrderProgress({
                                status: true,
                                started: true,
                                text: res.data._payload.state.message,
                            });
                            this.generalInits(res.data._payload)
                            break;

                        case ORDER_STATUS.ASSESSMENT:
                            this.orderInit({ open: false });
                            this.initOrderForm({ open: false });
                            this.initOrderFeedback({
                                status: true,
                                isRating: true,
                                completedOrderId: res.data._payload.state.order.order_id,
                            });
                            break;
                    }
                })
                .catch(error => {});
        },

        generalInits(payload)
        {
            this.orderInit({ open: false });
            this.setOrderFields(payload.order);
            this.initOrderForm({ open: false });
            this.initCar({
                mark: payload.state.driver.car.mark,
                model: payload.state.driver.car.model,
                color: payload.state.driver.car.color,
                sts_number: payload.state.driver.car.sts_number,
            });

            this.initDriver({
                name: payload.state.driver.name,
                surname: payload.state.driver.surname,
                photo: payload.state.driver.photo,
            });

            this.initOrderAction({
                status: true,
                data: this.order,
                responseData: payload.action,
            });
        },

        setOrderFields(currentData) {
            this.$store.state.order = {
                order_id: currentData.order_id,
                client_id: currentData.client_id,
                address_from: currentData.address_from,
                address_to: currentData.address_to,
                address_from_coordinates: this.$store.state.order.address_from_coordinates,
                address_to_coordinates: this.$store.state.order.address_to_coordinates,
                payment_type: currentData.payment_type_id,
                car_option: currentData.car_option,
                comment: currentData.comments,
                car_class_id: currentData.car_class_id,
                order_time: {
                    create_time: currentData.created_at,
                    time: this.$store.state.order.order_time.time,
                    zone: this.$store.state.order.order_time.zone,
                },
            };
        },

        initPayloadData(payload) {
            const hasLocation = Object.keys(this.location).length;
            const url = "/init" + (hasLocation ? `/${this.location.lat}/${this.location.lut}` : "");

            axios.get(url).then(res => {
                !hasLocation ? (this.location = res.data._payload.location) : null;
                const paymentMethods = res.data._payload.payment_types;
                const carClasses = res.data._payload.car_classes;
                // const mask = res.data._payload.phone_mask;
                const demands = res.data._payload.car_classes
                    ? res.data._payload.car_classes[0].options.map(option => {
                          return Object.assign(option, { option: option.option + " " + option.price });
                      })
                    : [];
                let rent_times = res.data._payload.rent_times.map(item => {
                    return { id: item, name: item + " ч." };
                });

                if (payload.client_id && payload.phone) {
                    this.initClient({
                        companies: res.data._payload.companies,
                        pay_cards: res.data._payload.pay_cards,
                        client_id: payload.client_id,
                        phone: payload.phone,
                    });
                } else {
                    this.initClient({ companies: res.data._payload.companies, pay_cards: res.data._payload.pay_cards });
                }

                this.initOrderForm({
                    demands: demands,
                    carClasses: carClasses,
                    paymentMethods: paymentMethods,
                    // phoneMask: mask,
                    rent_times: rent_times,
                });
                this.order.rent_time = rent_times.length ? rent_times[0].id : null;
            });
        },

        getOrderPrice() {
            if (!this.$dash.isEmpty(this.order.address_from_coordinates)) {
                this.calcCoin = true;
                this.initOrderForm({ pricePending: true });
             axios.post("/init_coin", {
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
                        is_rent: this.order.is_rent,
                        rent_time: this.order.rent_time,
                    })
                    .then(response => {
                        this.initOrderForm({ pricePending: false });
                        this.parseCoinResult(response.data);
                        this.calcCoin = false;
                    })
                    .catch(error => {
                        if (error.response && (422 === error.response.status || 500 === error.response.status)) {
                            this.errorHandler(error.response).forEach(error => this.errors.add(error));
                            this.initOrderForm({
                                priceText: error.response.data.message || error.message,
                                pricePending: false,
                            });
                            Snackbar.info(error.response.data.message);
                        }

                        this.calcCoin = false;
                    });
            }
        },

        parseCoinResult(data) {
            let message = "";

            if (Array.isArray(data)) {

                let index = data.findIndex((item, index) => {
                    if (item.car_class_id === this.carClassPrice || item.car_class_id === item.selected) {
                        this.initOrderForm({ demands: item.car_options });
                        return index;
                    }
                });

                if (-1 === index) {
                    index = 0;
                }

                let coinData = data[index];
                let coin = coinData.coin + this.optionsPrice;
                let currency = coinData.currency;

                if (coinData.initial && coinData.sitting_fee) {
                    message = `Цена за поездку от ${coin} ${currency}`;
                }
                if (!coinData.initial && coinData.sitting_fee) {
                    message = `Цена за поездку ${coin} ${currency}`;
                }
                if (!coinData.initial && !coinData.sitting_fee) {
                    message = `Цена за поездку ${coin} ${currency}`;
                }
                if (coinData.initial && !coinData.sitting_fee) {
                    message = `Цена за поездку от ${coin} ${currency}`;
                }

                data.forEach(item => {
                    item.coin += this.optionsPrice;
                });

                let rent_times = coinData.rent_times.map(item => {
                    return { id: item, name: item + ' ч.' };
                });

                if (this.order.address_from) {
                    this.initOrderForm({
                        coin: coin,
                        rent_times: rent_times,
                        sitCoin: coinData.sitting_coin,
                        sitFee: coinData.sitting_fee,
                        initial: coinData.initial,
                        priceText: message,
                        carClasses: data,
                    });
                }
                if (-1 === Object.values(coinData.rent_times).indexOf(this.order.rent_time)) {
                    this.order.rent_time = rent_times[0].id;
                }
            }
        },

        makeOrder() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.orderInit({
                        order_time: {
                            create_time: moment().format("YYYY-MM-DD HH:mm:ss"),
                            time: this.$store.state.order.order_time.time > moment().format("YYYY-MM-DD HH:mm")
                                ? this.$store.state.order.order_time.time : moment().format("YYYY-MM-DD HH:mm"),
                            zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                        }
                    })
                    this.initOrderForm({ pricePending: true });
                    axios
                        .post("/order", {
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
                            is_rent: this.order.is_rent,
                            rent_time: this.order.rent_time,
                        })
                        .then(res => {
                            this.initOrderForm({ pricePending: false })
                            // Snackbar.info(res.data.message);
                            this.orderInit({ order_id: res.data.order_id })
                            this.makeOrderAccept(res.data.data);
                        })
                        .catch(error => {
                            Snackbar.info(error.response.data.message);
                            this.initOrderForm({ pricePending: false });
                        });
                }
            });
        },
    },
};
