<!-- @format -->

<template>
    <div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Предложения водителям"></info-subtitle>
            <template v-if="history.shipped.length">
                <div
                    v-for="ship in history.shipped"
                    :key="ship.order_shipped_driver_id"
                    class="d-flex justify-space-between align-center mb-2"
                >
                    <span class="font-weight-medium mr-2">
                        {{ ship.driver.driver_info.surname }}
                        {{ ship.driver.driver_info.name }}
                        {{ ship.driver.driver_info.patronymic }}
                    </span>
                    <div class="d-flex align-center">
                        <span class="mr-2">{{ __getDateShorted(ship.created_at) }}</span>
                        <v-chip :color="ship.status.color" outlined x-small>{{ ship.status.text }}</v-chip>
                    </div>
                </div>
            </template>
            <v-chip v-if="!history.shipped.length" color="error" outlined x-small>нет предложений</v-chip>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Предложения водителям от операторов"></info-subtitle>
            <template v-if="history.attaches.length">
                <div
                    v-for="attach in history.attaches"
                    :key="attach.order_attach_id"
                    class="d-flex justify-space-between align-center mb-2"
                >
                    <span class="font-weight-medium mr-2">
                        {{ attach.shipped.driver.driver_info.surname }}
                        {{ attach.shipped.driver.driver_info.name }}
                        {{ attach.shipped.driver.driver_info.patronymic }}
                    </span>
                    <div class="d-flex align-center">
                        <span class="mr-2">{{ __getDateShorted(attach.shipped.created_at) }}</span>
                        <v-chip :color="attach.shipped.status.color" outlined x-small>{{
                            attach.shipped.status.text
                        }}</v-chip>
                    </div>
                </div>
            </template>
            <v-chip v-if="!history.attaches.length" color="error" outlined x-small>нет предложений</v-chip>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Борт"></info-subtitle>
            <div v-if="board">
                <v-row no-gutters class="mb-1 mt-0">
                    <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                        Водитель
                    </v-col>
                    <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                        <span class="mr-2">
                            {{ board.driver.driver_info.surname }}
                            {{ board.driver.driver_info.name }}
                            {{ board.driver.driver_info.patronymic }}
                        </span>
                        <v-menu transition="slide-x-transition" bottom right offset-x :close-on-content-click="false">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn x-small v-bind="attrs" icon color="primary" v-on="on" class="mr-2">
                                    <v-icon>mdi-information-outline</v-icon>
                                </v-btn>
                            </template>
                            <div style="background-color: white">
                                <div class="d-flex pa-2">
                                    <v-list class="pa-0">
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title>ФИО:</v-list-item-title>
                                                <v-list-item-subtitle>
                                                    {{ board.driver.driver_info.surname }}
                                                    {{ board.driver.driver_info.name }}
                                                    {{ board.driver.driver_info.patronymic }}
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Телефон:</v-list-item-title>
                                                <v-list-item-subtitle>
                                                    {{ board.driver.phone }}
                                                    <v-btn
                                                        small
                                                        icon
                                                        color="success"
                                                        @click="$emit('call', board.driver.phone)"
                                                    >
                                                        <v-icon small>mdi-phone</v-icon>
                                                    </v-btn>
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Рейтинг:</v-list-item-title>
                                                <v-list-item-subtitle>
                                                    <v-rating
                                                        dense
                                                        small
                                                        readonly
                                                        :value="board.driver.mean_assessment"
                                                        :color="__assessmentColor(board.driver.mean_assessment)"
                                                        :background-color="
                                                            __assessmentColor(board.driver.mean_assessment)
                                                        "
                                                    ></v-rating>
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                    <v-img
                                        class="mt-2"
                                        width="100"
                                        height="100"
                                        :src="board.driver.driver_info.photo"
                                    />
                                </div>
                            </div>
                        </v-menu>
                        <v-btn
                            v-if="inProcess"
                            small
                            icon
                            color="yellow darken-3"
                            @click="$emit('center', [{ lat: board.driver.lat, lut: board.driver.lut }])"
                        >
                            <v-icon small>mdi-map-marker</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-row no-gutters class="mb-1 mt-0">
                    <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                        Автомобиль
                    </v-col>
                    <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                        <span>
                            {{ board.car.mark }}
                            {{ board.car.model }}
                        </span>
                    </v-col>
                </v-row>
            </div>
            <v-chip v-if="!board" color="error" outlined x-small>заказ не принят</v-chip>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="История"></info-subtitle>
            <div v-if="history.stages">
                <v-row no-gutters>
                    <v-col cols="12" md="5">
                        <v-row no-gutters class="mb-1 mt-0">
                            <v-col cols="12" md="4" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                                Начат
                            </v-col>
                            <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                <div v-if="history.stages.started" class="d-flex align-center">
                                    <span class="mr-2">{{ __getDateShorted(history.stages.started) }}</span>
                                    <v-btn
                                        small
                                        icon
                                        color="yellow darken-3"
                                        @click="$emit('center', [history.stages.start])"
                                    >
                                        <v-icon small>mdi-map-marker</v-icon>
                                    </v-btn>
                                </div>
                                <v-chip v-else color="error" outlined x-small>нет данных</v-chip>
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mb-1 mt-0">
                            <v-col cols="12" md="4" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                                Принят
                            </v-col>
                            <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                <div v-if="history.stages.accepted" class="d-flex align-center">
                                    <span class="mr-2">{{ __getDateShorted(history.stages.accepted) }}</span>
                                    <v-btn
                                        small
                                        icon
                                        color="yellow darken-3"
                                        @click="$emit('center', [history.stages.accept])"
                                    >
                                        <v-icon small>mdi-map-marker</v-icon>
                                    </v-btn>
                                </div>
                                <v-chip v-else color="error" outlined x-small>нет данных</v-chip>
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mb-1 mt-0">
                            <v-col cols="12" md="4" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                                В Пути
                            </v-col>
                            <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                <div v-if="history.stages.on_wayed" class="d-flex align-center">
                                    <span class="mr-2">{{ __getDateShorted(history.stages.on_wayed) }}</span>
                                    <v-btn
                                        small
                                        icon
                                        color="yellow darken-3"
                                        @click="$emit('center', [history.stages.on_way])"
                                    >
                                        <v-icon small>mdi-map-marker</v-icon>
                                    </v-btn>
                                </div>
                                <v-chip v-else color="error" outlined x-small>нет данных</v-chip>
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mb-1 mt-0">
                            <v-col cols="12" md="4" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                                На месте
                            </v-col>
                            <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                <div v-if="history.stages.in_placed" class="d-flex align-center">
                                    <span class="mr-2">{{ __getDateShorted(history.stages.in_placed) }}</span>
                                    <v-btn
                                        small
                                        icon
                                        color="yellow darken-3"
                                        @click="$emit('center', [history.stages.in_place])"
                                    >
                                        <v-icon small>mdi-map-marker</v-icon>
                                    </v-btn>
                                </div>
                                <v-chip v-else color="error" outlined x-small>нет данных</v-chip>
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mb-1 mt-0">
                            <v-col cols="12" md="4" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                                Завершен
                            </v-col>
                            <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                <div v-if="history.stages.ended" class="d-flex align-center">
                                    <span class="mr-2">{{ __getDateShorted(history.stages.ended) }}</span>
                                    <v-btn
                                        small
                                        icon
                                        color="yellow darken-3"
                                        @click="$emit('center', [history.stages.end])"
                                    >
                                        <v-icon small>mdi-map-marker</v-icon>
                                    </v-btn>
                                </div>
                                <v-chip v-else color="error" outlined x-small>нет данных</v-chip>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col
                        v-if="
                            history.currentShipped.in_process_road &&
                            history.currentShipped.in_process_road.real_road.length
                        "
                        cols="12"
                        md="7"
                    >
                        <div class="text-center subtitle-1 mb-4">Маршрут</div>
                        <div class="d-flex justify-space-between">
                            <small>{{ __getTime(history.currentShipped.in_process_road.real_road[0].date) }}</small>
                            <small>
                                {{
                                    __getTime(
                                        history.currentShipped.in_process_road.real_road[
                                            history.currentShipped.in_process_road.real_road.length - 1
                                        ].date,
                                    )
                                }}
                            </small>
                        </div>
                        <v-slider
                            dense
                            :min="0"
                            :max="history.currentShipped.in_process_road.real_road.length - 1"
                            v-model="orderProgress"
                            color="#00C853"
                            track-color="#FFD600"
                            thumb-color="#00C853"
                            thumb-label="always"
                            append-icon="mdi-chevron-right"
                            prepend-icon="mdi-chevron-left"
                            @click:append="
                                orderProgress < history.currentShipped.in_process_road.real_road.length - 1
                                    ? orderProgress++
                                    : null
                            "
                            @click:prepend="0 < orderProgress ? orderProgress-- : null"
                        >
                            <template v-slot:thumb-label="{ value }">
                                <small
                                    >{{ __getTime(history.currentShipped.in_process_road.real_road[value].date) }}
                                </small>
                            </template>
                        </v-slider>
                    </v-col>
                </v-row>
            </div>
            <v-chip v-if="!history.stages" color="error" outlined x-small>заказ не принят</v-chip>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Расчет цены" />
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Начальная цена
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ __priceFormat(history.currentShipped.process.price) }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Общая цена сидения
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ __priceFormat(history.currentShipped.process.sitting_price) }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Общая цена паузы
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ __priceFormat(history.currentShipped.process.pause_price) }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Длителность паузы
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ history.currentShipped.process.waiting_time | formatSeconds }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Дистанция поездки
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ history.currentShipped.process.distance_traveled | formatMeters }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Длителность поездки
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="board && history.currentShipped && history.currentShipped.process">
                        {{ history.currentShipped.process.travel_time | formatSeconds }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Окончательная цена
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="history.completed">
                        {{ __priceFormat(history.completed.cost) }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
import { mutators } from "../../mixins/Mutators";
import InfoSubtitle from "./InfoSubtitle";

export default {
    name: "History",

    components: { InfoSubtitle },

    props: {
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
    },

    data() {
        return {
            orderProgress: null,
        };
    },

    mixins: [mutators],

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

    methods: {},
};
</script>
