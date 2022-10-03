/** @format */

import AllTransaction from "../../../forms/Bookkeeping/AllTransaction";
import Details from "../details/details";
import Transaction from "../transaction/transaction";
import { mapState } from "vuex";
import moment from "moment";
import { TRANSACTION } from "../../../plugins/config";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "bookkeeping",

    components: {
        "bookkeeping-details": Details,
        transaction: Transaction,
    },

    props: {
        driverTypes: {
            required: true,
            type: Array,
        },
        companies: {
            required: true,
            type: Array,
        },
        paymentTypes: {
            required: true,
            type: Array,
        },
        drivers: {
            required: true,
            type: Array,
        },
        parks: {
            required: true,
            type: Array,
        },
        transactionTypes: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            paginated: new AllTransaction({
                current_page: Number(this.$route.query["page"]),
                per_page: this.$route.query["per_page"],
                search: this.$route.query["search"],
                payment_types: Number(this.$route.query["payment_types"]),
                driver: Number(this.$route.query["driver"]),
            }),
            window: {
                width: 0,
                height: 0,
                heightDif: 180,
            },
            TRANSACTION: TRANSACTION,
            detailDialog: false,
            transactionDialog: false,
            selectedId: {},
            datePicker: [this.$route.query.date_start, this.$route.query.date_end],
            rowColor: "",
            newTransaction: [],
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
        };
    },

    watch: {
        "paginated.current_page": function () {
            this.setQuery();
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            setTimeout(() => {
                this.setQuery();
            }, 600);
        },
        "paginated.sort_by": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.sort_desc": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.driver": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.payment_types": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.payed": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.transaction_types": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.parks": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        datePicker: function (val) {
            this.paginated.date_start = moment(val[0]).format("DD-MM-YYYY");
            this.paginated.date_end = moment(val[1]).format("DD-MM-YYYY");
            this.paginated.current_page = 1;
            this.setQuery();
        },
    },

    computed: {
        ...mapState(["auth"]),
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        setQuery() {
            this.$router.push(
                {
                    name: "bookkeeping_all_index",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                        payment_types: this.paginated.payment_types,
                        transaction_types: this.paginated.transaction_types,
                        parks: this.paginated.parks,
                        driver: this.paginated.driver,
                        sort_desc: this.paginated.sort_desc,
                        sort_by: this.paginated.sort_by,
                        payed: this.paginated.payed,
                        date_start: this.paginated.date_start,
                        date_end: this.paginated.date_end,
                    },
                },
                () => {
                    this.paginated.items;
                },
            );
        },

        rowClasses(item) {
            let index = this.newTransaction.findIndex(newItem => {
                return newItem.id === item.id;
            });
            return ~index ? this.rowColor : "";
        },

        selectRow(item) {
            this.selectedId = item.id;
            this.detailDialog = true;
        },

        printTransaction() {
            this.paginated.loading = true;

            if (this.paginated.driver) {
                this.$http
                    .post(
                        `all/print_transactions`,
                        {
                            side: this.paginated.driver,
                            date_start: this.paginated.date_start,
                            date_end: this.paginated.date_end,
                        },
                        {
                            responseType: "blob",
                            headers: {
                                Accept: "application/vnd.ms-excel",
                            },
                        },
                    )
                    .then(response => {
                        const blob = new Blob([response.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                        });
                        const link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        link.setAttribute("download", "_bookkeeping.xlsx");
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        this.paginated.loading = false;
                    })
                    .cancel(error => {
                        this.paginated.loading = false;
                    });
            }
        },

        createTransaction(data) {
            new Audio("/storage/media/client_notify.mp3").play();
            this.newTransaction.push(data.payload);
            this.rowColor = "green lighten-5 pointer";

            if (1 === this.paginated.current_page) {
                this.paginated._payload.unshift(data.payload);
                if (this.paginated._payload.length > this.paginated.perPages) {
                    this.paginated._payload.pop();
                }
            } else {
                this.paginated.current_page = 1;
            }
        },
    },

    mounted() {
        let c_name = `${process.env.MIX_CHANNEL_WORKER_WEB}-worker.${this.auth.user.system_worker_id}.${this.auth.user.franchise_id}`;

        Echo.join(c_name).listen("BookkeepingCreateTransaction", data => {
            this.createTransaction(data);
        });
    },

    created() {
        this.paginated.items;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
