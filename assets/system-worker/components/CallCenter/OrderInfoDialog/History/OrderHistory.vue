<!-- @format -->

<template>
    <div v-if="order" class="px-4 pt-4" :style="{ height: height + 'px' }" style="overflow-y: auto">
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center"> Предложения водителям </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <template v-if="history.shipments.length">
                    <div
                        v-for="ship in history.shipments"
                        :key="ship.order_shipped_driver_id"
                        class="d-flex justify-space-between align-center mb-2"
                        :style="{ opacity: loading ? 0.1 : 1 }"
                    >
                        <span class="font-weight-medium mr-2">
                            {{ ship.driver_info.surname }}
                            {{ ship.driver_info.name }}
                            {{ ship.driver_info.patronymic }}
                        </span>
                        <div class="d-flex align-center">
                            <span class="mr-2">{{ getDateShorted(ship.created_at) }}</span>
                            <v-chip :color="ship.status.color" outlined x-small>{{ ship.status.text }}</v-chip>
                        </div>
                    </div>
                </template>
                <v-chip v-if="!history.shipped.length && loading" color="error" outlined x-small>
                    нет предложений
                </v-chip>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center"> Предложения водителям от операторов </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <template v-if="history.attach.length">
                    <div
                        v-for="attach in history.attach"
                        :key="attach.order_attach_id"
                        class="d-flex justify-space-between align-center mb-2"
                        :style="{ opacity: loading ? 0.1 : 1 }"
                    >
                        <span class="font-weight-medium mr-2">
                            {{ attach.driver_info.surname }}
                            {{ attach.driver_info.name }}
                            {{ attach.driver_info.patronymic }}
                        </span>
                        <div class="d-flex align-center">
                            <span class="mr-2">{{ getDateShorted(attach.shipped.created_at) }}</span>
                            <v-chip :color="attach.shipped.status.color" outlined x-small>
                                {{ attach.shipped.status.text }}
                            </v-chip>
                        </div>
                    </div>
                </template>
                <v-chip v-if="!history.attach.length && !loading" color="error" outlined x-small>
                    нет предложений
                </v-chip>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center">
                <span class="mr-1">Борт</span>
                <v-btn v-if="inProcess && !loading && board" small icon color="yellow darken-3">
                    <v-icon small v-text="'mdi-map-marker'" />
                </v-btn>
            </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <div v-if="board && board.driver_info" :style="{ opacity: loading ? 0.1 : 1 }">
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Водитель
                        </v-col>
                        <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                            <span
                                class="mr-2"
                                v-text="
                                    board.driver_info.surname +
                                    ' ' +
                                    board.driver_info.name +
                                    ' ' +
                                    board.driver_info.patronymic
                                "
                            />
                            <v-menu
                                transition="slide-x-transition"
                                bottom
                                right
                                offset-x
                                :close-on-content-click="false"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                        <v-icon>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <div style="background-color: white">
                                    <div class="d-flex pa-2">
                                        <v-list class="pa-0">
                                            <v-list-item two-line>
                                                <v-list-item-content>
                                                    <v-list-item-title>ФИО:</v-list-item-title>
                                                    <v-list-item-subtitle
                                                        v-text="
                                                            board.driver_info.surname +
                                                            ' ' +
                                                            board.driver_info.name +
                                                            ' ' +
                                                            board.driver_info.patronymic
                                                        "
                                                    />
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item two-line v-if="board.length && board.driver">
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
                                            <v-list-item two-line v-if="board.length && board.driver">
                                                <v-list-item-content>
                                                    <v-list-item-title>Рейтинг:</v-list-item-title>
                                                    <v-list-item-subtitle>
                                                        <v-rating
                                                            dense
                                                            small
                                                            readonly
                                                            :value="board.driver.mean_assessment"
                                                            :color="assessmentColor(board.driver.mean_assessment)"
                                                            :background-color="
                                                                assessmentColor(board.driver.mean_assessment)
                                                            "
                                                        />
                                                    </v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list>
                                        <v-img
                                            v-if="board.length && board.driver_info"
                                            class="mt-2"
                                            width="100"
                                            height="100"
                                            :src="board.driver_info.photo"
                                        />
                                    </div>
                                </div>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2" v-if="board.length && board.driver">
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
                <v-chip v-if="!board && !loading" color="error" outlined x-small>заказ не принят</v-chip>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">История</h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <div v-if="history.stages" :style="{ opacity: loading ? 0.1 : 1 }">
                    <v-row no-gutters>
                        <v-col cols="12" md="5">
                            <v-row no-gutters class="mb-2">
                                <v-col
                                    cols="12"
                                    md="4"
                                    class="pr-2"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12)"
                                >
                                    Начат
                                </v-col>
                                <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                    <div v-if="history.stages.started" class="d-flex align-center">
                                        <span class="mr-2">{{ getDateShorted(history.stages.started) }}</span>
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
                            <v-row no-gutters class="mb-2">
                                <v-col
                                    cols="12"
                                    md="4"
                                    class="pr-2"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12)"
                                >
                                    Принят
                                </v-col>
                                <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                    <div v-if="history.stages.accepted" class="d-flex align-center">
                                        <span class="mr-2">{{ getDateShorted(history.stages.accepted) }}</span>
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
                            <v-row no-gutters class="mb-2">
                                <v-col
                                    cols="12"
                                    md="4"
                                    class="pr-2"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12)"
                                >
                                    В Пути
                                </v-col>
                                <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                    <div v-if="history.stages.on_wayed" class="d-flex align-center">
                                        <span class="mr-2">{{ getDateShorted(history.stages.on_wayed) }}</span>
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
                            <v-row no-gutters class="mb-2">
                                <v-col
                                    cols="12"
                                    md="4"
                                    class="pr-2"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12)"
                                >
                                    На месте
                                </v-col>
                                <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                    <div v-if="history.stages.in_placed" class="d-flex align-center">
                                        <span class="mr-2">{{ getDateShorted(history.stages.in_placed) }}</span>
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
                            <v-row no-gutters class="mb-2">
                                <v-col
                                    cols="12"
                                    md="4"
                                    class="pr-2"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12)"
                                >
                                    Завершен
                                </v-col>
                                <v-col cols="12" md="8" class="font-weight-medium pl-2 d-flex align-center">
                                    <div v-if="history.stages.ended" class="d-flex align-center">
                                        <span class="mr-2">{{ getDateShorted(history.stages.ended) }}</span>
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
                    </v-row>
                </div>
                <v-chip v-if="!history.stages && !loading" color="error" outlined x-small>заказ не принят</v-chip>
            </div>
        </div>
        <div class="mb-6">
            <h4>История траектории</h4>
            <v-divider class="mx-0 mb-9 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-slider
                    dense
                    v-if="history.process_road"
                    :max="history.process_road.real_road.length - 1"
                    :min="0"
                    v-model="orderProgress"
                    color="#00C853"
                    track-color="#FFD600"
                    thumb-color="#00C853"
                    thumb-label="always"
                    append-icon="mdi-chevron-right"
                    prepend-icon="mdi-chevron-left"
                    hide-details
                    @click:append="orderProgress < history.process_road.real_road.length - 1 ? orderProgress++ : null"
                    @click:prepend="0 < orderProgress ? orderProgress-- : null"
                >
                    <template v-slot:thumb-label="{ value }">
                        <small>
                            {{ __getTime(history.process_road.real_road[value].date) }}
                        </small>
                    </template>
                </v-slider>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">Расчет цены</h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <div :style="{ opacity: loading ? 0.1 : 1 }">
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Начальная цена
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{ "₽ " + new Intl.NumberFormat("de-DE").format(history.shipped.process.price) }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Общая цена сидения
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{
                                    "₽ " + new Intl.NumberFormat("de-DE").format(history.shipped.process.sitting_price)
                                }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Общая цена паузы
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{ "₽ " + new Intl.NumberFormat("de-DE").format(history.shipped.process.pause_price) }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Длителность паузы
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{ history.shipped.process.waiting_time | formatSeconds }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Дистанция поездки
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{ history.shipped.process.distance_traveled | formatMeters }}
                                |
                                {{ " ₽ " + new Intl.NumberFormat("de-DE").format(history.completed.distance_price) }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Длителность поездки
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="board && history.shipped && history.shipped.process">
                                {{ history.shipped.process.travel_time | formatSeconds }}
                                |
                                {{ " ₽ " + new Intl.NumberFormat("de-DE").format(history.completed.duration_price) }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Окончательная цена
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                            <span v-if="history.completed">
                                {{ "₽ " + new Intl.NumberFormat("de-DE").format(history.completed.cost) }}
                            </span>
                            <v-chip v-else color="error" outlined x-small>не нустановлена</v-chip>
                        </v-col>
                    </v-row>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center"> Отзывы </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <div :style="{ opacity: loading ? 0.1 : 1 }">
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Отзыв клиента
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 align-center">
                            <template v-if="clientFeedBack">
                                <v-rating
                                    dense
                                    small
                                    readonly
                                    :value="clientFeedBack.assessment"
                                    :color="clientFeedBack ? assessmentColor(clientFeedBack.assessment) : 'gray'"
                                    :background-color="
                                        clientFeedBack ? assessmentColor(clientFeedBack.assessment) : 'gray'
                                    "
                                />
                                <p class="pl-1 small">{{ feedbackText(clientFeedBack) }}</p>
                            </template>
                            <v-chip v-else color="error" outlined x-small>нет отзывоа от клиента</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Отзыв водителя
                        </v-col>
                        <v-col cols="12" md="9" class="font-weight-medium pl-2 align-center">
                            <template v-if="driverFeedBack">
                                <v-rating
                                    dense
                                    small
                                    readonly
                                    :value="driverFeedBack ? driverFeedBack.assessment : 0"
                                    :color="driverFeedBack ? assessmentColor(driverFeedBack.assessment) : 'gray'"
                                    :background-color="
                                        driverFeedBack ? assessmentColor(driverFeedBack.assessment) : 'gray'
                                    "
                                />
                                <p class="pl-1 small">{{ feedbackText(driverFeedBack) }}</p>
                            </template>
                            <v-chip v-else color="error" outlined x-small>нет отзывоа от водителя</v-chip>
                        </v-col>
                    </v-row>
                    <v-row no-gutters class="mb-2">
                        <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                            Отзывы работников
                        </v-col>
                        <v-col
                            cols="12"
                            md="9"
                            class="font-weight-medium pl-2 align-center"
                            :class="workerFeedBacks.length ? 'd-block' : 'd-flex'"
                        >
                            <template v-if="workerFeedBacks.length">
                                <div v-for="(feedback, index) in workerFeedBacks" :key="feedback.order_feedback_id">
                                    <div class="d-flex align-center">
                                        <v-rating
                                            class="mr-1"
                                            dense
                                            small
                                            readonly
                                            :value="feedback.assessment"
                                            :color="assessmentColor(feedback.assessment)"
                                            :background-color="assessmentColor(feedback.assessment)"
                                        />
                                        <span v-if="'driver' === feedback.readable_type">o водителе</span>
                                        <span v-else-if="'client' === feedback.readable_type">o клиенте</span>
                                    </div>

                                    <p class="pl-1 small">{{ feedbackText(feedback) }}</p>
                                    <v-divider v-if="index !== workerFeedBacks.length - 1"></v-divider>
                                </div>
                            </template>
                            <v-chip v-else color="error" outlined x-small>нет отзывов от работников</v-chip>
                        </v-col>
                    </v-row>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center"> Жалобы на работников </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div style="min-height: 30px; position: relative" class="rounded">
                <v-overlay :opacity="0.1" absolute :value="loading">
                    <v-progress-circular size="20" width="1" indeterminate color="yellow darken-3" />
                </v-overlay>
                <div :style="{ opacity: loading ? 0.1 : 1 }">
                    <template v-if="history.complaints.length">
                        <div v-for="(complaint, index) in history.complaints" no-gutters class="mb-2">
                            <p class="mb-1">
                                <span>От:</span>
                                <span style="color: #00c853" class="mr-1">
                                    {{ complaint.writer.name }}
                                    {{ complaint.writer.patronymic }}
                                    {{ complaint.writer.surname }}
                                </span>
                                <span>на:</span>
                                <span style="color: #c62828">
                                    {{ complaint.recipient.name }}
                                    {{ complaint.recipient.patronymic }}
                                    {{ complaint.recipient.surname }}
                                </span>
                            </p>
                            <p class="mb-1 font-weight-bold">{{ complaint.subject }}</p>
                            <p class="small font-weight-medium">{{ complaint.complaint }}</p>
                            <v-divider v-if="index !== history.complaints.length - 1"></v-divider>
                        </div>
                    </template>
                    <v-chip v-else color="error" outlined x-small>нет жалоб на работников</v-chip>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="js" src="./OrderHistory.main.js"/>

