/** @format */

import DispatcherCallsPagination from "../../../../forms/DispatcherCallsPagination";
import { broadcasting } from "../../../../mixins/CallCenter/Broadcast";

export default {
    props: {
        socketData: {
            required: true,
        },
        height: {
            required: true,
        },
    },
    data() {
        return {
            paginated: new DispatcherCallsPagination({}, "call-center-dispatcher/calls"),
            toolbarHeight: 0,
            footerHeight: 0,
        };
    },
    mixins: [broadcasting],
    watch: {
        "socketData.callCreated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__addToList(
                        this.paginated.data,
                        this.socketData.callCreated,
                        "client_call_id",
                        this.paginated.per_page,
                    );
                }
            },
        },
        "socketData.callUpdated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__updateList(
                        this.paginated.data,
                        this.socketData.callUpdated,
                        "client_call_id",
                    );
                }
            },
        },

        "paginated.current_page": function () {
            this.paginated.getCalls;
        },
        "paginated.per_page": function () {
            this.paginated.getCalls;
        },
        "paginated.search": function () {
            this.paginated.getCalls;
        },
    },
    computed: {
        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 18;
        },
    },
    mounted() {
        this.toolbarHeight = this.$refs.toolbar.clientHeight;
        this.footerHeight = this.$refs.footer.clientHeight;
    },
    methods: {
        clientTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic
                ? `${initials.join(" ").trim()}: ${client.phone}`
                : client.phone;
        },
    },
    created() {
        this.paginated.getCalls;
    },
};
