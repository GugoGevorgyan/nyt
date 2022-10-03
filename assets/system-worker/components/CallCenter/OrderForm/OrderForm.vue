<!-- @format -->

<template>
    <v-card flat :dark="darkMode">
        <v-card-text>
            <div :style="{ height: order.client_id ? contentHeight : height }" style="overflow: auto">
                <v-form v-if="order.client_id && !client.in_order" data-vv-scope="order">
                    <v-container class="py-0 pa-0" fluid grid-list-lg>
                        <v-row no-gutters>
                            <v-col cols="12" lg="7" md="7" class="pr-md-2 mt-1">
                                <v-row>
                                    <v-col class="ma-0" cols="12" lg="12" md="12">
                                        <v-menu
                                            offset-x
                                            :value="errFrom"
                                            nudge-bottom="0"
                                            nudge-right="10"
                                            min-width="0"
                                            :close-on-content-click="false"
                                            :close-on-click="false"
                                        >
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    clearable
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    dense
                                                    class="rounded-2"
                                                    outlined
                                                    :loading="order.loadingFromCoordinates"
                                                    @click:clear="clearFromField()"
                                                    color="yellow darken-3"
                                                    data-vv-as="аддресс откуда"
                                                    label="Откуда*"
                                                    hint="аддресс откуда"
                                                    :id="'from-address'"
                                                    name="address_from"
                                                    hide-details
                                                    v-model="order.displayFrom"
                                                    :error-messages="errors.collect('order.address_from')"
                                                    v-validate="order.rules.address_from"
                                                >
                                                    <template v-slot:prepend-inner>
                                                        <v-icon
                                                            color="grey darken-3"
                                                            v-text="'mdi-map-marker-circle'"
                                                        />
                                                    </template>
                                                </v-text-field>
                                            </template>
                                            <v-alert dense type="error" class="ma-0">
                                                <small>{{ order.errFrom.msg }}</small>
                                            </v-alert>
                                        </v-menu>
                                    </v-col>
                                    <v-col class="ma-0" cols="12" lg="12" md="12">
                                        <v-menu
                                            v-if="!order.is_rent"
                                            offset-x
                                            :value="errTo"
                                            nudge-bottom="0"
                                            nudge-right="10"
                                            min-width="0"
                                            :close-on-content-click="false"
                                            :close-on-click="false"
                                        >
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    clearable
                                                    class="rounded-2"
                                                    hide-details
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    dense
                                                    outlined
                                                    @click:clear="clearToField()"
                                                    :loading="order.loadingToCoordinates"
                                                    data-vv-as="аддресс куда"
                                                    :error-messages="errors.collect('order.address_to')"
                                                   v-model="order.displayTo"
                                                    color="yellow darken-3"
                                                    hint="аддресс куда"
                                                    :id="'to-address'"
                                                    label="Куда"
                                                    name="address_to"
                                                    v-validate="order.rules.address_to"
                                                >
                                                    <template v-slot:prepend-inner>
                                                        <v-icon
                                                            color="grey darken-3"
                                                            v-text="'mdi-map-Search-outline'"
                                                        />
                                                    </template>
                                                </v-text-field>
                                            </template>
                                            <v-alert dense type="error" class="ma-0">
                                                <small>{{ order.errTo.msg }}</small>
                                            </v-alert>
                                        </v-menu>
                                        <v-select
                                            v-else
                                            dense
                                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                            :dark="darkMode"
                                            outlined
                                            :items="rentTimes"
                                            v-model="order.rent_time"
                                            item-text="name"
                                            item-value="id"
                                            placeholder="Часы"
                                            label="Час"
                                            class="pt-0"
                                        />
                                    </v-col>
                                </v-row>

                                <v-divider class="mb-3 mt-3" />

                                <v-row>
                                    <v-col cols="12" lg="6" md="6">
                                        <v-switch
                                            color="yellow darken-3"
                                            class="ma-0"
                                            v-model="order.is_meet"
                                            label="Встреча"
                                        />
                                        <v-tabs
                                            v-model="meetTab"
                                            :dark="darkMode"
                                            color="yellow darken-3"
                                            :hide-slider="!order.is_meet"
                                        >
                                            <v-tab :dark="darkMode" :disabled="!order.is_meet">Аэропорт</v-tab>
                                            <v-tab :dark="darkMode" :disabled="!order.is_meet">Станция метро</v-tab>
                                            <v-tab :dark="darkMode" :disabled="!order.is_meet">Вокзал</v-tab>
                                        </v-tabs>
                                        <v-tabs-items v-model="meetTab" class="mb-2 pt-4" :dark="darkMode">
                                            <v-tab-item :disabled="!order.is_meet">
                                                <v-autocomplete
                                                    :disabled="!order.is_meet"
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    dense
                                                    outlined
                                                    :items="airports"
                                                    clearable
                                                    data-vv-as="аэропорт"
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="name"
                                                    item-value="airport_id"
                                                    label="Выберите аэропорт"
                                                    open-on-clear
                                                    name="airport_id"
                                                    :error-messages="errors.collect('order.airport_id')"
                                                    v-model="meet.airport_id"
                                                    v-validate="
                                                        order.is_meet && meetTab === 0 ? meet.rules.airport_id : null
                                                    "
                                                />
                                            </v-tab-item>
                                            <v-tab-item :disabled="!order.is_meet">
                                                <v-autocomplete
                                                    :disabled="!order.is_meet"
                                                    dense
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    outlined
                                                    :items="metros"
                                                    clearable
                                                    data-vv-as="станция метро"
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="name"
                                                    item-value="metro_id"
                                                    label="Выберите станцию метро"
                                                    open-on-clear
                                                    name="metro_id"
                                                    :error-messages="errors.collect('order.metro_id')"
                                                    v-model="meet.metro_id"
                                                    v-validate="
                                                        order.is_meet && 1 === meetTab ? meet.rules.metro_id : null
                                                    "
                                                />
                                            </v-tab-item>
                                            <v-tab-item :disabled="!order.is_meet">
                                                <v-autocomplete
                                                    :disabled="!order.is_meet"
                                                    dense
                                                    outlined
                                                    :items="stations"
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    clearable
                                                    data-vv-as="вокзал"
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="name"
                                                    item-value="railway_station_id"
                                                    label="Выберите вокзал"
                                                    open-on-clear
                                                    name="railway_station_id"
                                                    :error-messages="errors.collect('order.railway_station_id')"
                                                    v-model="meet.railway_station_id"
                                                    v-validate="
                                                        order.is_meet && 2 === meetTab
                                                            ? meet.rules.railway_station_id
                                                            : null
                                                    "
                                                />
                                            </v-tab-item>
                                        </v-tabs-items>
                                        <v-text-field
                                            :disabled="!order.is_meet"
                                            outlined
                                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                            :dark="darkMode"
                                            dense
                                            color="yellow darken-3"
                                            data-vv-as="номер вагона"
                                            label="Информация (рейс / номер вагона)"
                                            v-model="meet.info"
                                            name="info"
                                            :error-messages="errors.collect('order.info')"
                                            v-validate="order.is_meet ? meet.rules.info : null"
                                        />
                                        <v-textarea
                                            rows="2"
                                            height="58px"
                                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                            :dark="darkMode"
                                            :disabled="!order.is_meet"
                                            outlined
                                            dense
                                            data-vv-as="текст таблички"
                                            label="Текст таблички"
                                            color="yellow darken-3"
                                            v-model="meet.text"
                                            name="meet_text"
                                            :error-messages="errors.collect('order.meet_text')"
                                            v-validate="order.is_meet ? meet.rules.text : null"
                                        />
                                        <h5 class="font-weight-light">Пасажир</h5>
                                        <template v-if="order.is_passenger">
                                            <div class="d-flex align-center">
                                                <p
                                                    class="mb-0 font-weight-medium"
                                                    style="width: 100%"
                                                    v-html="passengerText"
                                                />
                                                <v-btn icon color="primary" @click="passengerDialog = true">
                                                    <v-icon color="grey" small v-text="'mdi-pencil'" />
                                                </v-btn>
                                                <v-btn icon color="error" @click="clearPassenger()">
                                                    <v-icon small color="red" v-text="'mdi-close'" />
                                                </v-btn>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <v-btn
                                                :disabled="payCompany"
                                                outlined
                                                small
                                                icon
                                                color="yellow darken-3"
                                                @click="passengerDialog = true"
                                            >
                                                <v-icon v-text="'mdi-plus'" />
                                            </v-btn>
                                        </template>
                                    </v-col>

                                    <v-col cols="12" lg="6" md="6">
                                        <v-row>
                                            <v-col cols="12">
                                                <v-autocomplete
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    dense
                                                    outlined
                                                    :items="carOptions"
                                                    data-vv-as="параметры автомобиля"
                                                    clearable
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="option"
                                                    item-value="car_option_id"
                                                    label="Параметры автомобиля"
                                                    multiple
                                                    open-on-clear
                                                    name="car_option"
                                                    :error-messages="errors.collect('order.car_option')"
                                                    v-model="order.car_option"
                                                    v-validate="order.rules.car_option"
                                                />
                                            </v-col>
                                            <v-col cols="12" lg="6">
                                                <v-select
                                                    dense
                                                    outlined
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    :items="paymentMethods"
                                                    data-vv-as="способ оплаты"
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="name"
                                                    item-value="payment_type_id"
                                                    label="Способ оплаты*"
                                                    open-on-clear
                                                    name="payment_type_id"
                                                    :error-messages="errors.collect('order.payment_type_id')"
                                                    v-model="order.payment_type_id"
                                                    v-validate="order.rules.payment_type_id"
                                                />
                                                <v-select
                                                    v-if="companies.length"
                                                    :disabled="!payCompany"
                                                    :items="companies"
                                                    dense
                                                    outlined
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    data-vv-as="компания"
                                                    color="yellow darken-3"
                                                    item-color="yellow darken-3"
                                                    item-text="name"
                                                    item-value="company_id"
                                                    label="Компания оплаты*"
                                                    open-on-clear
                                                    name="company_id"
                                                    :error-messages="errors.collect('order.company_id')"
                                                    v-model="order.company_id"
                                                    v-validate="
                                                        payCompany && !order.company_id ? order.rules.company_id : null
                                                    "
                                                />
                                                <template v-if="payCompany">
                                                    <v-alert
                                                        v-if="!findCompanyCode || findCompanyCode.length < 5"
                                                        type="info"
                                                        outlined
                                                        dense
                                                    >
                                                        <small>Введите код компании</small>
                                                    </v-alert>
                                                    <v-alert v-else-if="findCompanyLoading" type="info" outlined dense>
                                                        <small>Поиск компании</small>
                                                    </v-alert>
                                                    <v-alert v-else-if="!findCompany" type="error" outlined dense>
                                                        <small>Компания не найдена</small>
                                                    </v-alert>
                                                    <v-alert v-if="findCompany" type="success" outlined dense>
                                                        <small>{{ findCompany.name }}</small>
                                                    </v-alert>
                                                </template>
                                                <v-text-field
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    :loading="findCompanyLoading"
                                                    :disabled="!payCompany"
                                                    clearable
                                                    dense
                                                    outlined
                                                    data-vv-as="код"
                                                    color="yellow darken-3"
                                                    label="Найти компанию по коду"
                                                    name="find_company"
                                                    :error-messages="errors.collect('order.find_company')"
                                                    v-model="findCompanyCode"
                                                    v-validate="
                                                        payCompany && !order.company_id ? order.rules.company_id : null
                                                    "
                                                />
                                                <v-textarea
                                                    rows="3"
                                                    row-height="0.4"
                                                    outlined
                                                    dense
                                                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                                                    :dark="darkMode"
                                                    data-vv-as="комментарий"
                                                    label="Комментарий"
                                                    color="yellow darken-3"
                                                    v-model="order.comments"
                                                    name="comment"
                                                    :error-messages="errors.collect('order.comment')"
                                                    v-validate="order.rules.comment"
                                                />
                                            </v-col>

                                            <v-col class="my-0 pb-0" cols="12" lg="6">
                                                <!--pre order-->
                                                <v-card
                                                    outlined
                                                    style="box-shadow: none; border-color: #d7d7d7; border-radius: 5px"
                                                >
                                                    <v-card-text>
                                                        <v-radio-group
                                                            class="my-0"
                                                            dense
                                                            v-model="addMinute"
                                                            :disabled="preOrderMinutesDisabled"
                                                        >
                                                            <v-radio
                                                                color="yellow darken-3"
                                                                label="Сейчас"
                                                                :value="0"
                                                            />
                                                            <v-radio
                                                                color="yellow darken-3"
                                                                label="Через 30 мин"
                                                                :value="30"
                                                            />
                                                            <v-radio
                                                                color="yellow darken-3"
                                                                label="Через 1 час"
                                                                :value="60"
                                                            />
                                                        </v-radio-group>

                                                        <v-datetime-picker
                                                            time-header-color="orange lighten-1"
                                                            date-header-color="orange lighten-1"
                                                            label="Дата и Время"
                                                            v-model="order.start_time"
                                                            :color-mode="darkMode"
                                                        />
                                                    </v-card-text>
                                                </v-card>
                                            </v-col>
                                            <v-col cols="12">
                                                <v-radio-group
                                                    hide-details
                                                    v-model="order.is_rent"
                                                    :row="true"
                                                    class="mt-0 pa-0 mb-2"
                                                    dense
                                                >
                                                    <v-radio color="grey darken-3" label="Заказ" :value="false" />
                                                    <v-radio color="grey darken-3" label="Аренда" :value="true" />
                                                </v-radio-group>
                                                <v-divider />
                                                <h5 class="font-weight-light">Класс автомобиля</h5>
                                                <div v-if="priceLoading || order.loadingFromCoordinates">
                                                    <v-progress-linear color="yellow darken-3" height="2" />
                                                </div>
                                                <v-sheet class="mb-4">
                                                    <v-slide-group
                                                        center-active
                                                        mandatory
                                                        show-arrows
                                                        v-model="order.car_class_id"
                                                    >
                                                        <v-slide-item
                                                            :key="car.car_class_id || car.class_id"
                                                            :value="car.car_class_id || car.class_id"
                                                            v-for="car in carClass"
                                                            v-slot:default="{ active, toggle }"
                                                        >
                                                            <v-card @click="toggle" class="my-2 mr-2" width="75">
                                                                <v-scale-transition>
                                                                    <div class="text-xs-center" v-if="active">
                                                                        <v-img
                                                                            alt="taxiCab"
                                                                            :src="
                                                                                '/' + car.image ||
                                                                                '/storage/img/taxi/taxi.jpg'
                                                                            "
                                                                            style="width: 100%"
                                                                        />
                                                                    </div>
                                                                    <div class="text-xs-center" v-else>
                                                                        <v-img
                                                                            alt="taxiCab"
                                                                            :src="
                                                                                '/' + car.image ||
                                                                                '/storage/img/taxi/taxi.jpg'
                                                                            "
                                                                            style="width: 100%; filter: brightness(50%)"
                                                                        />
                                                                    </div>
                                                                </v-scale-transition>
                                                                <div class="mx-1 text-center small">
                                                                    {{ car.class_name || car.name }}
                                                                </div>
                                                            </v-card>
                                                        </v-slide-item>
                                                    </v-slide-group>
                                                </v-sheet>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-col>

                            <v-col cols="12" lg="5" md="5" class="ma-0 pa-0">
                                <div :id="'order-map'" style="width: 100%" :style="{ height: contentHeight + 'px' }" />
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>

                <div
                    v-else-if="order.client_id && client.in_order"
                    class="d-flex justify-center align-center"
                    style="height: 100%"
                >
                    <v-alert max-width="600" outlined dense type="error">
                        Создать новый заказ невозможно, поскольку у клиента есть активный заказ
                    </v-alert>
                </div>
                <div v-else class="d-flex justify-center align-center" style="height: 100%">
                    <v-alert
                        max-width="600"
                        outlined
                        dense
                        type="info"
                        v-text="'Сначала выберите или создадите нового клиента'"
                    />
                </div>
            </div>
        </v-card-text>

        <v-divider v-if="order.client_id" class="my-0" />

        <v-card-actions v-if="order.client_id">
            <div class="d-flex text-h6 font-weight-light" style="width: 400px">
                <span class="mr-2">Цена:</span>
                <div v-if="priceLoading">
                    <v-progress-circular size="32" width="2" class="mx-4" indeterminate color="grey lighten-1" />
                </div>
                <span v-else class="font-weight-regular">
                    {{ order.price ? "₽" + new Intl.NumberFormat("de-DE").format(order.price) : "не установлена" }}
                </span>
            </div>

            <div style="width: 100%" class="d-flex justify-end">
                <div class="d-flex flex-column">
                    <small>*отмеченные поля обязательны</small>
                    <v-btn
                        :disabled="priceLoading || !order.address_from"
                        color="primary"
                        depressed
                        class="rounded-2"
                        :loading="loading"
                        @click="makeOrder()"
                    >
                        Создать заказ
                    </v-btn>
                </div>
            </div>
        </v-card-actions>

        <passenger-dialog
            :dialog="passengerDialog"
            :order="order"
            :passenger="passenger"
            @close="passengerDialog = false"
        />
    </v-card>
</template>
<script lang="js" src="./OrderForm.main.js" />
