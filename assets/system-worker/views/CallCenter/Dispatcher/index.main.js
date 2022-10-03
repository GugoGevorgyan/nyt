/** @format */

import SipCall from "../../../components/CallCenter/SipCall/SipCall";
import Snackbar from "../../../facades/Snackbar";
import OrdersMap from "../../../components/CallCenter/Map/OrdersMap";
import { mapState } from "vuex";
import OrdersTable from "./Order/OrdersTable";
import OperatorsTable from "./OperatorsTable/OperatorsTable";
import CallsTable from "./CallsTable/CallsTable";
import BoardsTable from "./Board/BoardsTable";
import AppBar from "../../../components/CallCenter/AppBar/AppBar";

export default {
    props: {
        countryCode: {
            required: true,
        },
        subPhone: {
            type: Object,
            required: true,
        },
        pendingOrders: {
            type: Array,
            required: true,
        },
        orderTypes: {
            type: Array,
            required: true,
        },
        orderStatuses: {
            type: Array,
            required: true,
        },
        driverStatuses: {
            type: Array,
            required: true,
        },
        carClasses: {
            type: Array,
            required: true,
        },
        carOptions: {
            type: Array,
            required: true,
        },
        paymentTypes: {
            type: Array,
            required: true,
        },
        calls: {
            type: Array,
            required: true,
        },
        railwayStations: {
            type: Array,
            required: true,
        },
        airports: {
            type: Array,
            required: true,
        },
        metros: {
            type: Array,
            required: true,
        },
        drivers: {
            type: Array,
            required: true,
        },
    },

    components: {
        AppBar,
        BoardsTable,
        CallsTable,
        OperatorsTable,
        OrdersTable,
        OrdersMap,
        SipCall,
        Snackbar,
    },

    data() {
        return {
            tabsHeight: 0,
            tab: 0,

            window: {
                width: 0,
                height: window.innerHeight,
            },

            call: null,
            callToNumber: null,
            phoneShow: false,
            atcLogged: false,
            atcPhoneLogin: false,

            lateOrder: null,

            socketData: {
                orderCreated: null,
                orderUpdated: null,
                orderShipped: null,
                orderCommon: null,
                highRateOrder: null,

                driverUpdated: null,
                driverShipped: null,
                radiusDriver: null,
                operatorCreated: null,
                operatorUpdated: null,

                callCreated: null,
                callUpdated: null,
            },
        };
    },

    computed: {
        ...mapState(["auth"]),

        contentHeight: {
            get() {
                return this.window.height - this.tabsHeight - 155;
            },
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },
        dialogHeight: {
            get() {
                return this.window.height - 137;
            },
        },
    },

    mounted() {
        this.tabsHeight = this.$refs.tabs.clientHeight;
        const c_name = `${process.env.MIX_CHANNEL_WORKER_WEB}-worker-dispatcher.${this.auth.user.system_worker_id}.${this.auth.user.franchise_id}`;

        /*order*/
        Echo.join(c_name)
            .listen("DispatcherOrderCreated", payload => {
                this.updateOrderPending(payload.order);
                if (payload.hasOwnProperty("order")) {
                    this.socketData.orderCreated = JSON.parse(JSON.stringify(payload.order));
                }
            })
            .listen("DispatcherOrderUpdated", payload => {
                this.updateOrderPending(payload.order);
                if (payload.hasOwnProperty("order")) {
                    this.socketData.orderUpdated = JSON.parse(JSON.stringify(payload.order));
                }
            })
            .listen("DispatcherOrderCommon", payload => {
                if (payload.hasOwnProperty("common")) {
                    this.socketData.orderCommon = JSON.parse(JSON.stringify(payload.common));
                }
            })
            .listen("DispatcherOrderActiveShipped", payload => {
                if (payload.hasOwnProperty("shipped")) {
                    this.socketData.orderShipped = JSON.parse(JSON.stringify(payload.shipped));
                }
            })
            .listen("DispatcherDriverUpdated", payload => {
                this.updateDriversList(payload.driver);
                if (payload.hasOwnProperty("driver")) {
                    this.socketData.driverUpdated = JSON.parse(JSON.stringify(payload.driver));
                }
            })
            .listen("DispatcherDriverActiveShipped", payload => {
                if (payload.hasOwnProperty("shipped")) {
                    this.socketData.driverShipped = JSON.parse(JSON.stringify(payload.shipped));
                }
            })
            .listen("DispatcherOperatorCreated", payload => {
                if (payload.hasOwnProperty("operator")) {
                    this.socketData.operatorCreated = JSON.parse(JSON.stringify(payload.operator));
                }
            })
            .listen("DispatcherOperatorUpdated", payload => {
                if (payload.hasOwnProperty("operator")) {
                    this.socketData.operatorUpdated = JSON.parse(JSON.stringify(payload.operator));
                }
            })
            .listen("DispatcherCallCreated", payload => {
                if (payload.hasOwnProperty("call")) {
                    this.socketData.callCreated = JSON.parse(JSON.stringify(payload.call));
                }
            })
            .listen("DispatcherCallUpdated", payload => {
                if (payload.hasOwnProperty("call")) {
                    this.socketData.callUpdated = JSON.parse(JSON.stringify(payload.call));
                }
            })
            .listen("HighRateOrderEvent", payload => {
                if (payload.hasOwnProperty("call")) {
                    this.socketData.highRateOrder = JSON.parse(JSON.stringify(payload.payload));
                }
            })
            .listen("DriverOrderLate", payload => {
                this.lateOrder = payload.payload;
            })
            .listen("ListenRadiusTaxiEvent", payload => {
                if (payload.hasOwnProperty("taxis")) {
                    this.socketData.radiusDriver = JSON.parse(JSON.stringify(payload.taxis));
                }
            });
    },

    methods: {
        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight;
        },

        /*call*/
        callTo(number) {
            this.phoneShow = true;
            this.callToNumber = number;
        },
        updateDriversList(driver) {
            if (driver.is_ready && driver.online && driver.logged) {
                for (let key in this.drivers) {
                    let value = this.drivers[key]
                    if (value.driver_id === driver.driver_id) {
                        this.drivers.splice(key,1)
                    }
                }
                this.drivers.push({
                    active_order_shipment: driver.is_ready,
                    azimuth: driver.azimuth,
                    car: driver.car,
                    car_id: driver.car_id,
                    current_franchise_id: driver.current_franchise_id,
                    current_status_id: driver.current_status_id,
                    driver_id: driver.driver_id,
                    driver_info: driver.driver_info,
                    driver_info_id: driver.driver_info_id,
                    entity_id: driver.entity_id,
                    is_ready: driver.is_ready,
                    lat: driver.lat,
                    lut: driver.lut,
                    logged: driver.logged,
                    online: driver.online,
                    phone: driver.phone,
                    status: driver.status,
                });
            } else {
                for (let key in this.drivers) {
                    let value = this.drivers[key]
                    if (value.driver_id === driver.driver_id) {
                        this.drivers.splice(key,1)
                    }
                }
            }
        },
        updateOrderPending(order) {
            if (order.status.status !== 1) {
                for (let key in this.pendingOrders) {
                    let value = this.pendingOrders[key]
                    if (value.order_id === order.order_id) {
                        this.pendingOrders.splice(key,1)
                    }
                }
            } else {
                this.pendingOrders.push(order);
            }
        }
    },

    created() {
        window.addEventListener("resize", this.handleResize);
    },
};
