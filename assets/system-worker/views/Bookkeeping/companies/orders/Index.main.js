/** @format */
import CompanyOrders from "../../../../forms/Bookkeeping/CompanyOrders";
import moment from "moment";

export default {
    name: "BookkeepingCompanyOrders",

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
        let paginated = new CompanyOrders({
            current_page: Number(this.$route.query["page"]),
            per_page: this.$route.query["per_page"],
            search: this.$route.query["search"],
            company: Number(this.$route.query["company"]),
        });

        return {
            paginated: paginated,
            window: {
                width: 0,
                height: 0,
                heightDif: 220,
            },
            datePicker: [this.$route.query.date_start || paginated.date_start, this.$route.query.date_end || paginated.date_end],
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

    computed: {
        total_amount() {
            return this.paginated._payload.map(order => {
                return order.total_with_VAT;
            }).reduce((a, b) => a + b, 0);
        }
    },

    watch: {
        "paginated.company": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        datePicker: function (val) {
            this.paginated.date_start = moment(val[0]).format("YYYY-MM-DD");
            this.paginated.date_end = moment(val[1]).format("YYYY-MM-DD");
            this.paginated.current_page = 1;
            this.setQuery();
        }
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        setQuery() {
            this.$router.push(
                {
                    name: "bookkeeping_company_orders_index",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        company: this.paginated.company,
                        sort_desc: this.paginated.sort_desc,
                        sort_by: this.paginated.sort_by,
                        date_start: this.paginated.date_start,
                        date_end: this.paginated.date_end
                    },
                },
                () => {
                    if(this.paginated.company) {
                        this.paginated.items();
                    }
                },
            );
        },

        download() {
            this.paginated.download();
        }
    },

    created() {
        this.handleResize();
        window.addEventListener("resize", this.handleResize);

        if(this.paginated.company) {
            this.paginated.items();
        }
    },
};
