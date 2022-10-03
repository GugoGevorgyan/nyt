/** @format */

import Snackbar from "../facades/Snackbar";
import { mapMutations } from "vuex";
import store from "../store";

/** @format */

export default {
    computed: {
        broadcast: {
            get() {
                return store.state.broadcast;
            },
            set(val) {
                store.state.broadcast = val;
            },
        },
    },

    methods: {
        ...mapMutations([
            "initNotification",
            "initOrderFeedback",
            "INIT_IN_ORDER_ACTION",
            "initOrderForm",
            "initDriver",
            "initCar",
            "initOrderProgress",
        ]),

        broadcastEvents() {
            if ("undefined" !== typeof this.client.client_id && this.client.client_id) {
                let c_name = `${process.env.MIX_CHANNEL_CLIENT_WEB}-base.${this.client.client_id}.${this.client.phone}`;
                this.broadcast = Echo.join(c_name)
                    .listen("ListenRadiusTaxiEvent", data => {
                        this.clientLessonRadiusTaxiEvent(data);
                    })
                    .listen("DriverOnAcceptOrderEvent", payload => {
                        this.initOrderProgress({ status: false, pending: false, radius: false });
                        this.clientPassDriverOnWayOrderEvent(payload);
                    })
                    .listen("DriverOnWayOrderEvent", payload => {
                        this.clientPassDriverOnWayOrderEvent(payload);
                    })
                    .listen("ListenTaxiPositionEvent", driver => {
                        this.listenTaxiPositionEvent(driver.driver);
                    })
                    .listen("DriverInPlace", payload => {
                        this.driverInPlaceEvent(payload);
                    })
                    .listen("TimeFreeWaitEnd", driver => {
                        this.driverClientTimeFreeWaitEnd(driver);
                    })
                    .listen("ClientOrderEndData", orderEnd => {
                        this.orderEndData(orderEnd);
                    })
                    .listen("SearchDriverTimeoutEvent", searchTimeout => {
                        this.orderTimeout(searchTimeout);
                    })
                    .listen("ClientPassOrderPrice", sitPrice => {
                        this.parseCoinResult(sitPrice);
                    })
                    .listen("PassScheduleAcceptedEvent", acceptSchedule => {
                        this.scheduleOrderAcceptEvent(acceptSchedule);
                    })
                    .listen("NonDriverEvent", nonDriver => {
                        this.nonDriverInSearch(nonDriver);
                    })
                    .listen("OrderReset", driverResetEvent => {
                        this.initOrderProgress({ status: false });
                        this.driverOrderReset(driverResetEvent);
                    })
                    .listen("OrderStarted", started => {
                        this.initOrderProgress({
                            status: true,
                            radius: false,
                            cancel: false,
                            accept: false,
                            onWay: false,
                            inPlace: false,
                            started: true,
                            text: started.text });
                    })
                    .listen("AdminOrderCancel", canceled => {
                        this.admOrdCancel(canceled);
                    });
            } else {
                let c_name = `${process.env.MIX_CHANNEL_BEFORE_CLIENT_WEB}-base.${this.client.before_auth_client_id}.${this.client.hash}`;
                let broadcastAuth = Echo.join(c_name);
                this.broadcast = broadcastAuth;

                broadcastAuth.listen("ListenRadiusTaxiEvent", data => {
                    this.clientLessonRadiusTaxiEvent(data);
                });
            }
        },

        admOrdCancel(canceled) {
            this.INIT_IN_ORDER_ACTION({ status: false, data: [], responseData: [] });
            this.initOrderProgress({
                status: true,
                radius: true,
                onWay: false,
                inPlace: false,
                cancel: false,
                text: canceled.message,
            });
            this.initOrderForm({ open: true });
            this.initOrderFeedback({ status: false });
            setTimeout(() => {
                this.initOrderProgress({ status: false });
            }, 3000);
        },

        driverOrderReset(data) {
            this.initOrderProgress({
                status: true,
                pending: true,
                onWay : false,
                accept: false,
                radius: true,
                text: "Поиск машины"
            });
        },

        nonDriverInSearch(data) {
            this.initOrderProgress({ text: data.message, cancel: false });

            setTimeout(() => {
                this.initOrderProgress({ status: false });
                this.initOrderAction({ status: false });
            }, 5000);

            this.initOrderForm({ open: true });
        },

        scheduleOrderAcceptEvent(data) {
            new Audio("/storage/media/client_notify.mp3").play();
            this.initNotification({ show: true, count: 1, data: data });
        },

        /**
         * ClientMessage View Taxi radius 3000M after open Page
         *
         * @param data
         */
        clientLessonRadiusTaxiEvent(data) {
            let drivers = data.taxis;

            if (0 === Object.entries(drivers).length) {
                this.removeDriverMarks();
            } else {
                if ("deleted" in data) {
                    this.deleteDriver(drivers);
                } else if ("updated" in data) {
                    this.createUpdateDriver(drivers);
                } else {
                    this.removeDriverMarks();
                    this.carGeoObjects = new ymaps.GeoObjectCollection();

                    for (let [key, driver] of Object.entries(drivers)) {
                        this.addInGeoObject(driver);
                    }

                    this.maps.map.geoObjects.add(this.carGeoObjects);
                }
            }
        },

        /**
         * Driver OnWay To ClientMessage
         *
         * @param payload
         */
        clientPassDriverOnWayOrderEvent(payload) {
            this.removeDriverMarks();
            this.initOrderProgress({ status: true, onWay: true, text: payload.message, pending: false });

            // this.addInGeoObject(payload);

            this.initOrderForm({ open: false });

            this.initCar({
                mark: payload.payload.car.mark,
                model: payload.payload.car.model,
                color: payload.payload.car.color,
                sts_number: payload.payload.car.sts_number,
            });

            this.initDriver({
                name: payload.payload.name,
                surname: payload.payload.surname,
                photo: payload.payload.photo,
            });

        },

        /**
         * On Way Driver Update Coordinate
         *
         * @param driver
         */
        listenTaxiPositionEvent(driver) {
            this.createUpdateDriver(driver);
        },

        /**
         * Driver In Place ClientMessage Home
         *
         * @param driver
         */
        driverInPlaceEvent(payload) {
            new Audio("/storage/media/taxi_waiting_end.mp3").play();
            this.initOrderProgress({
                status: true,
                cancel: true,
                accept: false,
                onWay: false,
                inPlace: true,
                pending: false,
                text: payload.message,
                features: {
                    free_wait_minute: payload.tariff_features.free_wait_minute,
                    paid_wait_minute: payload.tariff_features.paid_wait_minute
                }
            });
            this.initDriver({
                name: payload.payload.name,
                surname: payload.payload.surname,
                phone: payload.payload.phone,
                photo: payload.payload.photo,
            });
            this.initCar({
                color: payload.payload.car.color,
                mark: payload.payload.car.mark,
                model: payload.payload.car.model,
                license_plate: payload.payload.car.state_license_plate,
            });
        },

        /**
         * ClientMessage Waiting Time End
         *
         * @param driver
         */
        driverClientTimeFreeWaitEnd(driver) {
            let play = new Audio("/storage/media/taxi_waiting_end.mp3").play();
        },

        /**
         *  ORDER END EVENT
         * @param orderEnd
         */
        orderEndData(orderEnd) {
            this.initOrderProgress({
                text: "Order Ended Price is " + `${orderEnd.price} ${orderEnd.currency}`,
                cancel: false,
            });

            setTimeout(() => {
                this.initOrderProgress({ status: false });
                this.initOrderAction({ status: false });
            }, 10000);

            this.initOrderFeedback({ status: true, isRating: true, completedOrderId: orderEnd.orderId });
        },

        orderTimeout(timeout) {
            this.initOrderAction({ in_order: false });
            Snackbar.info("Order Is Timeout");
        },

        /**
         * ClientMessage View Taxi radius 3000M after open Page
         *
         * @param driver
         */
        deleteDriver(driver) {
            let index = this.placeMarks.findIndex(item => {
                return item.driver.driver_id === driver.driver_id;
            });

            if (-1 !== index) {
                this.carGeoObjects.splice(index, 1);
            }
        },

        /**
         * ClientMessage View Taxi radius 1000M after open Page
         */
        removeDriverMarks() {
            if (false !== this.carGeoObjects) {
                this.carGeoObjects.removeAll();
            }

            this.carGeoObjects = new ymaps.GeoObjectCollection();
        },

        createUpdateDriver(driver) {
            let index = this.placeMarks.findIndex(item => {
                return item.driver.driver_id === driver.driver_id;
            });

            if (-1 === index) {
                this.hasGeoObjectsAddDriver(driver);
            } else {
                this.carGeoObjects.splice(index, 1, this.createDriverMark(driver));
            }
        },

        /**
         * Add to geo object
         *
         * @param driver
         */
        addInGeoObject(driver) {
            let placeMark = this.createDriverMark(driver);
            this.placeMarks.push({ driver: driver, placeMark: placeMark });
            this.carGeoObjects.add(placeMark);
        },
    },
};
