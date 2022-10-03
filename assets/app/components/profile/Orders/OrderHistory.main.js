/** @format */

import axios from "axios";
import moment from "moment";
import { mapState } from "vuex";
import OrderInfo from "../OrderInfo";

export default {
    name: "OrderHistory",

    data() {
        return {
            selected: [],
            perPages: ["10", "25", "50", "100"],
            loading: false,
            headers: [
                { text: "Дата заказа", value: "order_time", sortable: false },
                { text: "Время начала поездки", value: "order_start", sortable: false },
                { text: "Время окончания поездки", value: "order_end", sortable: false },
                { text: "Компания", value: "company.name", sortable: false },
                { text: "Пассажир", value: "passenger", sortable: false },
                { text: "Откуда", value: "address_from", sortable: false },
                { text: "Куда", value: "address_to", sortable: false },
                { text: "Способ оплаты", value: "payment_type.name", sortable: false },
                { text: "Цена", value: "price", sortable: false },
                { text: "Статус", value: "status.text", sortable: false },
            ],
            orders: [],
            pickerOptions: {
                shortcuts: [
                    {
                        text: "Последняя неделя",
                        onClick(picker) {
                            let start = new Date();
                            let end = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Последний месяц",
                        onClick(picker) {
                            let start = new Date();
                            start.setMonth(start.getMonth() - 1, 1);
                            let end = new Date();
                            end.setMonth(end.getMonth(), 0);
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Текущий месяц",
                        onClick(picker) {
                            let start = new Date();
                            start.setDate(1);
                            let end = new Date();
                            end.setMonth(end.getMonth() + 1, 0);
                            picker.$emit("pick", [start, end]);
                        },
                    },
                ],
            },
            filterSort: {
                type: +this.$route.query.type,
                status: +this.$route.query.status,
                sortBy: null,
                sortDesc: null,
            },
            orderInfo: false,
            orderData: [],
            window: {
                width: 0,
                height: window.innerHeight - 323,
            },
            client_created_at: "",
            date_start: this.$route.query["date_start"],
            date_end: this.$route.query["date_end"],
            current_page: Number(this.$route.query["page"]) || 1,
            per_page: this.$route.query["per_page"] || 10,
            datePicker: [this.date_start, this.date_end],
        };
    },

    components: { "order-info": OrderInfo },

    computed: {
        ...mapState(["orderTypes", "orderStatuses"]),

        currentMonthStart() {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth();

            return moment(new Date(year, month)).format("YYYY-MM-DD");
        },

        today() {
            return moment(new Date()).format("YYYY-MM-DD");
        },
    },

    methods: {
        setQuery() {
            this.$router.push(
                {
                    query: {
                        page: this.current_page,
                        per_page: this.per_page,
                        date_start: this.date_start,
                        date_end: this.date_end,
                    },
                },
                () => {
                    this.initialize();
                },
            );
        },

        async initialize() {
            await axios(`/profile/client/orders`)
                .then(res => {
                    // this.client_created_at = res.data;
                    this.orders = res.data;
                })
                .catch(() => {});

            if (!this.datePicker.length || this.date_start === "undefined") {
                // this.datePicker.splice(0, 1, this.client_created_at);
                // this.datePicker.splice(1, 1, this.today);
            } else {
                // this.datePicker.splice(0, 1, this.date_start);
                // this.datePicker.splice(1, 1, this.date_end);
            }

            this.sendFilterSortReq();
        },

        sendFilterSortReq() {
            if (this.date_start !== "" && this.date_start !== undefined) {
                this.loading = true;
                let query = this.$router.resolve({ params: {}, query: this.$route.query }).href;
                query = query.slice(15);
                axios
                    .get(`/profile/client/orders${query}`)
                    .then(res => {
                        this.orders = res.data;
                        this.current_page = res.data.current_page;
                        this.per_page = res.data.per_page;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                    });
            }
        },

        openInfoDialog(event, item) {
            this.orderInfo = true;
            this.orderData = item.item.in_process_road;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 400;
        },
    },

    watch: {
        ["filterSort.type"](type) {
            this.$router.push({ query: Object.assign({}, this.$route.query, { type }) }).catch(() => {});
            this.sendFilterSortReq();
        },

        ["filterSort.status"](status) {
            this.$router.push({ query: Object.assign({}, this.$route.query, { status }) });
            this.sendFilterSortReq();
        },

        current_page: function () {
            this.setQuery();
        },

        per_page: function (newVal, oldVal) {
            if (Number(newVal) !== oldVal) {
                this.per_page = newVal;
                this.current_page = 1;
                this.setQuery();
            }
        },

        datePicker(val) {
            let date_start, date_end;
            if (val) {
                if (val[0] > new Date()) val[0] = new Date();
                date_start = moment(val[0]).format("YYYY-MM-DD");
                if (val[1] > new Date()) val[1] = new Date();
                date_end = moment(val[1]).format("YYYY-MM-DD");
            }
            this.date_start = date_start;
            this.date_end = date_end;
            this.setQuery();
        },
    },

    created() {
        this.initialize();
        window.addEventListener("resize", this.handleResize);
    },
};
