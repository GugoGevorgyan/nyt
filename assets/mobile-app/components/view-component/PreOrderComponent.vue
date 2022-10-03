<!-- @format -->

<template>
    <v-container class="pa-0" fill-height fluid>
        <v-card class="pa-0" flat>
            <v-layout column>
                <v-flex>
                    <v-date-picker
                        full-width
                        no-title
                        locale="ru-ru"
                        v-model="date"
                        class="elevation-0 custom"
                        color="yellow darken-3"
                        scrollable
                    />
                </v-flex>

                <v-flex>
                    <v-time-picker
                        full-width
                        no-title
                        locale="ru-ru"
                        v-model="time"
                        class="elevation-0"
                        color="yellow darken-3"
                        format="24hr"
                    />
                </v-flex>
            </v-layout>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn text @click="close" v-text="'Сбросить '" tile />
                <v-spacer/>
                <v-btn color="yellow darken-3" dark @click="accept" tile v-text="'Применить'" />
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import { Map, Order } from '../../mixins';
import moment from 'moment';

export default {
    name: 'PreOrder',

    mixins: [Map, Order],

    /**
     * @Data
     */
    data() {
        return {
            timer: null,
            minutes: '60',
            minutesDisabled: false,
            menu: false,
            addMinute: null,
            now: new Date().toISOString().slice(0, 10),
            validFromDate: moment(new Date(), 'YYYY-MM-DD').format('YYYY-MM-DD'),
            validEndDate: moment(new Date(), 'YYYY-MM-DD').add(5, 'days').format('YYYY-MM-DD'),
        };
    },

    /**
     * @State Computed
     */
    computed: {
        ...mapState(['orderForm', 'order']),

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
                'alt+t': this.togglePreOrder,
            };
        },
    },

    /**
     * @Methods
     */
    methods: {
        ...mapMutations(['updateToday', 'updateDate', 'updateTime', 'orderInit', 'initOrderForm']),

        togglePreOrder() {
            if (this.orderForm.open) {
                if (this.menu) {
                    this.minimize();
                } else {
                    this.open();
                }
            }
        },

        syncTime() {
            clearInterval(this.timer);
            this.today = moment();
            this.timer = setInterval(() => (this.today = moment()), 60000);
        },

        open() {
            this.minutesDisabled = true;
            this.menu = true;
            this.minutes = null;
            setTimeout(() => clearInterval(this.timer), 300);
        },

        close() {
            this.syncTime();
            this.minutesDisabled = false;
            this.menu = false;
        },

        minimize() {
            this.minutesDisabled = false;
            this.menu = false;
        },

        accept() {
            this.minutesDisabled = false;
            this.menu = false;
        },
    },

    /**
     * @Watchers
     */
    watch: {
        addMinute(val) {
            if (val) {
                this.time = moment().add(val, 'minutes').format('HH:mm');
            } else {
                this.time = moment(new Date()).format('HH:mm');
            }
        },

        today(val) {
            this.date = new Date(val).toISOString().slice(0, 10);
            this.time = new Date(val).toTimeString().slice(0, 5);

            if (this.order.address_from) {
                this.getOrderPrice();
            }
        },

        minutes(val) {
            clearInterval(this.timer);

            this.today = val ? Date.now() + val * 60 * 1000 : Date.now();
            this.timer = setInterval(() => (this.today = val ? Date.now() + val * 60 * 1000 : Date.now()), 60000);
        },

        // @TODO -> Invalid browser code, freezes
        date(val) {
            this.today = +new Date(`${this.date} ${this.time}:00`);
            let dateTime = moment(this.today).format('YYYY-MM-DD HH:mm');

            this.orderInit({
                order_time: {
                    create_time: moment().format('YYYY-MM-DD HH:mm'),
                    time: dateTime,
                    zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                },
            });
        },

        time(val) {
            this.today = +new Date(`${this.date} ${this.time}:00`);
            let dateTime = moment(this.today).format('YYYY-MM-DD HH:mm');
            this.orderInit({
                order_time: {
                    create_time: moment().format('YYYY-MM-DD HH:mm'),
                    time: dateTime,
                    zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                },
            });
        },
    },

    /**
     * @HOOK Created
     */
    created() {
        this.syncTime();
    },
};
</script>

<style scoped></style>
