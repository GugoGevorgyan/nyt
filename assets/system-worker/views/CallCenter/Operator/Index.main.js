/** @format */

import orderDialog from "../../../components/CallCenter/OrderDialog";
import SipCall from "../../../components/CallCenter/SipCall/SipCall";
import Snackbar from "../../../facades/Snackbar";
import AppBar from "../../../components/CallCenter/AppBar/AppBar";
import OrdersMap from "../../../components/CallCenter/Map/OrdersMap";
import CallsTable from "./CallsTable/CallsTable";
import OrdersTable from "./Orders/OrdersTable";
import BoardTable from "./Board/BoardsTable";

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

    data() {
        return {
            ordersTableFull: "true" === localStorage.getItem("oper_window"),

            window: {
                width: 0,
                height: window.innerHeight,
            },

            call: null,
            callToNumber: null,
            phoneShow: false,
            atcLogged: false,
            atcPhoneLogin: false,
            tab: 0,

            socketData: {
                orderCreated: null,
                orderUpdated: null,
                orderShipped: null,
                orderCommon: null,
                radiusDriver: null,

                driverUpdated: null,
                driverShipped: null,
            },
        };
    },

    watch: {
        ordersTableFull: function (val) {
            localStorage.setItem("oper_window", val);
        },
    },

    computed: {
        auth() {
            return this.$store.state.auth;
        },

        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },

        contentHeight() {
            return this.window.height - 135;
        },

        dialogHeight() {
            return this.window.height - 137;
        },
    },

    created() {
        window.addEventListener("resize", this.handleResize);
    },

    mounted() {
        /*order*/
        const c_name = `${process.env.MIX_CHANNEL_WORKER_WEB}-worker-operator.${this.auth.user.system_worker_id}.${this.auth.user.franchise_id}`;

        Echo.join(c_name)
            .listen("OperatorOrderCreated", payload => {
                if (payload.hasOwnProperty("order")) {
                    this.socketData.orderCreated = JSON.parse(JSON.stringify(payload.order));
                }
            })
            .listen("OperatorOrderUpdated", payload => {
                if (payload.hasOwnProperty("order")) {
                    this.socketData.orderUpdated = JSON.parse(JSON.stringify(payload.order));
                }
            })
            .listen("OperatorOrderAttached", payload => {
                if (payload.hasOwnProperty("order")) {
                    this.socketData.orderCreated = JSON.parse(JSON.stringify(payload.order));
                }
            })
            .listen("OperatorOrderCommon", payload => {
                if (payload.hasOwnProperty("common")) {
                    this.socketData.orderCommon = JSON.parse(JSON.stringify(payload.common));
                }
            })
            .listen("OperatorDriverUpdated", payload => {
                if (payload.hasOwnProperty("driver")) {
                    this.socketData.driverUpdated = JSON.parse(JSON.stringify(payload.driver));
                }
            })
            .listen("OperatorDriverActiveShipped", payload => {
                if (payload.hasOwnProperty("shipped")) {
                    this.socketData.driverShipped = JSON.parse(JSON.stringify(payload.shipped));
                }
            })
            .listen("OperatorOrderActiveShipped", payload => {
                if (payload.hasOwnProperty("shipped")) {
                    this.socketData.orderShipped = JSON.parse(JSON.stringify(payload.shipped));
                }
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
    },

    components: {
        AppBar,
        OrdersTable,
        OrdersMap,
        orderDialog: orderDialog,
        SipCall: SipCall,
        Snackbar: Snackbar,
        "calls-table": CallsTable,
        "boards-table": BoardTable,
    },
};
