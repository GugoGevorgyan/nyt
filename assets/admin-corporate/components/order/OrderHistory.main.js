/** @format */

import moment from "moment";
import { mapState } from "vuex";
import OrdersPagination from "../../forms/OrdersPagination";
import RoadMapComponent from "./RoadMapComponent";
import { DRIVER_STATUS, ORDER_STATUS } from "../../plugins/config";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "OrderHistory",

    components: { "road-map": RoadMapComponent },

    data() {
        return {
            ORDER_STATUS: ORDER_STATUS,
            DRIVER_STATUS: DRIVER_STATUS,
            itemsExcellButton: [
                { title: " Print ", icon: "mdi-cloud-print-outline" },
                { title: " Export ", icon: "mdi-file-excel-outline" },
            ],
            newOrders: [],
            cancelDialog: false,
            deletedOrderId: null,
            deletedClientId: null,
            terminatePassword: "",

            printData: {},
            printFields: {},
            roadDialoge: false,
            selectedRow: null,
            paginated: new OrdersPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    sort_by: this.$route.query["sort-by"],
                    sort_desc: this.setSortDesc(this.$route.query["sort-desc"]),
                    status: this.$route.query["status"],
                    type: this.$route.query["type"],
                    date_start: this.$route.query.date_start,
                    date_end: this.$route.query.date_end,
                },
                "/admin/corporate/order/paginate",
            ),
            window: {
                width: 0,
                height: window.innerHeight - 250,
            },
            datePicker: [this.$route.query.date_start, this.$route.query.date_end],
            pickerOptions: {
                shortcuts: [
                    {
                        text: "Last week",
                        onClick(picker) {
                            let start = new Date();
                            let end = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Last month",
                        onClick(picker) {
                            let start = new Date();
                            start.setMonth(start.getMonth() - 1, 1);
                            let end = new Date();
                            end.setMonth(end.getMonth(), 0);
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Current month",
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
        };
    },

    computed: {
        ...mapState(["company", "orderStatuses"]),

        orderTypes: {
            get() {
                return this.$store.state.orderTypes;
            },
        },

        broadcast: {
            get() {
                return this.$store.state.broadcast;
            },
            set(val) {
                this.$store.state.broadcast = val;
            },
        },

        lastMonthStart() {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth();

            return moment(new Date(year, month - 1)).format("YYYY-MM-DD");
        },

        lastMonthEnd() {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth();

            return moment(new Date(year, month, 0)).format("YYYY-MM-DD");
        },

        currentMonthStart() {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth();

            return moment(new Date(year, month)).format("YYYY-MM-DD");
        },

        today() {
            return moment(new Date()).format("YYYY-MM-DD");
        },

        nextMonth() {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth();

            return moment(new Date(year, month + 1)).format("YYYY-MM-DD");
        },

        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },

    watch: {
        "paginated.current_page": function (newVal, oldVal) {
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.per_page": function (newVal, oldVal) {
            if (Number(newVal) !== Number(oldVal)) {
                this.paginated.current_page = 1;
                this.setQuery();
            }
        },
        "paginated.sort_by": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.sort_desc": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.status": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.type": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },

        datePicker(val) {
            if (val) {
                this.paginated.date_start = moment(val[0]).format("YYYY-MM-DD");
                this.paginated.date_end = moment(val[1]).format("YYYY-MM-DD");
            } else {
                this.paginated.date_start = null;
                this.paginated.date_end = null;
            }

            this.setQuery();
        },
    },

    methods: {
        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 250;
        },

        closeDialogue() {
            this.roadDialoge = false;
        },

        printExcell(id, datas, name) {
            this.paginated
                .printExcellData(id, datas, name)
                .then(response => {
                    let datas = response.data._payload.filter((items, index) => 0 !== index);
                    let fields = response.data._payload[0];

                    let win = window.open("#", "_blank");
                    win.document.open();
                    win.document.write("<html>");
                    win.document.write("<body>");
                    win.document.write("<head>");
                    win.document.write("<style>");
                    win.document.write(
                        ".table {\n" +
                            "  width: 100%;\n" +
                            "  max-width: 100%;\n" +
                            "  margin-bottom: 1rem;\n" +
                            "  border-collapse: collapse;\n" +
                            "}\n" +
                            "\n" +
                            ".table th,\n" +
                            ".table td {\n" +
                            "  padding: 0.75rem;\n" +
                            "  vertical-align: top;\n" +
                            "  border-top: 1px solid #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table thead th {\n" +
                            "  vertical-align: bottom;\n" +
                            "  border-bottom: 2px solid #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table tbody + tbody {\n" +
                            "  border-top: 2px solid #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table .table {\n" +
                            "  background-color: #fff;\n" +
                            "}\n" +
                            "\n" +
                            ".table-sm th,\n" +
                            ".table-sm td {\n" +
                            "  padding: 0.3rem;\n" +
                            "}\n" +
                            "\n" +
                            ".table-bordered {\n" +
                            "  border: 1px solid #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table-bordered th,\n" +
                            ".table-bordered td {\n" +
                            "  border: 1px solid #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table-bordered thead th,\n" +
                            ".table-bordered thead td {\n" +
                            "  border-bottom-width: 2px;\n" +
                            "}\n" +
                            "\n" +
                            ".table-striped tbody tr:nth-of-type(odd) {\n" +
                            "  background-color: rgba(0, 0, 0, 0.05);\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover tbody tr:hover {\n" +
                            "  background-color: rgba(0, 0, 0, 0.075);\n" +
                            "}\n" +
                            "\n" +
                            ".table-active,\n" +
                            ".table-active > th,\n" +
                            ".table-active > td {\n" +
                            "  background-color: rgba(0, 0, 0, 0.075);\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-active:hover {\n" +
                            "  background-color: rgba(0, 0, 0, 0.075);\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-active:hover > td,\n" +
                            ".table-hover .table-active:hover > th {\n" +
                            "  background-color: rgba(0, 0, 0, 0.075);\n" +
                            "}\n" +
                            "\n" +
                            ".table-success,\n" +
                            ".table-success > th,\n" +
                            ".table-success > td {\n" +
                            "  background-color: #dff0d8;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-success:hover {\n" +
                            "  background-color: #d0e9c6;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-success:hover > td,\n" +
                            ".table-hover .table-success:hover > th {\n" +
                            "  background-color: #d0e9c6;\n" +
                            "}\n" +
                            "\n" +
                            ".table-info,\n" +
                            ".table-info > th,\n" +
                            ".table-info > td {\n" +
                            "  background-color: #d9edf7;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-info:hover {\n" +
                            "  background-color: #c4e3f3;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-info:hover > td,\n" +
                            ".table-hover .table-info:hover > th {\n" +
                            "  background-color: #c4e3f3;\n" +
                            "}\n" +
                            "\n" +
                            ".table-warning,\n" +
                            ".table-warning > th,\n" +
                            ".table-warning > td {\n" +
                            "  background-color: #fcf8e3;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-warning:hover {\n" +
                            "  background-color: #faf2cc;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-warning:hover > td,\n" +
                            ".table-hover .table-warning:hover > th {\n" +
                            "  background-color: #faf2cc;\n" +
                            "}\n" +
                            "\n" +
                            ".table-danger,\n" +
                            ".table-danger > th,\n" +
                            ".table-danger > td {\n" +
                            "  background-color: #f2dede;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-danger:hover {\n" +
                            "  background-color: #ebcccc;\n" +
                            "}\n" +
                            "\n" +
                            ".table-hover .table-danger:hover > td,\n" +
                            ".table-hover .table-danger:hover > th {\n" +
                            "  background-color: #ebcccc;\n" +
                            "}\n" +
                            "\n" +
                            ".thead-inverse th {\n" +
                            "  color: #fff;\n" +
                            "  background-color: #292b2c;\n" +
                            "}\n" +
                            "\n" +
                            ".thead-default th {\n" +
                            "  color: #464a4c;\n" +
                            "  background-color: #eceeef;\n" +
                            "}\n" +
                            "\n" +
                            ".table-inverse {\n" +
                            "  color: #fff;\n" +
                            "  background-color: #292b2c;\n" +
                            "}\n" +
                            "\n" +
                            ".table-inverse th,\n" +
                            ".table-inverse td,\n" +
                            ".table-inverse thead th {\n" +
                            "  border-color: #fff;\n" +
                            "}\n" +
                            "\n" +
                            ".table-inverse.table-bordered {\n" +
                            "  border: 0;\n" +
                            "}\n" +
                            "\n" +
                            ".table-responsive {\n" +
                            "  display: block;\n" +
                            "  width: 100%;\n" +
                            "  overflow-x: auto;\n" +
                            "  -ms-overflow-style: -ms-autohiding-scrollbar;\n" +
                            "}\n" +
                            "\n" +
                            ".table-responsive.table-bordered {\n" +
                            "  border: 0;\n" +
                            "}\n",
                    );
                    win.document.write("</style>");
                    win.document.write("</head>");
                    win.document.write('<div class="table-responsive">');

                    win.document.write('<table class="table table-bordered">');
                    win.document.write("<thead>");
                    win.document.write("<tr>");
                    for (let field of fields) {
                        win.document.write("<th>");
                        win.document.write(field);
                        win.document.write("</th>");
                    }
                    win.document.write("</tr>");
                    win.document.write("</thead>");

                    win.document.write("<tbody>");
                    for (let data of datas) {
                        win.document.write("<tr>");
                        for (let [index, items] of Object.entries(data)) {
                            win.document.write("<td>");
                            win.document.write(items);
                            win.document.write("</td>");
                        }
                        win.document.write("</tr>");
                    }
                    win.document.write("</tbody>");
                    win.document.write("</table>");
                    win.document.write("</div>");
                    win.document.write("<" + "/body" + "><" + "/html" + ">");
                    win.print();
                })
                .catch(error => {
                    console.log(error);
                });
        },

        setQuery() {
            this.$router.push(
                {
                    name: "",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        "sort-by": this.paginated.sort_by,
                        "sort-desc": this.paginated.sort_desc,
                        type: this.paginated.type,
                        status: this.paginated.status,
                        date_start: this.paginated.date_start,
                        date_end: this.paginated.date_end,
                    },
                },
                () => {
                    this.paginated.datas;
                },
            );
        },

        setSortDesc(sort) {
            return Array.isArray(sort) ? sort : Boolean(sort);
        },

        rowClasses(item) {
            let index = this.newOrders.findIndex(newItem => {
                return newItem.order.order_id === item.order.order_id;
            });

            return ~index ? "red lighten-5 pointer" : "pointer";
        },

        whereClauseCancel(orderId) {
            let index = this.paginated._payload.findIndex(item => {
                return item.order.order_id === orderId;
            });

            let order = this.paginated._payload[index];

            return (
                order.driver.current_status_id !== DRIVER_STATUS.IN_PLACE &&
                order.driver.current_status_id !== DRIVER_STATUS.IN_ORDER &&
                order.order.status_id !== ORDER_STATUS.CANCELED &&
                order.order.status_id !== ORDER_STATUS.COMPLETED
            );
        },

        cancelOrder(deletes = false, order_id = null, client_id = null) {
            if (deletes) {
                this.paginated
                    .cancelOrder(
                        order_id || this.deletedOrderId,
                        client_id || this.deletedClientId,
                        this.terminatePassword,
                    )
                    .then(resulr => {
                        this.cancelDialog = false;
                        this.terminatePassword = "";
                        this.deletedOrderId = null;
                        this.deletedClientId = null;
                    });
            } else {
                this.deletedOrderId = order_id;
                this.deletedClientId = client_id;
                this.cancelDialog = true;
            }
        },
    },

    mounted() {
        this.broadcast.listen("CreateOrder", payload => {
                new Audio("/storage/media/client_notify.mp3").play();
                this.newOrders.push(payload.order);

                if (1 === this.paginated.current_page) {
                    this.paginated._payload.unshift(payload.order);
                    if (this.paginated._payload.length > this.paginated.perPages) {
                        this.paginated._payload.pop();
                    }
                } else {
                    this.paginated.current_page = 1;
                }
            })
            .listen("CancelOrder", payload => {
                let index = this.paginated._payload.findIndex(item => {
                    return item.order.order_id === payload.order.order.order_id;
                });

                if (~index) {
                    this.paginated._payload.splice(index, 1, payload.order);
                }
            })
            .listen("CompletedOrder", payload => {
                let index = this.paginated._payload.findIndex(item => {
                    return item.order.order_id === payload.order.order.order_id;
                });

                if (~index) {
                    this.paginated._payload.splice(index, 1, payload.order);
                }
            });
    },

    created() {
        this.$store.dispatch("getCompanyPhoneMask").then();
        this.paginated.datas;
        window.addEventListener("resize", this.handleResize);
    },
};
