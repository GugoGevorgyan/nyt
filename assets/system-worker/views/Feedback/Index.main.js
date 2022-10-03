/** @format */

import FeedbackPagination from "../../forms/FeedbackPagination";
import moment from "moment-timezone";

export default {
    props: {
        orderStatuses: {
            required: true,
        },
        types: {
            required: true,
        },
        writers: {
            required: true,
        },
    },

    data() {
        return {
            paginated: new FeedbackPagination({
                current_page: Number(this.$route.query["page"]),
                per_page: this.$route.query["per-page"],
                search: this.$route.query["search"],
                status: this.$route.query.status,
                type:
                    this.$route.query.type && Array.isArray(this.$route.query.type)
                        ? this.$route.query.type
                        : [this.$route.query.type],
                writer:
                    this.$route.query.writer && Array.isArray(this.$route.query.writer)
                        ? this.$route.query.writer
                        : [this.$route.query.writer],
                path: "feedbacks/paginate",
            }),

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 187,

            searchText: null,
        };
    },

    filters: {
        formatTime: function (value) {
            let date = new Date(value);
            return `${date.getHours()}:${date.getMinutes() <= 9 ? "0" + date.getMinutes() : date.getMinutes()} `;
        },

        formatDate: function (date) {
            let itemDate = new Date(date);
            let today = new Date();
            let isToday =
                today.getDate() === itemDate.getDate() &&
                today.getMonth() === itemDate.getMonth() &&
                today.getFullYear() === itemDate.getFullYear();
            return isToday ? "Сегодня" : moment(itemDate).format("DD MMM YYYY");
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
    },

    watch: {
        "paginated.current_page": function () {
            this.updateData();
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.updateData();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.updateData();
        },
        "paginated.status": function () {
            this.paginated.current_page = 1;
            this.updateData();
        },
        "paginated.type": function () {
            this.paginated.current_page = 1;
            this.updateData();
        },
        "paginated.writer": function () {
            this.paginated.current_page = 1;
            this.updateData();
        },

        searchText() {
            if (!this.searchText) {
                this.search();
            }
        },
    },

    methods: {
        search() {
            this.paginated.search === this.searchText
                ? this.paginated.getData
                : (this.paginated.search = this.searchText);
        },
        commaJoin(arr, keys) {
            if (typeof keys === "object") {
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

        assessmentColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },
        updateData() {
            this.$router.push(
                {
                    name: "get_feedbacks_index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        type: this.paginated.type,
                        status: this.paginated.status,
                        writer: this.paginated.writer,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },
    },

    created() {
        this.paginated.getData;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
