/** @format */

import { mapState } from "vuex";
import moment from "moment-timezone";
import CallCenterOrderPagination from "../../../../forms/CallCenterOrderPagination";
import OrderInfoDialog from "../../../../components/CallCenter/OrderInfoDialog/OrderInfoDialog";
import { broadcasting } from "../../../../mixins/CallCenter";

export default {
    components: { OrderInfoDialog },

    props: {
        socketData: {
            required: true,
        },
        height: {
            required: true,
        },
        full: {
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
            paginated: new CallCenterOrderPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    status: Number(this.$route.query.status),
                    type: Number(this.$route.query.type),
                    search: this.$route.query.search,
                },
                "call-center/paginate",
            ),
            route: "call_center",

            newItems: [],

            toolbarHeight: 0,
            footerHeight: 0,

            interval: null,
            currentTime: null,

            infoOrderId: null,
            infoOrderDialog: false,

            fatPrice: 3000,
        };
    },

    mixins: [broadcasting],

    computed: {
        ...mapState(["auth"]),

        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },

        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 40;
        },

        infoOrder() {
            return this.infoOrderId ? this.paginated.data.find(item => this.infoOrderId === item.order_id) : null;
        },
    },

    watch: {
        "socketData.orderCreated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__addToOrderList(
                        this.paginated.data,
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
                this.paginated.data = this.__updateOrderList(this.paginated.data, this.socketData.orderUpdated);
            },
        },
        "socketData.orderCommon": {
            deep: true,
            handler() {
                this.paginated.data = this.__updateOrderCommon(this.paginated.data, this.socketData.orderCommon);
                this.newItems = this.__updateOrderCommon(this.newItems, this.socketData.orderCommon);
            },
        },
        "socketData.orderShipped": {
            deep: true,
            handler() {
                this.paginated.data = this.__updateOrderShipped(this.paginated.data, this.socketData.orderShipped);
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
    },

    mounted() {
        this.toolbarHeight = this.$refs.toolbar.clientHeight;
    },

    filters: {
        leftTime: function (created, now) {
            let createDate = new Date(created);
            let sec_num = Math.floor((now - createDate.getTime()) / 1000);
            let minutes = Math.floor(sec_num / 60);
            let seconds = sec_num - minutes * 60;

            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return minutes + ":" + seconds;
        },
    },

    methods: {
        /*new items*/
        rowClasses(item) {
            let index = this.newItems.findIndex(newItem => {
                return newItem.order_id === item.order_id;
            });
            return ~index ? "red lighten-4 pointer" : "pointer";
        },
        dischargeNewItems() {
            this.newItems = [];
        },

        /*table*/
        showInfo(order) {
            this.infoOrderId = order.order_id;
            this.infoOrderDialog = true;
        },
        closeInfo() {
            this.infoOrderId = null;
            this.infoOrderDialog = false;
        },
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
                    },
                },
                () => {
                    this.paginated.getOrders;
                },
            );
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
            return this.isToday(itemDate) ? moment(itemDate).format("HH:mm") : moment(itemDate).format("YYYY-MM-DD");
        },

        /*preorder*/
        getPreorderDate(preorder) {
            let timeOrder = moment().format("DD-MM-YYYY HH:mm:ss");
            let timeLocal = moment().format("DD-MM-YYYY HH:mm:ss");

            let dt1 = new Date(timeOrder);
            let dt2 = new Date(timeLocal);

            let minutesDiff = (dt1.getTime() - dt2.getTime()) / 60000;

            let localStartTme = moment(new Date(preorder.distribution_start))
                .add(minutesDiff, "m")
                .format("YYYY-MM-DD HH:mm:ss");

            return {
                started: new Date() > Date.parse(localStartTme),
                time: localStartTme,
            };
        },

        /*pending time*/
        setCurrentTime() {
            let nowDate = new Date();
            this.currentTime = nowDate.getTime();
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
