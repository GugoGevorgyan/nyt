/** @format */
import ClientPagination from "../../forms/ClientPagination";

export default {
    name: "ClientsIndex",

    data() {
        return {
            paginated: new ClientPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per_page"]),
                    search: this.$route.query["search"],
                    active: this.$route.query["active"],
                },
                "clients/pager",
            ),
            notifyDialog: false,
            window: {
                width: 0,
                height: 0,
                heightDif: 187,
            },
        };
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        sendNotification() {
            this.notifyDialog = true;
        },
    },

    created() {
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
        this.paginated.clients;
    },
};
