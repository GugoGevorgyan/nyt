<!-- @format -->

<template>
    <div :style="{ 'min-height': height + 'px' }" style="position: relative">
        <v-row no-gutters v-if="order" :style="{ opacity: loading ? 0.5 : 1 }">
            <v-col cols="12" md="7">
                <div class="d-flex elevation-1 mb-1" style="overflow: hidden">
                    <v-tabs
                        v-model="tab"
                        color="yellow darken-3"
                        active-class="active-tab"
                        style="max-height: 28px; overflow: hidden"
                        hide-slider
                        :show-arrows="false"
                    >
                        <v-tab :key="0" class="elevation-1 font-weight-light" style="max-height: 28px">
                            <span style="font-size: 10px" :style="{ color: 0 !== tab ? '#F9A825' : '#FFFFFF' }"
                                >Оснавная информация</span
                            >
                        </v-tab>
                        <v-tab :key="1" class="elevation-1 font-weight-light" style="max-height: 28px">
                            <span style="font-size: 10px" :style="{ color: 1 !== tab ? '#F9A825' : '#FFFFFF' }"
                                >История заказа</span
                            >
                        </v-tab>
                        <v-tab :key="2" class="elevation-1 font-weight-light" style="max-height: 28px">
                            <span style="font-size: 10px" :style="{ color: 2 !== tab ? '#F9A825' : '#FFFFFF' }"
                                >Отзывы</span
                            >
                        </v-tab>
                        <v-tab :key="3" class="elevation-1 font-weight-light" style="max-height: 28px">
                            <span style="font-size: 10px" :style="{ color: 3 !== tab ? '#F9A825' : '#FFFFFF' }"
                                >Коментарии</span
                            >
                            <div
                                class="tab-badge elevation-5"
                                v-if="history.comments && history.comments.length"
                            >
                                {{ history.comments.length }}
                            </div>
                        </v-tab>
                    </v-tabs>
                    <v-spacer />
                    <v-tooltip right>
                        <template v-slot:activator="{ on: on }">
                            <v-btn v-on="on" small @click="getInfo()" icon color="yellow darken-3">
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <small>Обновить данные заказа</small>
                    </v-tooltip>
                </div>
                <v-tabs-items v-model="tab">
                    <!--Info-->
                    <v-tab-item :key="0">
                        <div class="pa-4" :style="{ height: height - 32 + 'px' }" style="overflow-y: auto">
                            <create-info :order="order"></create-info>
                        </div>
                    </v-tab-item>

                    <!--History-->
                    <v-tab-item :key="1">
                        <div class="pa-4" :style="{ height: height - 32 + 'px' }" style="overflow-y: auto">
                            <history
                                :order="order"
                                :history="history"
                                :board="board"
                                :in-process="inProcess"
                                @center="center = $event"
                                @orderProgress="orderProgress = $event"
                            />
                        </div>
                    </v-tab-item>

                    <!--Feedbacks-->
                    <v-tab-item :key="2">
                        <div class="pa-4" :style="{ height: height - 32 + 'px' }" style="overflow-y: auto">
                            <feedbacks
                                :history="history"
                                :worker-feed-backs="workerFeedBacks"
                                :client-feed-back="clientFeedBack"
                                :driver-feed-back="driverFeedBack"
                            />
                        </div>
                    </v-tab-item>

                    <!--Comments-->
                    <v-tab-item :key="3">
                        <div class="pa-4" :style="{ height: height - 32 + 'px' }" style="overflow-y: auto">
                            <comments :comments="history.comments" />
                        </div>
                    </v-tab-item>
                </v-tabs-items>
            </v-col>
            <v-col cols="12" md="5">
                <info-map
                    :height="height"
                    :order="order"
                    :history="history"
                    :board="board"
                    :order-progress="orderProgress"
                    :center="center"
                    :in-process="inProcess"
                />
            </v-col>
        </v-row>
        <div
            v-if="!loading && !order"
            :style="{ height: height + 'px' }"
            style="width: 100%"
            class="d-flex align-center justify-center"
        >
            <v-alert outlined type="error" dense>
                <span
                    >Заказ под номером <strong>{{ orderId }}</strong> не найден</span
                >
            </v-alert>
        </div>
        <v-overlay :opacity="0" absolute :value="loading">
            <v-progress-circular indeterminate :width="2" :size="50" color="yellow darken-3" />
        </v-overlay>
    </div>
</template>
<script>
import axios from "axios";
import Snackbar from "../../facades/Snackbar";
import InfoMap from "./InfoMap";
import CreateInfo from "./CreateInfo";
import History from "./History";
import Feedbacks from "./Feedbacks";
import Comments from "./Comments";

export default {
    name: "InfoBox",

    components: { Comments, Feedbacks, History, CreateInfo, InfoMap },

    props: {
        orderId: {
            required: true,
        },
        orderObj: {
            required: true,
        },
        height: {
            required: true,
        },
    },

    data() {
        return {
            loading: false,
            order: null,
            tab: 0,
            history: {
                attaches: [],
                shipped: [],
                currentShipped: null,
                stages: null,
                completed: null,
                feedbacks: [],
                complaints: [],
                comments: [],
            },

            orderProgress: null,
            center: null,
        };
    },
    computed: {
        inProcess() {
            if (this.order) {
                return !this.order.completed && !this.order.canceled;
            }
        },
        board() {
            if (this.history.completed) {
                return {
                    car: this.history.completed.car,
                    driver: this.history.completed.driver,
                };
            } else if (this.history.currentShipped && this.history.currentShipped.status.status === 2) {
                return {
                    car: this.history.currentShipped.driver.car,
                    driver: this.history.currentShipped.driver,
                };
            }
        },

        clientFeedBack() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.find(item => item.writable.client_id);
            }
        },
        driverFeedBack() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.find(item => item.writable.driver_id);
            }
        },
        workerFeedBacks() {
            if (this.order && this.history.feedbacks) {
                return this.history.feedbacks.filter(item => item.writable.system_worker_id);
            }
        },
    },
    watch: {
        orderId() {
            this.getInfo();
        },
    },
    methods: {
        getInfo() {
            this.loading = true;
            axios
                .get(process.env.MIX_APP_WORKER_URL + "order/info/" + this.orderId)
                .then(response => {
                    this.loading = false;
                    this.order = response.data.order;
                    this.history = response.data.history;
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },
    created() {
        if (this.orderObj) {
            this.order = this.orderObj.order;
            this.history = this.orderObj.history;
        } else {
            this.getInfo();
        }
    },
};
</script>

<style>
.active-tab {
    color: white !important;
    background-color: #f9a825 !important;
}
.tab-badge {
    margin-left: 5px;
    display: flex;
    letter-spacing: 0;
    background-color: #ff3d00;
    color: white;
    padding: 3px;
    font-size: 10px;
    border-radius: 50%;
    height: 20px;
    width: 20px;
    justify-content: center;
    align-items: center;
}
</style>
