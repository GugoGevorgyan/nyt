<!-- @format -->

<template>
    <v-dialog v-model="dialog" persistent max-width="600" width="100%">
        <v-card flat v-if="order">
            <v-card-text class="pa-4 text-center">
                <v-tabs centered v-model="tab" color="yellow darken-3" active-class="white">
                    <v-tab :disabled="!board" key="driver">
                        <small class="font-weight-regular">Отзыв о водителе</small>
                    </v-tab>
                    <v-tab key="client">
                        <small class="font-weight-regular">Отзыв о клиенте</small>
                    </v-tab>
                    <v-tab key="worker">
                        <small class="font-weight-regular">Жалоба на работника</small>
                    </v-tab>
                </v-tabs>
                <v-tabs-items v-model="tab" style="height: 400px">
                    <v-tab-item :key="0" :disabled="!board" style="height: 400px">
                        <div class="pa-2 d-flex align-center justify-center" style="height: 400px">
                            <div style="width: 100%">
                                <v-rating
                                    large
                                    readonly
                                    :value="feedbackData.driver.assessment"
                                    :color="assessmentColor(feedbackData.driver.assessment)"
                                    :background-color="assessmentColor(feedbackData.driver.assessment)"
                                />
                                <div class="d-flex justify-center align-center">
                                    <v-btn
                                        :color="feedbackData.driver.negative ? 'error' : 'grey darken-1'"
                                        x-small
                                        text
                                        @click="feedbackData.driver.negative = true"
                                        class="mr-2"
                                        >Негативный</v-btn
                                    >
                                    <v-radio-group dense v-model="feedbackData.driver.negative" row>
                                        <v-radio class="ma-0" color="error" :value="true" />
                                        <v-radio class="ma-0" color="success" :value="false" />
                                    </v-radio-group>
                                    <v-btn
                                        :color="!feedbackData.driver.negative ? 'success' : 'grey darken-1'"
                                        x-small
                                        text
                                        @click="feedbackData.driver.negative = false"
                                        >Положительный</v-btn
                                    >
                                </div>
                                <v-alert dense outlined type="error" class="text-left">
                                    <small>Отзыв повлияет на рейтинг водителя</small>
                                </v-alert>
                                <v-textarea
                                    @keypress="$event.keyCode === 13 && !driverDisabled ? send() : null"
                                    v-model="feedbackData.driver.text"
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    label="Текст отзыва"
                                />
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item :key="1" style="height: 400px">
                        <div class="pa-2 d-flex align-center justify-center" style="height: 400px">
                            <div style="width: 100%">
                                <v-rating
                                    large
                                    readonly
                                    :value="feedbackData.client.assessment"
                                    :color="assessmentColor(feedbackData.client.assessment)"
                                    :background-color="assessmentColor(feedbackData.client.assessment)"
                                />
                                <div class="d-flex justify-center align-center">
                                    <v-btn
                                        :color="feedbackData.client.negative ? 'error' : 'grey darken-1'"
                                        x-small
                                        text
                                        @click="feedbackData.client.negative = true"
                                        class="mr-2"
                                        >Негативный</v-btn
                                    >
                                    <v-radio-group dense v-model="feedbackData.client.negative" row>
                                        <v-radio class="ma-0" color="error" :value="true"></v-radio>
                                        <v-radio class="ma-0" color="success" :value="false"></v-radio>
                                    </v-radio-group>
                                    <v-btn
                                        :color="!feedbackData.client.negative ? 'success' : 'grey darken-1'"
                                        x-small
                                        text
                                        @click="feedbackData.client.negative = false"
                                        >Положительный</v-btn
                                    >
                                </div>
                                <v-alert dense outlined type="error" class="text-left">
                                    <small>Отзыв повлияет на рейтинг клиента</small>
                                </v-alert>
                                <v-textarea
                                    @keypress="13 === $event.keyCode && !clientDisabled ? send() : null"
                                    v-model="feedbackData.client.text"
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    label="Текст отзыва"
                                />
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item :key="2" class="pa-2 d-flex align-center justify-center" style="height: 400px">
                        <div class="pa-2 d-flex align-center justify-center" style="height: 400px">
                            <div style="width: 100%">
                                <v-row no-gutters>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            :loading="workersLoading"
                                            :items="workers"
                                            v-model="feedbackData.worker.recipient_id"
                                            item-value="system_worker_id"
                                            dense
                                            color="yellow darken-3"
                                            label="Работник"
                                            outlined
                                        >
                                            <template v-slot:selection="data">
                                                <small
                                                    >{{ data.item.name }} {{ data.item.patronymic }}
                                                    {{ data.item.surname }}</small
                                                >
                                            </template>
                                            <template v-slot:item="data">
                                                <small
                                                    >{{ data.item.name }} {{ data.item.patronymic }}
                                                    {{ data.item.surname }}</small
                                                >
                                            </template>
                                        </v-select>
                                    </v-col>
                                    <v-col cols="12" md="12">
                                        <v-text-field
                                            v-model="feedbackData.worker.subject"
                                            dense
                                            color="yellow darken-3"
                                            outlined
                                            label="Тема жалобы"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="12">
                                        <v-textarea
                                            @keypress="13 === $event.keyCode && !workerDisabled ? send() : null"
                                            v-model="feedbackData.worker.complaint"
                                            dense
                                            color="yellow darken-3"
                                            outlined
                                            label="Текст жалобы"
                                        />
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </v-tab-item>
                </v-tabs-items>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn :disabled="loading" small color="error" @click="close()">отмена</v-btn>
                <v-btn :disabled="disabled" :loading="loading" small color="primary" @click="send()">принять</v-btn>
                <v-spacer />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import Snackbar from "../../facades/Snackbar";

export default {
    props: {
        dialog: {
            required: true,
        },
        order: {
            required: true,
        },
        board: {
            required: true,
        },
    },
    data() {
        return {
            loading: false,
            tab: null,
            workers: [],
            workersLoading: false,

            feedbackData: null,
            feedbackDataDefault: {
                type: "",
                driver: {
                    driver_id: null,
                    text: null,
                    assessment: 1,
                    negative: true,
                },
                client: {
                    client_id: null,
                    text: null,
                    assessment: 1,
                    negative: true,
                },
                worker: {
                    subject: null,
                    complaint: null,
                    recipient_id: null,
                },
            },
        };
    },
    watch: {
        "feedbackData.driver.negative": function () {
            this.feedbackData.driver.assessment = this.feedbackData.driver.negative ? 1 : 5;
        },
        "feedbackData.client.negative": function () {
            this.feedbackData.client.assessment = this.feedbackData.client.negative ? 1 : 5;
        },
        "feedbackData.type"() {
            if (this.feedbackData.driver && this.board.driver) {
                switch (this.feedbackData.type) {
                    case "driver":
                        this.feedbackData.driver.driver_id = this.board.driver.driver_id;
                        break;
                    case "client":
                        this.feedbackData.client.client_id = this.order.client_id;
                        break;
                }
            }
        },
        board() {
            this.tab = this.board ? 0 : 1;
        },
        dialog() {
            this.dialog ? this.show() : this.close();
        },
        tab() {
            switch (this.tab) {
                case 0:
                    this.feedbackData.type = "driver";
                    break;
                case 1:
                    this.feedbackData.type = "client";
                    break;
                case 2:
                    this.feedbackData.type = "worker";
                    break;
                default:
                    this.feedbackData.type = "driver";
            }
        },
    },
    computed: {
        driverDisabled() {
            return (
                !this.board ||
                !this.feedbackData.driver.text ||
                1 > this.feedbackData.driver.text.length ||
                2500 < this.feedbackData.driver.text.length
            );
        },
        clientDisabled() {
            return (
                !this.feedbackData.client.text ||
                1 > this.feedbackData.client.text.length ||
                2500 < this.feedbackData.client.text.length
            );
        },
        workerDisabled() {
            return (
                !this.feedbackData.worker.recipient_id ||
                !this.feedbackData.worker.subject ||
                1 > this.feedbackData.worker.subject.length ||
                2500 < this.feedbackData.worker.subject.length ||
                !this.feedbackData.worker.complaint ||
                1 > this.feedbackData.worker.complaint.length ||
                2500 < this.feedbackData.worker.complaint.length
            );
        },
        disabled() {
            if ("driver" === this.feedbackData.type) {
                return this.driverDisabled;
            } else if ("client" === this.feedbackData.type) {
                return this.clientDisabled;
            } else if ("worker" === this.feedbackData.type) {
                return this.workerDisabled;
            } else {
                return true;
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
        show() {
            this.feedbackData = this.feedbackDataDefault;
        },
        close() {
            this.$emit("close");
            this.feedbackData = this.feedbackDataDefault;
            this.tab = null;
            this.loading = false;
        },
        send() {
            let data = { ...this.feedbackData[this.feedbackData.type] };
            data.type = this.feedbackData.type;
            data.order_id = this.order.order_id;

            this.loading = true;
            this.$http
                .post("call-center/order-feedback/create", data)
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.$emit("feedbacks", response.data.data.feedbacks);
                    this.$emit("complaints", response.data.data.complaints);
                    this.close();
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },
        getWorkers() {
            this.workersLoading = true;
            this.$http
                .get("call-center/order-feedback/workers")
                .then(response => {
                    this.workers = response.data;
                    this.workersLoading = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.workersLoading = false;
                });
        },
    },
    created() {
        this.getWorkers();
        this.feedbackData = this.feedbackDataDefault;
    },
};
</script>
