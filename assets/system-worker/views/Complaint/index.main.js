/** @format */

import ComplaintPagination from "../../forms/ComplaintPagination";
import Complaint from "../../components/Complaint/Complaint";

export default {
    components: { Complaint },

    props: {
        statuses: {
            required: true,
        },
    },

    data() {
        return {
            paginated: new ComplaintPagination({
                current_page: Number(this.$route.query["page"]),
                per_page: Number(this.$route.query["per-page"]),
                search: this.$route.query["search"],
                path: "complaint/paginate",
            }),

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 187,

            dialog: false,
            complaint: null,
        };
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
    },

    methods: {
        commaJoin(arr, keys) {
            if ("object" === typeof keys) {
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

        updateData() {
            this.$router.push(
                {
                    name: "show_complaint_index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        status: this.paginated.status,
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

        showComplaint(complaint) {
            this.complaint = complaint;
            this.dialog = true;
        },

        closeComplaint() {
            this.complaint = null;
            this.dialog = false;
        },
    },

    created() {
        this.paginated.getData;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
