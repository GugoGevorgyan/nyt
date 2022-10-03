/** @format */

import moment from "moment";
import { ORDER_STATUS } from "../../../../plugins/config";
import { broadcasting } from "../../../../mixins/CallCenter";
import Snackbar from "../../../../facades/Snackbar";
import CallCenterOrderDispatcherPagination from "../../../../forms/CallCenterOrderDispatcherPagination";
import OrderInfoDialog from "../../../../components/CallCenter/OrderInfoDialog/OrderInfoDialog";
import OrderActionsDialog from "../../../../components/CallCenter/OrderInfoDialog/Actions/OrderActions";

export default {
    components: { "order-info-dialog": OrderInfoDialog, "order-actions-dialog": OrderActionsDialog },

    props: {
        socketData: {
            required: true,
        },
        height: {
            required: true,
        },
        statuses: {
            required: true,
            type: Array,
        },
        types: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            paginated: new CallCenterOrderDispatcherPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    search: this.$route.query["search"],
                    type: Number(this.$route.query["type"]),
                    status: Number(this.$route.query["status"]),
                    date_start: Number(this.$route.query["date_start"]),
                    date_end: Number(this.$route.query["date_end"]),
                },
                "call-center-dispatcher/paginate",
            ),

            datePicker: [this.$route.query.date_start, this.$route.query.date_end],
            newItems: [],
            operators: [],
            operatorsLoading: false,
            operatorAttachLoading: false,
            toolbarHeight: 0,
            footerHeight: 0,
            interval: null,
            currentTime: null,
            route: "call_center_dispatcher",
            addOrders: true,
            infoOrderId: null,
            actionOrderId: null,
            infoOrderDialog: false,
            actionsOrderDialog: false,
            fatPrice: 3000,
            rowColor: "",
            message: "",
            eventData: {},
            showMenu: false,
            menuX: 0,
            menuY: 0,
            orderStatus: ORDER_STATUS,
            cancelDialog: false,
            cancelLoading: false,
        };
    },

    mixins: [broadcasting],

    filters: {
        leftTime: function (created, now) {
            let createDate = new Date(created);
            let secNum = Math.floor((now - createDate.getTime()) / 1000);
            let minutes = Math.floor(secNum / 60);
            let seconds = secNum - minutes * 60;

            if (10 > minutes) {
                minutes = "0" + minutes;
            }
            if (10 > seconds) {
                seconds = "0" + seconds;
            }
            return minutes + ":" + seconds;
        },
    },

    watch: {
        "socketData.orderCreated": {
            deep: true,
            handler() {
                this.rowColor = "green lighten-4 pointer";
                if (1 === this.paginated.current_page && this.addOrders) {
                    this.paginated._payload = this.__addToOrderList(
                        this.paginated._payload,
                        this.socketData.orderCreated,
                        this.paginated.per_page,
                    );
                }
                this.newItems.push(JSON.parse(JSON.stringify(this.socketData.orderCreated)));
            },
        },
        "socketData.orderUpdated": {
            deep: true,
            handler() {
                this.paginated._payload = this.__updateOrderList(this.paginated._payload, this.socketData.orderUpdated);
            },
        },
        "socketData.orderCommon": {
            deep: true,
            handler() {
                this.rowColor = "red lighten-5 pointer";
                this.paginated._payload = this.__updateOrderCommon(
                    this.paginated._payload,
                    this.socketData.orderCommon,
                );
                this.newItems = this.__updateOrderCommon(this.newItems, this.socketData.orderCommon);
            },
        },
        "socketData.orderShipped": {
            deep: true,
            handler() {
                this.paginated._payload = this.__updateOrderShipped(
                    this.paginated._payload,
                    this.socketData.orderShipped,
                );
                this.newItems = this.__updateOrderShipped(this.newItems, this.socketData.orderShipped);
            },
        },

        "paginated.current_page": function () {
            this.setQuery();
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.status": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.type": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.selected": function (order) {
            if (
                order.length &&
                order[0].status_id !== ORDER_STATUS.CANCELED &&
                order[0].status_id !== ORDER_STATUS.COMPLETED
            ) {
                if (order[0].preorder && 30 < moment(order[0].preorder.time).diff(moment(), "minutes")) {
                    this.eventData = {
                        type: "preorder",
                        actions: { driver: true, time: true },
                        payload: {
                            driver: order[0].driver,
                            order_id: order[0].order_id,
                            preorder_time: order[0].preorder.time,
                        },
                    };
                } else {
                    this.eventData = { type: "regular" };
                }
            } else {
                this.eventData = {};
            }
        },
        datePicker: function () {
            let format = "YYYY-MM-DD";
            this.paginated.date_start = this.datePicker ? moment(this.datePicker[0]).format(format) : undefined;
            this.paginated.date_end = this.datePicker ? moment(this.datePicker[1]).format(format) : undefined;
            this.paginated.current_page = 1;
            this.setQuery();
        },

        addOrders: function () {
            if (this.addOrders) {
                if (1 === this.paginated.current_page) {
                    this.newItems.forEach(item => {
                        this.__addToOrderList(this.paginated._payload, item, this.paginated.per_page);
                    });
                } else {
                    this.paginated.current_page = 1;
                }
            }
        },
    },

    computed: {
        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 32;
        },
        infoOrder() {
            return this.infoOrderId ? this.paginated._payload.find(item => this.infoOrderId === item.order_id) : null;
        },
        actionOrder() {
            return this.actionOrderId
                ? this.paginated._payload.find(item => this.actionOrderId === item.order_id)
                : null;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },
        inProcess() {
            if (this.actionOrder) {
                return !this.actionOrder.completed && !this.actionOrder.canceled;
            }
        },
    },

    mounted() {
        if (this.$refs.toolbar) {
            this.toolbarHeight = this.$refs.toolbar.clientHeight || 0;
        }
    },

    methods: {
        /*new items*/
        rowClasses(item) {
            let index = this.newItems.findIndex(newItem => {
                return newItem.order_id === item.order_id;
            });
            return ~index ? this.rowColor : "pointer";
        },

        /*orders*/
        dischargeNewItems() {
            this.newItems = [];
        },

        /*data table*/
        setQuery() {
            this.$router.push(
                {
                    name: this.route,
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        status: this.paginated.status,
                        type: this.paginated.type,
                        date_start: this.paginated.date_start,
                        date_end: this.paginated.date_end,
                    },
                },
                () => {
                    this.paginated.getOrders;
                },
            );
        },
        dblClickRow(event, item) {
            let order = item.item;
            this.openHistoryDialog(order.order_id);
        },
        rightClickRow(event, item) {
            event.preventDefault();
            this.openContextMenu(event, item.item.order_id);
        },
        openHistoryDialog(order_id) {
            this.infoOrderId = order_id;
            this.infoOrderDialog = true;
        },
        openContextMenu(event, order_id) {
            this.actionOrderId = order_id;
            this.menuX = event.clientX;
            this.menuY = event.clientY;
            this.$nextTick(() => (this.showMenu = true));
        },
        closeInfo() {
            this.infoOrderId = null;
            this.infoOrderDialog = false;
        },
        closeAction() {
            this.actionOrderId = null;
            this.actionsOrderDialog = false;
        },
        openMenuDialog(type) {
            switch (type) {
                case "details":
                    this.infoOrderId = this.actionOrderId;
                    this.infoOrderDialog = true;
                    break;
                case "actions":
                    this.actionsOrderDialog = true;
                    break;
            }
        },
        commaJoin(arr, key) {
            let values = [];
            arr.forEach(item => {
                values.push(item[key]);
            });
            return values.join(", ");
        },
        customer(value) {
            if (value.customer.system_worker_id) {
                return `<div class="small mb-1">
                            <strong>Работник кол-центра</strong>
                        </div>
                        <div>${value.customer.surname} ${value.customer.name} ${value.customer.patronymic}</div>`;
            } else if (value.customer.admin_corporate_id) {
                return `<div class="small mb-1">
                            <strong>Корпоративный админстратор</strong>
                        </div>
                        <div>${value.customer.surname} ${value.customer.name} ${value.customer.patronymic}</div>
                        <div class="small mb-1">
                            <strong>Компания</strong>
                        </div>
                        <div>${value.corporate.company.name}</div>`;
            } else if (value.customer.client_id) {
                let name =
                    value.customer.surname || value.customer.name || value.customer.patronymic
                        ? `${value.customer.surname} ${value.customer.name} ${value.customer.patronymic}`
                        : value.customer.phone;
                return `<div class="small mb-1">
                            <strong>Клинет</strong>
                        </div>
                        <div>${name}</div>`;
            }
        },
        /*created data*/
        isToday(someDate) {
            let today = new Date();
            return (
                someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear()
            );
        },
        getDate(date) {
            return date ? moment(new Date(date)).format("YYYY-MM-DD HH:mm:ss") : null;
        },
        getDateShorted(date) {
            let itemDate = new Date(date);
            return this.isToday(itemDate)
                ? "Сегодня: " + moment(itemDate).format("HH:mm")
                : moment(itemDate).format("YYYY-MM-DD");
        },

        /*preorder*/
        getPreorderDate(preorder) {
            let timeOrder = moment(preorder.time).format("DD-MM-YYYY HH:mm");
            let timeLocal = moment().format("DD-MM-YYYY HH:mm");

            let dt1 = new Date(timeOrder);
            let dt2 = new Date(timeLocal);

            let minutesDiff = (dt1.getTime() - dt2.getTime()) / 60000;

            let localStartTme = moment(new Date(preorder.distribution_start))
                .add(minutesDiff, "m")
                .format("YYYY-MM-DD HH:mm");

            return {
                started: new Date() > Date.parse(localStartTme),
                time: timeOrder,
            };
        },
        /*data*/
        getOperators() {
            this.operatorsLoading = true;
            this.$http
                .get("call-center-dispatcher/get-operators")
                .then(response => {
                    this.operatorsLoading = false;
                    this.operators = response.data;
                })
                .catch(error => {
                    this.operatorsLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        /*action*/
        changeOperator(order_id, operator_id) {
            this.operatorAttachLoading = order_id;
            this.$http
                .post("call-center-dispatcher/operator-attach-order", { order_id: order_id, operator_id: operator_id })
                .then(response => {
                    this.operatorAttachLoading = false;
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.operatorAttachLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        /*pending time*/
        setCurrentTime() {
            let nowDate = new Date();
            this.currentTime = nowDate.getTime();
        },
        sendMessage(order) {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.$http
                        .post(`call-center-dispatcher/send/message`, {
                            phone: order.client.phone,
                            text: this.message,
                        })
                        .then(response => {
                            this.message = "";
                            this.$validator.reset();
                            new Snackbar.info(response.data.message);
                        });
                }
            });
        },
        cancelOrder() {
            this.cancelLoading = true;
            this.$http
                .put(`call-center/order-cancel/${this.actionOrder.order_id}`)
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
    },

    created() {
        this.interval = setInterval(
            function () {
                this.setCurrentTime();
            }.bind(this),
            1000,
        );
        this.paginated.getOrders;
    },
};
