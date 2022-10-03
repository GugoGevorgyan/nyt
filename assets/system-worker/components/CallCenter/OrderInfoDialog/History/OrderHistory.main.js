/** @format */

import moment_tz from "moment-timezone";
import moment from "moment";
import { mutators } from "../../../../mixins/Mutators";
import { ORDER_STATUS } from "../../../../plugins/config";

export default {
    name: "OrderHistory",

    mixins: [mutators],

    props: {
        height: {
            required: true,
        },
        order: {
            required: true,
        },
        history: {
            required: true,
        },
        board: {
            required: true,
        },
        inProcess: {
            required: true,
        },
        loading: {
            required: true,
        },
        clientFeedBack: {
            required: true,
        },
        driverFeedBack: {
            required: true,
        },
        workerFeedBacks: {
            required: true,
        },
    },

    data() {
        return {
            orderProgress: null,
            checkDriver: false,
        };
    },

    filters: {
        formatSeconds(seconds) {
            if (!seconds) {
                return "0 сек.";
            }

            let hours = 0;
            let minutes = 0;
            let returnSeconds = 0;
            if (60 <= seconds) {
                minutes = Math.floor(seconds / 60);
                returnSeconds = seconds % 60;

                if (60 <= minutes) {
                    hours = Math.floor(minutes / 60);
                    minutes %= 60;
                }
            } else {
                returnSeconds = seconds;
            }

            return `${hours ? hours + "ч. " : ""}${minutes ? minutes + "мин. " : ""}${
                returnSeconds ? returnSeconds + "сек." : ""
            }`;
        },
        formatMeters(meters) {
            if (!meters) {
                return "0 м.";
            }

            let kms = 0;
            let returnMeters = 0;
            if (1000 <= meters) {
                kms = Math.floor(meters / 1000);
                returnMeters = meters % 1000;
            } else {
                returnMeters = meters;
            }
            return `${kms ? kms + "км " : ""}${returnMeters ? returnMeters + "м" : ""}`;
        },
    },

    watch: {
        orderProgress() {
            if (null !== this.orderProgress) {
                this.$emit("orderProgress", this.orderProgress);
            }
        },
        order() {
            if (!this.order) {
                this.orderProgress = null;
            }
        },
    },

    methods: {
        assessmentColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },
        feedbackText(feedback) {
            if (feedback.text) {
                return feedback.text;
            } else if (feedback.option) {
                return feedback.option.name;
            }
        },
        isToday(someDate) {
            let today = new Date();
            return (
                someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear()
            );
        },
        getDateShorted(date) {
            let itemDate = new Date(date);
            return this.isToday(itemDate)
                ? "сегодня " + moment(itemDate).format("HH:mm")
                : moment(itemDate).format("DD/MMM/YYYY") + " " + moment(itemDate).format("HH:mm");
        },
        getTime(date) {
            return moment(new Date(date)).format("HH:mm");
        },
        driverBoardFullName() {
            if (this.board.length) {
                return this.board.driver_info.surname + this.board.driver_info.name + this.board.driver_info.patronymic;
            }
            return "";
        },
    },

    // created() {
    //     if (
    //         this.order.preorder &&
    //         30 < moment(this.order.preorder.time).diff(moment()) &&
    //         this.order.status_id === ORDER_STATUS.PENDING
    //     ) {
    //         this.checkDriver = true;
    //     }
    // },
};
