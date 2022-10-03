/** @format */

import { mapMutations, mapStates } from "vuex";
import Vue from "vue";
import Snackbar from "../facades/Snackbar";
import { Map } from "./Map";
import store from "../store";

/** @format */
export const Broadcast = Vue.util.mergeOptions(Map, {
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
        ...mapMutations(["initNotification", "initOrderFeedback", "initOrderAction"]), // @todo

        broadcastEvents() {
            if ("undefined" === typeof this.client.before_auth_client_id) {
                let broadcastAuth = Echo.join(
                    `${process.env.MIX_CHANNEL_CLIENT_WEB}-base.${this.client.client_id}.${this.client.phone}`,
                );
                this.broadcast = broadcastAuth;
                // noinspection JSUnresolvedFunction
                broadcastAuth
                    .listen("ListenRadiusTaxiEvent", data => {
                        this.clientLessonRadiusTaxiEvent(data);
                    })
                    .listen("ListenTaxiPositionEvent", driver => {
                        this.listenTaxiPositionEvent(driver.driver);
                    })
                    .listen("DriverOnAcceptOrderEvent", data => {
                        this.driverOnAcceptOrder(data.payload);
                    })
                    .listen("DriverOnWayOrderEvent", data => {
                        this.driverOnWayOrderEvent(data.payload);
                    })
                    .listen("DriverInPlace", driver => {
                        this.driverInPlaceEvent(driver.payload);
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
                        //@todo
                    })
                    .listen("PassScheduleAcceptedEvent", acceptSchedule => {
                        this.scheduleOrderAcceptEvent(acceptSchedule);
                    })
                    .listen("NonDriverEvent", nonDriver => {
                        this.nonDriverInSearch(nonDriver);
                    })
                    .listen("OrderReset", driverResetEvent => {
                        this.driverOrderReset(driverResetEvent);
                    })
                    .listen("OrderStarted", started => {
                        this.initOrderProgress({
                            status: true,
                            radius: false,
                            cancel: false,
                            connection: false,
                            text: started.text,
                        });
                    })
                    .listen("AdminOrderCancel", canceled => {
                        this.adminCancelOrder(canceled);
                    })
                    .notification(notify => {
                        console.log(notify);
                    });
            } else {
                let broadcastAuth = Echo.join(
                    `${process.env.MIX_CHANNEL_BEFORE_CLIENT_WEB}-base.${this.client.before_auth_client_id}.${this.client.hash}`,
                );
                this.broadcast = broadcastAuth;

                broadcastAuth.listen("ListenRadiusTaxiEvent", data => {
                    this.clientLessonRadiusTaxiEvent(data);
                });
            }
        },

        /**
         * Driver reset order
         * @param data
         */
        driverOrderReset(data) {
            this.initOrderAction({ inOrder: false });
            this.initOrderProgress({ status: false });
            this.initOrderFeedback({ status: true });
        },

        adminCancelOrder(canceled) {
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

        /**
         * Non Driver after search order
         * @param data
         */
        nonDriverInSearch(data) {
            this.initOrderProgress({ text: data.message, cancel: false });

            setTimeout(() => {
                this.initOrderProgress({ status: false });
                this.initOrderAction({ status: false });
            }, 5000);

            this.initOrderForm({ open: true });
        },

        /**
         * Driver accept schedule Order
         * @param data
         */
        scheduleOrderAcceptEvent(data) {
            new Audio("/storage/media/client_notify.mp3").play();
            this.initNotification({ show: true, count: 1, data: data });
        },

        /**
         * Driver In order update position
         * @param driver
         */
        listenTaxiPositionEvent(driver) {
            this.createUpdateDriver(driver);
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
                if (data.hasOwnProperty("status") && "deleted" === data.status) {
                    this.deleteDriver(drivers);
                } else if (data.hasOwnProperty("status") && "updated" === data.status) {
                    this.createUpdateDriver(drivers[0]);
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
         * Driver accept order
         * @param payload
         */
        driverOnAcceptOrder(payload) {
            this.initOrderProgress({ status: true, onAccept: true, connection: true, text: payload.text });
            this.initDriver(payload);
            this.initCar(payload.car);
        },

        /**
         * Driver OnWay To ClientMessage
         *
         * @param payload
         */
        driverOnWayOrderEvent(payload) {
            this.removeDriverMarks();
            this.addInGeoObject(payload);

            this.initOrderProgress({ status: true, onWay: true, text: payload.text });
            this.initDriver(payload);
            this.initCar(payload.car);
        },

        /**
         * Driver In Place ClientMessage Home
         *
         * @param driver
         */
        driverInPlaceEvent(driver) {
            this.initOrderProgress({
                status: true,
                cancel: true,
                onWay: false,
                inPlace: true,
                text: "Водитель на месте",
                searchDriverValueView: false,
                showCordContent: false,
            });

            new Audio("/storage/media/taxi_waiting_end.mp3").play();
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
                price: orderEnd.price + orderEnd.currency,
                distance: orderEnd.distance,
                duration: orderEnd.duration,
            });

            setTimeout(() => {
                this.initOrderProgress({ status: false });
                this.initOrderAction({ status: false });
            }, 10000);

            this.initOrderFeedback({ status: true, isRating: true, completedOrderId: orderEnd.orderId });
        },

        /**
         * Order taxi search Time end
         * @param timeout
         */
        orderTimeout(timeout) {
            this.initOrderAction({ in_order: false });
            Snackbar.info("Order Is Timeout");
        },
    },
});
