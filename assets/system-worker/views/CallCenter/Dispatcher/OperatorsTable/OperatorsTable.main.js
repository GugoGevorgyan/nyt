/** @format */

import Pagination from "../../../../forms/DispatcherOperatorsPagination";
import { mapState } from "vuex";
import { broadcasting } from "../../../../mixins/CallCenter";

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
            paginated: new Pagination({}, "call-center-dispatcher/operators"),
            toolbarHeight: 0,
            footerHeight: 0,
        };
    },
    mixins: [broadcasting],
    watch: {
        "socketData.operatorCreated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__addToList(
                        this.paginated.data,
                        this.socketData.operatorCreated,
                        "system_worker_id",
                        this.paginated.per_page,
                    );
                }
            },
        },
        "socketData.operatorUpdated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__updateList(
                        this.paginated.data,
                        this.socketData.operatorUpdated,
                        "system_worker_id",
                    );
                }
            },
        },

        "paginated.current_page": function () {
            this.paginated.getOperators;
        },
        "paginated.per_page": function () {
            this.paginated.getOperators;
        },
        "paginated.search": function () {
            this.paginated.getOperators;
        },
    },
    computed: {
        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 18;
        },
    },
    methods: {},
    mounted() {
        this.toolbarHeight = this.$refs.toolbar.clientHeight;
        this.footerHeight = this.$refs.footer.clientHeight;
    },
    created() {
        this.paginated.getOperators;
    },
};
