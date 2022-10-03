<!-- @format -->

<template>
    <v-dialog max-width="400px" width="100%" persistent v-model="orderFeedback.status">
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
            <v-progress-linear v-if="progressFeedback" height="3" color="amber" indeterminate />

            <v-card-text>
                <v-layout justify-center column align-content-center fill-height v-if="orderFeedback.isRating">
                    <span class="text--primary">Price: {{ orderProgress.price }}</span>
                    <span class="text--primary">Distance: {{ orderProgress.distance }}</span>
                    <span class="text--primary">Duration: {{ orderProgress.duration }}</span>
                </v-layout>

                <v-divider />
                <v-layout justify-center align-content-center fill-height v-if="orderFeedback.isRating">
                    <v-rating v-model="orderFeedback.assessment" @input="getAssessmentDetails">
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

                <v-radio-group v-model="orderFeedback.options_id" dense>
                    <v-radio
                        :key="option.option"
                        :label="option.name"
                        :value="option.option"
                        v-for="option in orderFeedback.typeOptions"
                        color="yellow darken-3"
                    />
                </v-radio-group>
            </v-card-text>
            <v-btn
                block
                :disabled="
                    (orderFeedback.isRating && !orderFeedback.assessment) ||
                    (!orderFeedback.isRating && !orderFeedback.options_id)
                "
                @click="sendOrderFeedback"
                color="yellow darken-3"
                v-text="orderFeedback.isRating ? 'Заценить' : 'Отправить'"
                tile
            />
        </v-card>
    </v-dialog>
</template>

<script>
import { Map } from "../../mixins";
import Snackbar from "./../../facades/Snackbar";
import { mapMutations, mapState } from "vuex";
import axios from "axios";

export default {
    name: "OrderFeedbackComponent",

    mixins: [Map],

    data() {
        return {
            orderCancelFeedbackInterval: undefined,
            ratingColors: [
                "yellow",
                "yellow darken-1",
                "yellow darken-2",
                "yellow darken-3",
                "orange",
                "orange darken-1",
            ],
            isText: false,
            progressFeedback: false,
        };
    },

    computed: {
        ...mapState({ orderFeedback: "orderFeedback", orderProgress: "orderProgress", orderForm: "orderForm" }),

        feedbackStatus() {
            return this.$store.state.orderFeedback.status;
        },
    },

    watch: {
        feedbackStatus(newVal, oldVal) {
            if (newVal) {
                this.orderCancelFeedbackInterval = setTimeout(() => {
                    this.initOrderFeedback({ status: false });
                }, 300000);
            }
        },
    },

    methods: {
        ...mapMutations({
            initOrderFeedback: "initOrderFeedback",
            initOrderForm: "initOrderForm",
            initOrderProgress: "initOrderProgress",
            orderInit: "orderInit",
        }),

        genColor(i) {
            return this.ratingColors[i];
        },

        sendOrderFeedback() {
            axios
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
                    this.initOrderForm({ open: true });
                    this.orderInit({});
                    this.initOrderFeedback({});
                    this.initOrderProgress({});
                    Snackbar.show("Thank for feedback", "yellow darken-3");
                })
                .catch(error => {
                    this.errorHandler(error.response).forEach(error => this.errors.add(error));
                });
        },

        getAssessmentDetails() {
            this.progressFeedback = true;
            return axios
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
    },

    beforeDestroy() {
        clearInterval(this.orderCancelFeedbackInterval);
    },
};
</script>

<style scoped></style>
