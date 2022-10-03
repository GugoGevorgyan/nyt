<!-- @format -->

<template>
    <v-layout>
        <v-bottom-sheet
            inset
            width="100%"
            v-model="orderProgress.status"
            :overlay-opacity="0.2"
            overlay-color="light"
            persistent
            class="border"
        >
            <v-sheet class="text-center rounded-t-xl" height="250px" max-height="250">

                <v-card
                    v-if='orderProgress.pending'
                    class='mx-auto elevation-20 mt-1 rounded-t-xl'
                    height='100%'
                >
                    <v-progress-linear
                        v-model='searchDriverValue'
                        :buffer-value='100'
                        height='25px'
                        color='yellow darken-3'
                    />
                    <v-card-actions>
                        <v-card-title class='py-0'>
                            {{ orderProgress.text }}
                        </v-card-title>
                    </v-card-actions>
                    <v-divider />
                    <v-card-actions>
                        <v-card-subtitle>
                            <span>{{ order.address_from }}</span>
                            <span v-if='order.address_to'> - </span>
                            <span>{{ order.address_to }}</span>
                        </v-card-subtitle>
                    </v-card-actions>
                    <v-card-actions>
                        <v-card-text class='text-left py-0'>
                            <v-chip color='orange' dark outlined small>
                                {{ responseOrderData.coin }} {{ responseOrderData.currency }}
                            </v-chip>
                        </v-card-text>
                    </v-card-actions>
                    <v-card-actions>
                        <v-spacer />
                        <v-card-text v-if='orderProgress.cancel' class='text-right py-0'>
                            <v-tooltip left>
                                <template v-slot:activator='{ on }'>
                                    <v-btn
                                        color='gray darken-3'
                                        @click='cancelOrder'
                                        icon
                                    >
                                        <v-icon
                                            v-on='on'
                                        >
                                            mdi-cancel
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Отменить заказ</span>
                            </v-tooltip>
                        </v-card-text>
                    </v-card-actions>
                </v-card>

                <v-card
                    v-if='orderProgress.onWay || orderProgress.accept'
                    class='mx-auto elevation-20 mt-1 rounded-t-xl'
                    height='100%'
                >
                    <v-card-actions>
                        <v-layout column>
                            <v-flex>
                                <v-card-title class='py-0'>
                                    {{ orderProgress.text }}
                                </v-card-title>
                            </v-flex>
                            <v-flex>
                                <v-card-text class='text-left py-0'>
                                    <span>{{ car.color }}</span>
                                    <span>{{ car.mark }} {{ car.model }}</span>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                        <v-spacer />
                        <v-card-subtitle class='text-center align-center py-0'>
                            <v-icon
                                :color="car.color"
                                large
                            >
                                mdi-taxi
                            </v-icon>
                            <div fluid class='pl-2 pr-2 grey lighten-3' max-width='10px'>
                                <h4>{{ car.sts_number }}</h4>
                            </div>
                        </v-card-subtitle>
                    </v-card-actions>
                    <v-divider />

                    <v-card-actions>
                        <v-card-text class='text-left'>
                            <span>{{ order.address_from }}</span>
                            <span v-if='order.address_to'> - </span>
                            <span>{{ order.address_to }}</span>
                        </v-card-text>
                        <v-spacer />
                        <v-img
                            :src='driver.photo'
                            class='rounded-circle ma-2'
                            contain
                            width='25px'
                            height='25px'
                            style='flex-basis: 125px'
                        />
                        <v-card-subtitle>
                            <h3 class='driver--fullName'>{{ driver.name }} {{ driver.surname }}</h3>
                        </v-card-subtitle>
                    </v-card-actions>

                    <v-card-actions v-if='orderProgress.minute' class='py-0'>
                        <v-card-subtitle class='py-0 blue--text'>
                            {{ orderProgress.minute }}
                        </v-card-subtitle>
                    </v-card-actions>
                    <v-card-actions>
                        <v-card-text class='text-left py-0'>
                            <v-chip color='orange' dark outlined small>
                                {{ responseOrderData.coin }} {{ responseOrderData.currency }}
                            </v-chip>
                        </v-card-text>
                    </v-card-actions>

                    <v-card-actions>
                        <v-spacer />
                        <v-card-text v-if='orderProgress.cancel' class='text-right py-0'>
                            <v-tooltip left>
                                <template v-slot:activator='{ on }'>
                                    <v-btn
                                        color='gray darken-3'
                                        @click='cancelOrder(true)'
                                        icon
                                    >
                                        <v-icon
                                            v-on='on'
                                        >
                                            mdi-cancel
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Отменить заказ</span>
                            </v-tooltip>
                        </v-card-text>
                    </v-card-actions>
                </v-card>

                <!--        DRIVER IN PLACE STATE        -->
                <v-card
                    v-if="orderProgress.inPlace"
                    class='mx-auto elevation-20 mt-1 rounded-t-xl'
                    height='100%'
                >
                    <v-card-actions>
                        <v-layout column>
                            <v-flex>
                                <v-card-title class='py-0'>
                                    {{ orderProgress.text }}
                                </v-card-title>
                            </v-flex>
                            <v-flex>
                                <v-card-text class='text-left py-0'>
                                    <span>{{ car.color }}</span>
                                    <span>{{ car.mark }} {{ car.model }}</span>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                        <v-spacer />
                        <v-card-subtitle class='text-center align-center py-0'>
                            <v-icon
                                :color="car.color"
                                large
                            >
                                mdi-taxi
                            </v-icon>
                            <div fluid class='pl-2 pr-2 grey lighten-3' max-width='10px'>
                                <h4>{{ car.sts_number }}</h4>
                            </div>
                        </v-card-subtitle>
                    </v-card-actions>
                    <v-divider />


                    <v-card-actions class='py-0'>
                        <v-card-text class='text-left'>
                            <span>{{ order.address_from }}</span>
                            <span v-if='order.address_to'> - </span>
                            <span>{{ order.address_to }}</span>
                        </v-card-text>
                        <v-spacer />
                        <v-img
                            :src='driver.photo'
                            class='rounded-circle ma-2'
                            contain
                            width='25px'
                            height='25px'
                            style='flex-basis: 125px'
                        />
                        <v-card-subtitle>
                            <h3 class='driver--fullName'>{{ driver.name }} {{ driver.surname }}</h3>
                        </v-card-subtitle>
                    </v-card-actions>

                    <v-card-actions>
                        <v-layout justify-space-between>
                            <v-flex>
                                <v-layout column>
                                    <v-flex>
                                        <v-card-text class='py-0 text-left'>
                                            Бесплатные минуты ожидания - {{ orderProgress.features.free_wait_minute }}
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex>
                                        <v-card-text class='py-0 text-left'>
                                            Сумма ожидания за одну минуту - {{ orderProgress.features.paid_wait_minute }}
                                        </v-card-text>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-spacer />

                            <v-flex align-self-center>
                                <v-card-text class='py-0 text-right'>
                                    <v-chip color='orange' dark outlined small>
                                        {{ responseOrderData.coin }} {{ responseOrderData.currency }}
                                    </v-chip>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                    </v-card-actions>

                    <v-card-actions>
                        <v-spacer />
                        <v-card-text v-if='orderProgress.cancel' class='text-right py-0'>
                            <v-tooltip left>
                                <template v-slot:activator='{ on }'>
                                    <v-btn
                                        color='gray darken-3'
                                        @click='cancelOrder(true)'
                                        icon
                                    >
                                        <v-icon
                                            v-on='on'
                                        >
                                            mdi-cancel
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Отменить заказ</span>
                            </v-tooltip>
                        </v-card-text>
                    </v-card-actions>
                </v-card>

                <v-card
                    v-if="orderProgress.started"
                    class='mx-auto elevation-20 mt-1 rounded-t-xl'
                    height='100%'
                >
                    <v-card-actions>
                        <v-layout column>
                            <v-flex>
                                <v-card-title class='py-0 green--text'>
                                    {{ orderProgress.text }}
                                </v-card-title>
                            </v-flex>
                            <v-flex>
                                <v-card-text class='text-left py-0'>
                                    <span>{{ car.color }}</span>
                                    <span>{{ car.mark }} {{ car.model }}</span>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                        <v-spacer />
                        <v-card-subtitle class='text-center align-center py-0'>
                            <v-icon
                                :color="car.color"
                                large
                            >
                                mdi-taxi
                            </v-icon>
                            <div fluid class='pl-2 pr-2 grey lighten-3' max-width='10px'>
                                <h4>{{ car.sts_number }}</h4>
                            </div>
                        </v-card-subtitle>
                    </v-card-actions>
                    <v-divider />


                    <v-card-actions class='py-0'>
                        <v-card-text class='text-left'>
                            <span>{{ order.address_from }}</span>
                            <span v-if='order.address_to'> - </span>
                            <span>{{ order.address_to }}</span>
                        </v-card-text>
                        <v-spacer />
                        <v-img
                            :src='driver.photo'
                            class='rounded-circle ma-2'
                            contain
                            width='25px'
                            height='25px'
                            style='flex-basis: 125px'
                        />
                        <v-card-subtitle>
                            <h3 class='driver--fullName'>{{ driver.name }} {{ driver.surname }}</h3>
                        </v-card-subtitle>
                    </v-card-actions>

                    <v-card-actions>
                                <v-card-text class='py-0 text-right'>
                                    <v-chip color='orange' dark outlined small>
                                        {{ responseOrderData.coin }} {{ responseOrderData.currency }}
                                    </v-chip>
                                </v-card-text>
                    </v-card-actions>
                </v-card>
            </v-sheet>
        </v-bottom-sheet>
    </v-layout>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import moment from 'moment';

export default {
    name: "OrderProgressComponent",

    data() {
        return {
            orderProgressText: "Order Processed",
            searchDriverValue: 0,
            orderSheetInterval: undefined,
            repeatSearching: false,
            repeatSearchingText: "Продолжить поиск машин ?",
        };
    },

    computed: {
        ...mapState(["orderProgress", "inOrder", "car", "driver","order","responseOrderData", "orderRepeat"]),

        orderProgress: function(){
                return this.$store.state.orderProgress;
        },
    },

    watch: {
        'orderRepeat.continue' : function(val) {
            if (!val) {
                clearInterval(this.orderSheetInterval);
            } else {
                this.searchDriverValue = 0;
                this.orderSetInterval();
            }
        },
        'orderRepeat.status': function(val) {
            if (val) {
                clearInterval(this.orderSheetInterval);
            }
        },
        'orderRepeat.cancel': function(val) {
            if (val) {
                this.cancelOrder(false);
            }
        }
    },

    methods: {
        ...mapMutations(["initOrderProgress", "INIT_IN_ORDER_ACTION", "orderInit", "initOrderFeedback", "initOrderRepeat", "initOrderForm"]),

        cancelOrder(feedback = false) {
            this.$http
                .post("/cancel-order")
                .then(response => {
                    if (!response.data.data.cancel_fee) {
                        clearInterval(this.orderSheetInterval);
                        this.searchDriverValue = 0;
                    }
                    if (feedback) {
                        this.initOrderFeedback({
                            status: true,
                            isCancelFee: response.data.data.cancel_fee,
                            cancelPrice: response.data.data.cancel_price,
                            typeOptions: response.data.data.options,
                            abortedOrderId: response.data.data.aborted_id,
                            text: response.data.message,
                            isRating: false,
                        });
                    } else {
                        this.INIT_IN_ORDER_ACTION({ status: false, data: [], responseData: [] });
                        this.initOrderForm({ open: true });
                        this.initOrderProgress({ status: false });
                    }
                })
                .catch();
        },

        orderSetInterval() {
            if (this.orderProgress.status && this.orderProgress.radius && this.orderProgress.pending) {
                this.orderSheetInterval = setInterval(() => {
                    if (300 <= this.searchDriverValue) {
                        this.INIT_IN_ORDER_ACTION({ status: false, data: [], responseData: [] });
                        this.orderInit({});
                        clearInterval(this.orderSheetInterval);
                        this.initOrderProgress({ status: false, cancel: false });
                    } else {
                        let time = this.order.order_time.repeated_at
                            ? moment().diff(moment(this.order.order_time.repeated_at),'second')
                            : moment().diff(moment(this.order.order_time.create_time),'second');

                        if (!this.orderProgress.pending) {
                            clearInterval(this.orderSheetInterval);
                        }

                        time += 1;
                        if (time > 270) {
                            clearInterval(this.orderSheetInterval);
                            this.initOrderRepeat({
                                status:true ,
                                continue: false,
                                cancel: false,
                                title: "Нет свободных машин",
                            });
                            this.repeatSearching = true;
                        } else {
                            this.searchDriverValue = ((time*100)/500);
                        }
                    }
                }, 1000);
            };
        }
    },

    created() {
        this.orderSetInterval();
    },

    beforeDestroy() {
        clearInterval(this.orderSheetInterval);
    },
};
</script>

<style scoped>
.driver--fullName {
    width: max-content;
}
</style>
