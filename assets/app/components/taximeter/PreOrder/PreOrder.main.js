/** @format */

import { mapMutations, mapState } from 'vuex';
import moment from 'moment';
import { Map, Order } from '../../../services';

export default {
    name: "PreOrder",

    mixins: [Map, Order],

    props: {
        preorder: {
            required: false,
            default: false,
            type: Boolean,
        },
        orderFormOpened: {
            required: false
        }
    },

    /**
     * @Data
     */
    data() {
        return {
            timer: null,
            addMinute: null,
            now: new Date().toISOString().slice(0, 10),
            validFromDate: moment(new Date(), "YYYY-MM-DD").format("YYYY-MM-DD"),
            validEndDate: moment(new Date(), "YYYY-MM-DD").add(14, "days").format("YYYY-MM-DD"),
            datePickerOptions: {
                disabledDate(date) {
                    return date.getTime() < Date.now() - 8.64e7;
                },
            },
            elementDate: moment(),
        };
    },

    /**
     * @State Computed
     */
    computed: {
        ...mapState(["order","orderForm", "inOrder"]),

        today: {
            get() {
                return this.$store.state.today;
            },

            set(val) {
                this.updateToday(val);
            },
        },

        date: {
            get() {
                return this.$store.state.date;
            },

            set(val) {
                this.updateDate(val);
            },
        },

        time: {
            get() {
                return this.$store.state.time;
            },

            set(val) {
                this.updateTime(val);
            },
        },

        keymap() {
            return {
                "alt+t": this.togglePreOrder,
            };
        },
    },

    /**
     * @Methods
     */
    methods: {
        ...mapMutations(["updateToday", "updateDate", "updateTime", "orderInit", "initOrderForm"]),

        syncTime() {
            if (this.$store.state.orderForm.open) {
            clearInterval(this.timer);
            this.today = moment();

            this.timer = setInterval(() => {
                if (!this.inOrder && moment(this.today).format("HH:mm") < moment().format("HH:mm"))
                {
                    this.today = moment();
                }
            }, 1000); //1 seconds
           }
        },

        open() {
            setTimeout(() => clearInterval(this.timer), 1000);
        },

        close() {
            this.addMinute = null;
            this.syncTime();
        },

        updateOrderTime(currentTime) {
            if (this.orderFormOpened) {
                this.orderInit({
                    order_time: {
                        create_time: moment().format("YYYY-MM-DD HH:mm:ss"),
                        time: currentTime,
                        zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    },
                });

                if (this.order.address_from && this.orderForm.open) {
                    this.getOrderPrice();
                }
            }
        },
    },

    /**
     * @Watchers
     */
    watch: {
        'orderForm.displayFrom': function() {
            if (this.orderForm.displayFrom === "" && this.orderForm.displayTo === "") {
                this.addMinute = null;
            }
        },
        elementDate(val) {
            if (val) {
                if (val > this.today) {
                    clearInterval(this.timer);
                }
                this.date = new Date(this.elementDate).toISOString().slice(0, 10);
                this.time = new Date(this.elementDate).toTimeString().slice(0, 5);
                this.updateOrderTime(this.date + " " + this.time);

            } else {
                this.date = this.now;
            }
        },
        addMinute(val) {
            if (val) {
                this.time = moment().add(val, "minutes").format("HH:mm");
                this.date = new Date(moment()).toISOString().slice(0, 10);
            } else {
                this.time = moment(new Date()).format("HH:mm");
                if (!(this.date > new Date().toISOString().slice(0, 10))) {
                    this.syncTime();
                }
            }
        },

        today(val) {
            this.date = new Date(val).toISOString().slice(0, 10);
            this.time = new Date(val).toTimeString().slice(0, 5);
        },

        // @TODO -> Invalid browser code, freezes
        date(val) {
            if (val > this.now) {
                this.addMinute = null;
            }
        },

        time() {
            this.elementDate = +new Date(`${this.date} ${this.time}:00`);
        },
    },

    /**
     * @HOOK Created
     */
    created() {
        this.syncTime();
    },
};
