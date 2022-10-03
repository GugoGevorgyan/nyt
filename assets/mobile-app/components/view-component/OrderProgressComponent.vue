<!-- @format -->

<template>
    <v-bottom-sheet v-model="orderProgress.status" :overlay-opacity="0.1" inset persistent>
        <v-chip v-if="orderProgress.showCordContent" class="mb-3 elevation-6 half-width" color="white">
            <template v-slot:default>
                <div>
                    <v-icon v-text="'mdi-crosshairs-gps'" />
                    Показать мои координаты
                </div>
                <v-switch v-model="orderProgress.showCord" class="show-my-cord" color="yellow darken-3" dense />
            </template>
        </v-chip>
        <v-sheet class="text-center" max-height="360" min-height="180">
            <v-progress-linear
                v-if="orderProgress.searchDriverValueView"
                :value="orderProgress.searchDriverValue"
                buffer-value="100"
                color="yellow darken-3"
                height="2"
            />
            <h3 v-text="orderProgress.text" />
            <v-divider />

            <v-window
                v-model="step"
                :show-arrows="false"
                omit
                style="height: 237px"
                v-if="orderProgress.onAccept || orderProgress.onWay || orderProgress.inPlace"
            >
                <v-window-item :omit="false" :value="1" class="fill-height full-width">
                    <v-flex v-if="orderProgress.onAccept">
                        <v-card-text>
                            <div>
                                <div class="headline">{{ driver.name }} {{ driver.surname }}</div>
                                <div>{{ car.mark }} {{ car.model }}</div>
                                <div>{{ car.color }}</div>
                                <div>{{ car.state_license_plate }}</div>
                            </div>
                        </v-card-text>
                        <v-img
                            :src="driver.photo"
                            class="shrink ma-2"
                            contain
                            height="125px"
                            style="flex-basis: 125px"
                        />
                    </v-flex>

                    <v-flex v-if="orderProgress.onWay">
                        <v-card-text>
                            <div>
                                <div class="headline">{{ driver.name }} {{ driver.surname }}</div>
                                <div>{{ car.mark }} {{ car.model }}</div>
                                <div>{{ car.color }}</div>
                                <div>{{ car.state_license_plate }}</div>
                            </div>
                        </v-card-text>
                        <v-img
                            :src="driver.photo"
                            class="shrink ma-2"
                            contain
                            height="125px"
                            style="flex-basis: 125px"
                        />
                    </v-flex>

                    <v-flex v-if="orderProgress.inPlace">
                        <v-card-text>
                            <div>
                                <div class="headline">{{ driver.name }} {{ driver.surname }}</div>
                                <div>{{ car.mark }} {{ car.model }}</div>
                                <div>{{ car.color }}</div>
                                <div>{{ car.state_license_plate }}</div>
                            </div>
                        </v-card-text>
                        <v-img
                            :src="driver.photo"
                            class="shrink ma-2"
                            contain
                            height="125px"
                            style="flex-basis: 125px"
                        ></v-img>
                    </v-flex>
                </v-window-item>

                <v-window-item :omit="true" :value="2" class="fill-height">
                    <v-layout align-content-start class="custom-broadcast-layout" fill-height justify-start>
                        <v-flex align-self-start class="custom-broadcast-flex-content" ref="msgContent">
                            <div v-for="message of messages">
                                <v-chip
                                    v-if="inMessages.length && 'client' !== message.sender"
                                    class="float-left mb-1 mt-1 full-width custom-broadcast-span-left"
                                    color="yellow lighten-1"
                                    v-text="message.text"
                                />
                                <v-chip
                                    v-if="onMessages.length && 'client' === message.sender"
                                    class="float-right custom-broadcast-span-right mb1 mt-1"
                                    color="grey lighten-3"
                                    v-text="message.text"
                                />
                            </div>
                        </v-flex>

                        <v-flex align-self-end class="broadcast-text-input">
                            <v-form autcomplete="off">
                                <v-text-field v-model="message" autofocus color="blue darken-2" dense type="text">
                                    <template v-slot:append>
                                        <v-icon
                                            color="blue darken-2"
                                            @click="sendMessageToDriver"
                                            large
                                            v-text="'mdi-send'"
                                        />
                                    </template>
                                </v-text-field>
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-window-item>
            </v-window>

            <v-divider />

            <v-layout fill-height style="height: 65px">
                <v-flex v-if="orderProgress.cancel">
                    <v-btn icon @click="cancelOrder">
                        <v-icon color="grey darken-1" v-text="'mdi-cancel'" />
                    </v-btn>
                    <p @click="cancelOrder">Oтмена</p>
                </v-flex>

                <v-flex v-if="orderProgress.connection">
                    <v-btn icon @click="step === 2 ? (step = 1) : (step = 2)">
                        <v-icon color="grey darken-1" v-text="'mdi-chat'" />
                    </v-btn>
                    <p @click="step === 2 ? (step = 1) : (step = 2)">Написать</p>
                </v-flex>

                <v-flex v-if="orderProgress.connection">
                    <a v-bind:href="'tel:+' + orderProgress.callDriver">
                        <v-btn icon>
                            <v-icon color="grey darken-1" v-text="'mdi-phone'" />
                        </v-btn>
                    </a>
                    <p v-bind:href="'tel:+' + orderProgress.callDriver">Позвонит</p>
                </v-flex>
            </v-layout>
        </v-sheet>
    </v-bottom-sheet>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import axios from "axios";
import { Broadcast, Map, Order } from "../../mixins";

export default {
    name: "OrderProgressComponent",

    mixins: [Broadcast, Map, Order],

    data() {
        return {
            orderSheetInterval: undefined,
            dialogue: false,
            step: 1,
            message: "",
            messages: [],
            onMessages: [],
            inMessages: [],
        };
    },

    computed: {
        ...mapState({
            orderProgress: "orderProgress",
            car: "car",
            driver: "driver",
            broadcast: "broadcast",
            snackbar: "snackbar",
        }),

        showCord() {
            return this.$store.state.orderProgress.showCord;
        },
    },

    watch: {
        showCord(val) {
            if (navigator.geolocation && val) {
                navigator.geolocation.watchPosition(position => {
                    let pos = { lat: position.coords.latitude, lut: position.coords.longitude };
                    this.broadcast.whisper(`broadcast-web/show-my-cord`, { show: this.showCord, cords: pos });
                });
            }

            if (!navigator.geolocation) {
                this.SNACKBAR({ show: true, text: "Geolocation is not supported by this browser." });
            }

            if (!val) {
                this.broadcast.whisper(`broadcast-web/show-my-cord`, { show: this.showCord });
            }
        },

        step(val) {
            if (2 === val) {
                this.scrollDawnMsg();
            }
        },
    },

    methods: {
        ...mapMutations([
            "initOrderProgress",
            "orderInit",
            "initOrderForm",
            "initOrderFeedback",
            "initClient",
            "SNACKBAR",
        ]),

        openDialogue() {},

        cancelOrder() {
            axios
                .post("/cancel-order")
                .then(response => {
                    if (!response.data.data.cancel_fee) {
                        this.initialize(false);
                        clearInterval(this.orderSheetInterval);
                        this.searchDriverValue = 0;
                    }
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
        },

        sendMessageToDriver(e) {
            this.messages.push({ text: this.message, sender: "client" });
            this.onMessages.push(this.message);
            this.broadcast.whisper(`broadcast-web/broadway-driver`, { text: this.message });
            this.message = "";
        },

        scrollDawnMsg() {
            let container = this.$refs.msgContent;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
    },

    beforeDestroy() {
        clearInterval(this.orderSheetInterval);
    },

    created() {
        if (this.orderProgress.status && this.orderProgress.searchDriverValueView) {
            this.orderSheetInterval = setInterval(() => {
                if (100 <= this.orderProgress.searchDriverValue) {
                    clearInterval(this.orderSheetInterval);
                    this.initOrderProgress({ status: false, cancel: false });
                } else {
                    this.initOrderProgress({ searchDriverValue: this.orderProgress.searchDriverValue + 0.1 });
                }
            }, 300);
        }
    },

    mounted() {
        this.broadcast.listen("BroadwayDriverTalk", talk => {
            new Audio("/storage/media/taxi-broadway.mp3").play();
            this.messages.push(talk.messageData);
            this.inMessages.push(talk.messageData.text);
            this.scrollDawnMsg();
        });
    },
};
</script>

<style scoped>
.custom-broadcast-layout {
    width: 100%;
    display: inline-block;
    box-sizing: border-box;
}

.custom-broadcast-flex-content {
    width: 100%;
    display: inline-block;
    height: 80%;
    padding: 7px;
    overflow-y: scroll;
}

.custom-broadcast-span-left {
    width: 100%;
    display: inline-block;
    white-space: pre-line;
    height: auto;
}

.custom-broadcast-span-right {
    width: 100%;
    display: inline-block;
    white-space: pre-line;
    height: auto;
}

.broadcast-text-input {
    width: 100%;
    float: left;
}

.show-my-cord {
    position: absolute;
    right: 5px;
}

.v-chip {
    margin-bottom: 0 !important;
    box-shadow: none !important;
    border-radius: 0 !important;
}
</style>
