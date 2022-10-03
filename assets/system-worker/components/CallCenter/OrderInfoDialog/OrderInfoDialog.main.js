/** @format */

import OrderInfo from "./Info/OrderInfo";
import OrderHistory from "./History/OrderHistory";
import Snackbar from "../../../facades/Snackbar";
import OrderInfoMap from "./Map/OrderInfoMap";
import ColorRound from "./../ColorRound";
import OrderInfoComments from "./Comments/OrderInfoComments";
import OrderFeedbackDialog from "./../OrderFeedbackDialog";
import OrderInfoDetail from "./Details/OrderInfoDetail";
import axios from 'axios';
export default {
    name: "OrderInfoDialog",

    components: {
        OrderFeedbackDialog,
        OrderInfoComments,
        ColorRound,
        OrderInfoMap,
        OrderHistory,
        OrderInfo,
        OrderInfoDetail,
    },

    props: {
        height: {
            required: true,
        },
        order: {
            required: true,
        },
    },

    data() {
        return {
            history: {
                order: [],
                attach: [],
                shipments: [],
                shipped: [],
                stages: [],
                completed: [],
                feedbacks: [],
                complaints: [],
                comments: [],
                way_road: [],
                process_road: [],
                tariff: [],
                corporate: [],
            },

            tab: 0,
            loading: false,
            center: null,
            orderProgress: null,
            slip: null,

            slipLoading: false,
            cancelDialog: false,
            cancelLoading: false,
            feedbackDialog: false,
            message: ''
        };
    },

    computed: {
        title() {
            if (this.order) {
                let from_address = this.order.address_from.split(", ").slice(1).join(", ");
                let text = `Заказ номер ${this.order.order_id}: из ${from_address}`;

                if (this.order.address_to) {
                    let to_address = this.order.address_to.split(", ").slice(1).join(", ");
                    text += ` до ${to_address}`;
                }
                return text;
            }
        },
        contentHeight() {
            return this.height - 84;
        },
        inProcess() {
            if (this.order) {
                return !this.order.completed && !this.order.canceled;
            }
        },
        board() {
            if (this.history.completed) {
                return {
                    car: this.history.completed.car,
                    driver: this.history.completed.driver,
                    driver_info: this.history.shipped.driver_info,
                };
            } else if (
                this.history.shipped &&
                2 === (this.history.shipped.status.status || this.history.shipped.status)
            ) {
                return {
                    car: this.history.shipped.driver.car,
                    driver: this.history.shipped.driver,
                    driver_info: this.history.shipped.driver_info,
                };
            }
        },
        slipChange() {
            if (this.order.corporate) {
                return this.slip !== this.order.corporate.slip_number;
            }
        },
        clientFeedBack() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.find(item => item.writable.client_id);
            }
        },
        driverFeedBack() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.find(item => item.writable.driver_id);
            }
        },
        workerFeedBacks() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.filter(item => item.writable.system_worker_id);
            }
        },
    },

    watch: {
        order() {
            this.setSlip();

            if (this.order) {
                this.getHistory();
            } else {
                this.orderProgress = null;
                this.tab = 0;
                this.history = {
                    attaches: [],
                    shipped: [],
                    currentShipped: [],
                    stages: [],
                    completed: [],
                    feedbacks: [],
                    complaints: [],
                    comments: [],
                };
            }
        },
        "history.completed": function () {
            this.setSlip();
        },
        "history.corporate": function () {
            this.slip =
                this.history.corporate && "undefined" !== this.history.corporate.slip_number
                    ? this.history.corporate.slip_number
                    : "";
        },
        board: function () {
            if (!this.board) {
                this.feedbackDialog = false;
            }
        },
    },

    methods: {
        assessmentColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },

        getHistory() {
            this.loading = true;
            this.$http
                .get(`call-center/order-history/${this.order.order_id}`)
                .then(response => {
                    this.history = response.data._payload;
                    this.loading = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },

        setSlip() {
            this.slip =
                this.order && this.order.corporate && this.order.completed ? this.order.corporate.slip_number : null;
        },

        updateSlip() {
            this.slipLoading = true;
            let data = {
                order_id: this.order.order_id,
                slip_number: this.slip,
            };
            this.$http
                .post("call-center/slip-update", data)
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.slipLoading = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.slipLoading = false;
                });
        },

        cancelOrder() {
            this.cancelLoading = true;
            this.$http
                .put(`call-center/order-cancel/${this.order.order_id}`)
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.cancelLoading = false;
                    this.cancelDialog = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.cancelLoading = false;
                });
        },
        sendMessage(order) {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    axios.post(`call-center-dispatcher/send/message`,
                        {
                            phone: order.client.phone,
                            text: this.message,
                        },
                    ).then((response)=> {
                        this.message = ''
                        this.$validator.reset();
                        new Snackbar.info(response.data.message);
                    })
                }
            });
        }
    },

    created() {
        this.setSlip();
        this.getHistory();
    },
};
