/** @format */

import Snackbar from '../../../facades/Snackbar';
import { DRIVER_STATUS } from '../../../plugins/config';
import { broadcasting, orderMapListen, orderMapObjects } from '../../../mixins/CallCenter';

export default {
    props: {
        socketData: {
            required: true,
        },
        height: {
            required: true,
        },
        pendingOrders: {
            required: true,
            type: Array,
        },
        drivers: {
            required: true,
            type: Array,
        },
        driverStatuses: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            mapOverlay: false,
            mapButtonsHeight: 0,
            orders: this.pendingOrders,
            map: undefined,
            mapObjects: [],
            geoObjects: false,
            route: null,
            cancelAttachBtn: undefined,
            attach: false,
            attachingOrders: [],
            showDriversStatus: null,
            showPendingOrders: true,
            regions: [],
            regionsLoading: false,
            selectedMapRegion: null,
            mapCordCenter: [55.755819, 37.617644],
            mapCordZoom: 12,
            placeMarkOptions: {
                driver: {
                    attach: false,
                    attachOrder: null,
                },
                order: {
                    attached: false,
                    foreignBoard: false,
                },
            },
        };
    },

    mixins: [broadcasting, orderMapListen, orderMapObjects],

    watch: {
        "socketData.orderCreated": {
            deep: true,
            handler() {
                if (1 === this.socketData.orderCreated.status.status) {
                    this.orders = this.__addToOrderList(this.orders, this.socketData.orderCreated);
                    this.showOrders();
                }
            },
        },
        "socketData.orderUpdated": {
            deep: true,
            handler() {
                this.orders = this.__updateOrderListByStatus(this.orders, this.socketData.orderUpdated, 1);
                this.showOrders();
            },
        },
        "socketData.orderCommon": {
            deep: true,
            handler() {
                this.orders = this.__updateOrderCommon(this.orders, this.socketData.orderCommon);
                this.showOrders();
            },
        },
        "socketData.orderShipped": {
            deep: true,
            handler() {
                this.orders = this.__updateOrderShipped(this.orders, this.socketData.orderShipped);
                this.showOrders();
            },
        },
        "socketData.driverUpdated": {
            deep: true,
            handler() {
                this.updatePlaceMarkDriver(JSON.parse(JSON.stringify(this.socketData.driverUpdated)));
            },
        },
        "socketData.driverShipped": {
            deep: true,
            handler() {
                this.updateDriverSocketShipped(JSON.parse(JSON.stringify(this.socketData.driverShipped)));
            },
        },

        orders: function () {
            this.showOrders();
        },
        showDriversStatus: function () {
            this.showDrivers();
        },
        showPendingOrders: function () {
            this.showOrders();
        },
        drivers: function () {
            this.showDrivers();
        },
        selectedMapRegion: function (val) {
            let index = this.regions.findIndex(item => {
                if (item && item.region_id === val) {
                    return item;
                }
            });
            let region = this.regions[index];
            if (region && region.cord) {
                this.mapCordCenter = [region.cord.lat, region.cord.lut];
                this.mapCordZoom = 13;
                this.map.setCenter(this.mapCordCenter);
                this.map.setZoom(this.mapCordZoom);
            }
        },
    },

    computed: {
        auth: function () {
            return this.$store.state.auth;
        },
        freeDrivers: function () {
            return this.drivers.filter(item => "IS_FREE" === item.status.name);
        },
        selectedOrder: function () {
            return this.mapObjects.find(item => "selected_order" === item.type)
                ? this.mapObjects.find(item => "selected_order" === item.type).obj
                : undefined;
        },
        mapHeight: function () {
            return this.height - this.mapButtonsHeight - 5;
        },
    },

    methods: {
        /*map html*/
        commaJoin(arr, keys) {
            if ("object" === typeof keys) {
                return arr
                    .map(item => {
                        let result = [];
                        Object.keys(keys).forEach(key => {
                            let x = [];
                            keys[key].forEach(value => {
                                x.push(item[key][value]);
                            });
                            result.push(x.join(" "));
                        });
                        return result;
                    })
                    .join(", ");
            } else {
                return keys
                    ? arr
                          .map(item => {
                              return item[keys];
                          })
                          .join(", ")
                    : arr.join(", ");
            }
        },

        /**
         * @param driver
         * @returns {any|boolean}
         */
        driverIsPending(driver) {
            return (
                driver.active_order_shipment &&
                driver.active_order_shipment.status &&
                1 === driver.active_order_shipment.status.status
            );
        },

        /**
         * @param driver
         * @returns {boolean}
         */
        driverIsAcceptPending(driver) {
            return (
                driver.current_status_id === DRIVER_STATUS.ON_WAY ||
                driver.current_status_id === DRIVER_STATUS.ON_ACCEPT
            );
        },

        /*drivers*/
        updateDriverSocketShipped(shipped) {
            let index = this.drivers.findIndex(item => {
                return item.driver_id === shipped.driver_id;
            });
            if (~index) {
                this.drivers[index].active_order_shipment = shipped.shipped;
                this.updatePlaceMarkDriver(this.drivers[index]);
            }
        },

        /*map*/
        init() {
            this.map = new ymaps.Map("map", {
                center: this.mapCordCenter,
                zoom: this.mapCordZoom,
                controls: [],
            });
            let trafficControl = new ymaps.control.TrafficControl({
                layout: "round#buttonLayout",
                options: {
                    maxWidth: "small",
                },
            });
            let fullScreenControl = new ymaps.control.FullscreenControl({
                layout: "round#buttonLayout",
                options: {
                    maxWidth: "small",
                },
            });
            let zoomControl = new ymaps.control.ZoomControl({
                options: {
                    layout: "round#zoomLayout",
                    position: {
                        right: 10,
                        bottom: 30,
                    },
                },
            });
            let geolocationControl = new ymaps.control.GeolocationControl({
                options: {
                    layout: "round#buttonLayout",
                    position: {
                        left: 8,
                        bottom: 40,
                    },
                },
            });
            let typeSelector = new ymaps.control.TypeSelector({
                options: {
                    layout: "ro und#listBoxLayout",
                    itemLayout: "round#listBoxItemLayout",
                    itemSelectableLayout: "round#listBoxItemSelectableLayout",
                    float: "none",
                    position: {
                        bottom: 40,
                        left: "60px",
                    },
                },
            });
            this.map.controls.add(typeSelector);
            this.map.controls.add(fullScreenControl);
            this.map.controls.add(trafficControl);
            this.map.controls.add(zoomControl);
            this.map.controls.add(geolocationControl);

            this.geoObjects = new ymaps.GeoObjectCollection();
            this.map.geoObjects.add(this.geoObjects);
            this.map.events.add("boundschange", function (event) {
                let oldBounds = event.get("oldBounds");
                let newBounds = event.get("newBounds");
            });

            this.showDrivers();
            this.showOrders();
        },

        mapSetObjects() {
            this.placeMarkOptions = {
                driver: {
                    attach: false,
                    attachOrder: null,
                },
                order: {
                    attached: false,
                },
            };
            this.map.controls.remove(this.cancelAttachBtn);
            this.mapObjects = [];
            this.geoObjects.removeAll();
            this.showDrivers();
            this.showOrders();
        },

        setMapRoute(targets) {
            this.route = new ymaps.multiRouter.MultiRoute(
                {
                    referencePoints: targets,
                },
                {
                    wayPointStartIconColor: "#FFFFFF",
                    wayPointStartIconFillColor: "#B3B3B3",
                    routeActiveStrokeWidth: 2,
                    routeActiveStrokeStyle: "solid",
                    routeActiveStrokeColor: "#002233",
                    routeStrokeStyle: "dot",
                    routeStrokeWidth: targets.length,
                    boundsAutoApply: true,
                },
            );
            this.map.geoObjects.add(this.route);
        },

        /*driver place mark*/
        showDrivers() {
            this.removePlaceMarksByType("driver");
            if (this.map) {
                for (let i = 0; i < this.drivers.length; ++i) {
                    if (!this.showDriversStatus || this.showDriversStatus === this.drivers[i].status.status) {
                        this.driverMapObj(this.drivers[i]);
                    }
                }
            }
        },

        driverMapObj(driver) {
            let placeMark = this.createDriverPlaceMark(driver);
            this.mapObjects.push({ type: "driver", obj: driver, placeMark: placeMark });
            this.geoObjects.add(placeMark);
        },

        driverMapObjRemove(driver, index) {
            this.geoObjects.remove(this.mapObjects[index].placeMark);
            this.mapObjects.splice(index, 1);
        },

        updatePlaceMarkDriver(driver) {
            let index = this.mapObjects.findIndex(item => {
                return "driver" === item.type && item.obj.driver_id === driver.driver_id;
            });

            if (-1 !== index && (!driver.logged || !driver.online || !driver.is_ready)) {
                this.driverMapObjRemove(driver, index);
            } else {
                ~index ? this.geoObjects.remove(this.mapObjects[index].placeMark) : this.mapObjects.push(driver);

                let newPlaceMark = this.createDriverPlaceMark(driver);
                this.mapObjects.splice(index, 1, {
                    type: 'driver',
                    obj: driver,
                    placeMark: newPlaceMark,
                });
                this.geoObjects.add(newPlaceMark);
            }
        },

        /*order place mark*/
        showOrders() {
            this.removePlaceMarksByType("order");

            if (this.map && this.showPendingOrders) {
                for (let i = 0; i < this.orders.length; ++i) {
                    this.orderMapObj(this.orders[i]);
                }
            }
        },

        orderMapObj(order) {
            let placeMark = this.createOrderPlaceMark(order);

            this.mapObjects.push({
                type: "order",
                obj: order,
                placeMark: placeMark,
            });
            this.geoObjects.add(placeMark);
        },

        showAttachPlaceMarks(order) {
            this.mapObjects = [];
            this.geoObjects.removeAll();
            this.drivers.forEach(item => {
                if (1 === item.status.status) {
                    this.driverMapObj(item);
                }
            });
            this.orderMapObj(order);
        },

        /*call*/
        call(phone) {
            this.$emit("call", phone);
        },

        attachCallback(shipped) {
            this.attachingOrders.forEach(item => {
                if (item.order_id === shipped.order_id && item.driver_id === shipped.driver_id) {
                    if (2 === shipped.status.status) {
                        Snackbar.info("Водитель принял прикрепленный заказ");
                    } else if (3 === shipped.status.status) {
                        Snackbar.error("Водитель отклонил прикрепленный заказ");
                    }
                }
            });
        },

        getRegions() {
            if (!this.regions.length) {
                this.regionsLoading = true;
                this.$http
                    .get(`${process.env.MIX_APP_WORKER_URL}get/regions`)
                    .then(response => {
                        this.regionsLoading = false;
                        this.regions = response.data.regions.regions.map(item => {
                            if (item.cord) {
                                return item;
                            }
                        });
                    })
                    .catch(e => {
                        if (e.response && 422 === e.response.status) {
                            this.regionsLoading = false;
                            this.regionsSelectable = [];
                        }
                    });
            }
        },

        /*actions*/
        driverAddOrder(driver_id, order_id) {
            this.mapOverlay = true;
            this.$http
                .post("call-center/driver-add-order", { driver_id: driver_id, order_id: order_id })
                .then(() => {
                    this.attachingOrders.push({ order_id: order_id, driver_id: driver_id });
                    this.mapOverlay = false;
                    this.mapSetObjects();
                })
                .catch(error => {
                    this.mapOverlay = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        orderChangeManual(order_id) {
            this.$http
                .put(`${process.env.MIX_APP_WORKER_URL}call-center-dispatcher/ord_re_manual/${order_id}`)
                .then(r => {
                    this.showOrders();
                })
                .catch(e => {
                    if (e.response && 422 === e.response.status) {
                        Snackbar.info(e.response.data.message);
                    }
                });
        },

        orderUnpinInDriver(driver_id) {
            this.$http
                .put(`${process.env.MIX_APP_WORKER_URL}call-center-dispatcher/dr_ord_unpin/${driver_id}`)
                .then(r => {
                    self.mapSetObjects();
                })
                .catch(e => {
                    if (e.response && 422 === e.response.status) {
                        Snackbar.info(e.response.data.message);
                    }
                });
        },
    },

    mounted() {
        this.mapButtonsHeight = this.$refs.buttons.clientHeight;
    },

    created() {
        ymaps.ready(this.init);
        this.getRegions();
    },
};
