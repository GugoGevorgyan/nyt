<!-- @format -->

<template>
    <v-card flat v-if="order" max-height="1100" class="border-lg">
        <v-card-title class="font-weight-light px-4 py-2 grey lighten-3">
            {{ title }}
            <v-spacer />
            <v-btn icon color="grey" @click="$emit('close')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider class="ma-0" />
        <v-card-text :style="{ height: height + 'px' }" style="overflow-y: hidden" class="pa-0">
            <v-row no-gutters v-if="order">
                <v-col cols="12" md="5">
                    <order-info-map
                        :order-progress="orderProgress"
                        :order="order"
                        :history="history"
                        :board="board"
                        :loading="loading"
                        :center="center"
                        :height="height"
                        :in-process="inProcess"
                    />
                </v-col>
                <v-col cols="12" md="7">
                    <div class="px-4 d-flex justify-space-between">
                        <div class="d-flex py-1">
                            <div class="d-flex align-center">
                                <div class="pr-2 font-weight-regular" style="font-size: 18px">{{
                                    order.status.text
                                }}</div>
                                <color-round :color="order.status.color" />
                            </div>
                            <div class="mx-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"></div>
                            <div class="d-flex align-center">
                                <v-rating
                                    dense
                                    small
                                    readonly
                                    :value="clientFeedBack ? clientFeedBack.assessment : 0"
                                    :color="clientFeedBack ? assessmentColor(clientFeedBack.assessment) : 'gray'"
                                    :background-color="
                                        clientFeedBack ? assessmentColor(clientFeedBack.assessment) : 'gray'
                                    "
                                />
                            </div>
                            <div class="mx-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"></div>
                            <div class="d-flex align-center">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            class="mr-1"
                                            small
                                            v-bind="attrs"
                                            v-on="on"
                                            icon
                                            color="yellow darken-3"
                                            @click="getHistory()"
                                        >
                                            <v-icon>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <small>Обновить информацию</small>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            @click="feedbackDialog = true"
                                            class="mr-1"
                                            small
                                            v-bind="attrs"
                                            v-on="on"
                                            icon
                                            color="yellow darken-3"
                                        >
                                            <v-icon>mdi-comment-plus-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <small>Добавить отзыв</small>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            v-if="!order.completed && !order.canceled"
                                            class="mr-1"
                                            small
                                            v-bind="attrs"
                                            v-on="on"
                                            icon
                                            color="error"
                                            @click="cancelDialog = true"
                                        >
                                            <v-icon>mdi-close-circle-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <small>Отменить заказ</small>
                                </v-tooltip>
                            </div>
                            <div class="mx-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"></div>
                            <div class="d-flex align-center mr-4">
                                <v-text-field
                                    label="Сообщение"
                                    name="message"
                                    v-model="message"
                                    :error-messages="errors.collect('message')"
                                    v-validate="'required|max:150'"
                                    autocomplete="off"
                                    @keypress="$event.keyCode === 13 ? sendMessage(order) : null"
                                    hide-details
                                    style="width: 300px"
                                />
                                <v-btn
                                    small
                                    depressed
                                    class="ml-2"
                                    icon
                                    color="quaternary"
                                    @click="sendMessage(order)"
                                >
                                    <v-icon light v-text="'mdi-send'" />
                                </v-btn>
                            </div>
                        </div>

                        <v-expand-transition>
                            <div class="d-flex align-center" v-if="order.completed && order.corporate">
                                <div
                                    class="mr-3 pr-2 font-weight-regular"
                                    style="border-right: 1px solid rgba(0, 0, 0, 0.12); font-size: 18px"
                                    v-text="'Слип'"
                                />
                                <label>
                                    <input
                                        style="max-width: 70px; border: 1px solid #f9a825"
                                        type="number"
                                        class="mr-2 text-center"
                                        v-model="slip"
                                    />
                                </label>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            v-bind="attrs"
                                            v-on="on"
                                            :loading="slipLoading"
                                            :disabled="!slip || 4 < slip.length || 4 > slip.length || !slipChange"
                                            icon
                                            color="yellow darken-3"
                                            @click="updateSlip()"
                                        >
                                            <v-icon v-text="'mdi-check'" />
                                        </v-btn>
                                    </template>
                                    <small>Принять</small>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            v-bind="attrs"
                                            v-on="on"
                                            :disabled="!slipChange || slipLoading || !slip"
                                            icon
                                            color="error"
                                            @click="slip = order.completed.slip_number"
                                        >
                                            <v-icon v-text="'mdi-close'" />
                                        </v-btn>
                                    </template>
                                    <small>Отменить изменения</small>
                                </v-tooltip>
                            </div>
                        </v-expand-transition>
                    </div>

                    <v-tabs
                        v-model="tab"
                        grow
                        right
                        height="35"
                        active-class="white"
                        color="secondary"
                        background-color="grey lighten-5"
                    >
                        <v-tab :key="0">
                            <small class="font-weight-regular">основная информация</small>
                        </v-tab>
                        <v-tab :key="1">
                            <small class="font-weight-regular">История заказа</small>
                        </v-tab>
                        <v-tab :key="2">
                            <small class="font-weight-regular">Детальная информация</small>
                        </v-tab>
                        <v-tab :key="3">
                            <small class="font-weight-regular">
                                <v-badge
                                    v-if="history.comments.length"
                                    color="grey"
                                    inline
                                    :content="history.comments.length"
                                >
                                    комментарии
                                </v-badge>
                                <span v-else>комментарии</span>
                            </small>
                        </v-tab>
                    </v-tabs>

                    <v-tabs-items v-model="tab">
                        <!--AllTransaction Info-->
                        <v-tab-item :key="0" :style="{ height: contentHeight + 'px' }">
                            <order-info :height="contentHeight" :order="order" :history="history" />
                        </v-tab-item>

                        <!--AllTransaction History-->
                        <v-tab-item :key="1" :style="{ height: contentHeight + 'px' }">
                            <order-history
                                :order="order"
                                :history="history"
                                :in-process="inProcess"
                                :driver-feed-back="driverFeedBack"
                                :client-feed-back="clientFeedBack"
                                :worker-feed-backs="workerFeedBacks"
                                :board="board"
                                :loading="loading"
                                :height="contentHeight"
                                @call="$emit('call', $event)"
                                @center="center = $event"
                                @orderProgress="orderProgress = $event"
                            />
                        </v-tab-item>

                        <!--Detail Info-->
                        <v-tab-item :key="2" :style="{ height: contentHeight + 'px' }">
                            <order-info-detail
                                :details="history.completed"
                                :crossing="history.crossing"
                                :tariffs="history.tariff"
                                :order="history.order"
                                :height="contentHeight"
                                @details="history.completed = $event"
                            />
                        </v-tab-item>

                        <!--Worker comments-->
                        <v-tab-item :key="3" :style="{ height: contentHeight + 'px' }">
                            <order-info-comments
                                :process="inProcess"
                                :order="order"
                                :comments="history.comments"
                                :height="contentHeight"
                                :loading="loading"
                                @updateComments="history.comments = $event"
                            />
                        </v-tab-item>
                    </v-tabs-items>
                </v-col>
            </v-row>
        </v-card-text>

        <!--Cancel dialog-->
        <v-dialog v-model="cancelDialog" max-width="400" width="100%">
            <v-card flat v-if="inProcess">
                <v-card-text class="pa-4 text-center">
                    <span style="font-size: 20px"
                        >Вы уверены, что хотите отменить заказ номер: {{ order.order_id }}?</span
                    >
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn :disabled="cancelLoading" small color="primary" @click="cancelDialog = false">нет</v-btn>
                    <v-btn :loading="cancelLoading" small text color="error" @click="cancelOrder()">да</v-btn>
                    <v-spacer />
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--Feedback dialog-->
        <order-feedback-dialog
            :order="order"
            :board="board"
            :dialog="feedbackDialog"
            @close="feedbackDialog = false"
            @feedbacks="history.feedbacks = $event"
            @complaints="history.complaints = $event"
        />
    </v-card>
</template>
<script lang="js" src="./OrderInfoDialog.main.js" />
