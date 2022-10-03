/** @format */

import moment from "moment";
import DispatcherBoardsPagination from "../../../../forms/DispatcherBoardsPagination";
import ColorRound from "../../../../components/CallCenter/ColorRound";
import { broadcasting } from "../../../../mixins/CallCenter";

export default {
    components: { "color-round": ColorRound },

    mixins: [broadcasting],

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
            paginated: new DispatcherBoardsPagination({}, "call-center-dispatcher/boards"),
            toolbarHeight: 0,
            footerHeight: 0,
        };
    },

    watch: {
        "socketData.driverUpdated": {
            deep: true,
            handler() {
                if (1 === this.paginated.current_page) {
                    this.paginated.data = this.__updateActiveBoardList(
                        this.paginated.data,
                        this.socketData.driverUpdated,
                        this.paginated.per_page,
                    );
                }
            },
        },
        "socketData.driverShipped": {
            deep: true,
            handler() {
                this.paginated.data = this.__updateDriverShipped(this.paginated.data, this.socketData.driverShipped);
            },
        },

        "paginated.current_page": function () {
            this.paginated.getBoards;
        },
        "paginated.per_page": function () {
            this.paginated.getBoards;
        },
        "paginated.search": function () {
            this.paginated.getBoards;
        },
    },

    computed: {
        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 18;
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

        getDate(date) {
            return moment(new Date(date)).format("YYYY-MM-DD");
        },
    },

    mounted() {
        this.toolbarHeight = this.$refs.toolbar.clientHeight;
        this.footerHeight = this.$refs.footer.clientHeight;
    },

    created() {
        this.paginated.getBoards;
    },
};
