<!-- @format -->

<!--suppress JSUnresolvedVariable -->
<template>
    <v-dialog :max-width="orderFeedback.isCancelFee ? '450px' : '400px'" width="100%" persistent v-model="orderFeedback.status">
        <v-card v-if="orderFeedback.isCancelFee" elevation="4" class="border">
            <v-card-title
                class="title"
                style="word-break: break-word"
                v-text="'Вы уверены, что хотите отменить заказ?'"
            />
            <v-card-text>
                <h1>{{ orderFeedback.text }} {{ orderFeedback.cancelPrice }} {{ orderForm.currency }}</h1>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn depressed @click="acceptOrderCancel(false)" color="yellow darken-3" tile v-text="'Нет'" />
                <v-btn depressed @click="acceptOrderCancel(true)" tile class="ml-3" v-text="'Да'" />
            </v-card-actions>
        </v-card>

        <v-card v-else elevation="4" class="border">
            <v-progress-linear color="amber" height="3" indeterminate v-if="progressFeedback" />

            <v-card-text>
                <v-layout align-content-center fill-height justify-center v-if="orderFeedback.isRating">
                    <v-rating @input="getAssessmentDetails" v-model="orderFeedback.assessment" dense small>
                        <template v-slot:item="props">
                            <v-icon
                                :color="props.isFilled ? genColor(props.index) : 'grey lighten-1'"
                                @click="props.click"
                                large
                            >
                                {{ props.isFilled ? "mdi-star-circle" : "mdi-circle-outline" }}
                            </v-icon>
                        </template>
                    </v-rating>
                </v-layout>

                <v-radio-group dense v-model="orderFeedback.options_id">
                    <v-radio
                        :key="option.option"
                        :label="option.name"
                        :value="option.option"
                        color="yellow darken-3"
                        v-for="option in orderFeedback.typeOptions"
                    />
                </v-radio-group>

                <v-radio
                    v-if="orderFeedback.typeOptions.length > 0"
                    label="Другое"
                    :value="isText"
                    @click="isText = true"
                />
                <v-textarea v-if="isText" rows="2" filled background-color="white" v-model="orderFeedback.text" dense />
            </v-card-text>
            <v-btn
                v-if="!orderFeedback.isCancelFee"
                :disabled="orderFeedback.isRating && (!orderFeedback.assessment || !orderFeedback.options_id)"
                @click="sendOrderFeedback"
                block
                color="yellow darken-3"
                tile
                v-text="orderFeedback.isRating ? 'Заценить' : 'Отправить'"
            />
        </v-card>
    </v-dialog>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import { Map } from "./../../services";
import Snackbar from "../../facades/Snackbar";
import { order, orderFeedback, orderProgress } from "../../store/initial";

export default {
    name: "OrderFeedbackComponent",

    data() {
        return {
            orderCancelFeedbackInterval: undefined,
            ratingColors: [
                "yellow",
                "yellow darken-1",
                "yellow darken-2",
                "yellow darken-3",
                "orange darken-2",
                "orange darken-3",
            ],
            isText: false,
            progressFeedback: false,
        };
    },

    mixins: [Map],

    computed: {
        ...mapState(["orderFeedback", "orderForm"]),

        inOrderStatus: {
            get() {
                return this.$store.state.inOrder;
            },
            set(value) {
                this.$store.state.inOrder = value;
            },
        },
    },

    watch: {
        ["orderFeedback.status"](newVal) {
            if (newVal) {
                this.orderCancelFeedbackInterval = setTimeout(() => {
                    this.initOrderFeedback({ status: false });
                }, 300000);
            }
        },
    },

    methods: {
        ...mapMutations([
            "initOrderFeedback",
            "INIT_IN_ORDER_ACTION",
            "initOrderForm",
            "orderInit",
            "initOrderProgress",
        ]),

        genColor(i) {
            return this.ratingColors[i];
        },

        acceptOrderCancel(cancel) {
            if (cancel) {
                this.$http
                    .post("/cancel-accept-place", { accept: true })
                    .then(response => {
                        this.initOrderFeedback({
                            status: true,
                            isCancelFee: response.data.data.cancel_fee,
                            cancelPrice: response.data.data.cancel_price,
                            typeOptions: response.data.data.options,
                            abortedOrderId: response.data.data.aborted_id,
                            text: response.data.message,
                            isRating: false,
                        });
                    })
                    .catch();
            } else {
                this.$http
                    .post("/cancel-accept-place", { accept: false })
                    .then(response => {})
                    .catch();

                this.initOrderFeedback({
                    status: false,
                    isRating: false,
                    isCancelFee: false,
                    cancelPrice: 0,
                    abortedOrderId: null,
                    completedOrderId: null,
                    options_id: null,
                    assessment: null,
                    text: null,
                    typeOptions: [],
                });
            }
        },

        sendOrderFeedback() {
            this.$http
                .post("/add_feedback", {
                    aborted_order_id: this.orderFeedback.abortedOrderId,
                    completed_order_id: this.orderFeedback.completedOrderId,
                    feedback: {
                        option_id: this.orderFeedback.options_id,
                        text: this.orderFeedback.text,
                        assessment: this.orderFeedback.assessment,
                    },
                })
                .then(result => {
                    this.INIT_IN_ORDER_ACTION({ status: false, data: [], responseData: [] });
                    this.inOrderStatus = false;
                    this.initOrderForm({ open: true });
                    this.orderInit(order);
                    this.initOrderFeedback(orderFeedback);
                    this.initOrderProgress(orderProgress);
                    Snackbar.show("Thank for feedback", "yellow darken-3");
                })
                .catch(error => {
                    this.errorHandler(error.response).forEach(error => this.errors.add(error));
                });
        },

        getAssessmentDetails() {
            this.progressFeedback = true;
            return this.$http
                .get(`/get-details-assessment/${this.orderFeedback.assessment}`)
                .then(details => {
                    this.progressFeedback = false;
                    this.initOrderFeedback({
                        status: true,
                        isRating: true,
                        options: true,
                        typeOptions: details.data._payload,
                    });
                })
                .catch();
        },
    },

    beforeDestroy() {
        clearInterval(this.orderCancelFeedbackInterval);
    },
};
</script>

<style scoped></style>
