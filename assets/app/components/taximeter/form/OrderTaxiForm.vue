<!-- @format -->

<template>
    <v-card elevation="4" max-height="770" style="opacity: 0.9; border-radius: 8px" width="900">
        <v-container class="pa-0" grid-list-sm>
            <v-layout fill-height row>
                <v-card-title style="line-height: 1rem" v-text="'Форма Заказа'" />

                <v-spacer />

                <v-icon
                    color="yellow darken-3"
                    style="right: 15px"
                    @click="toggleOrderForm"
                    v-text="'mdi-arrow-left-bold-outline'"
                />
            </v-layout>
        </v-container>

        <v-divider />

        <v-card-text class="pa-0">
            <v-container grid-list-md>
                <v-form autocomplete="off">
                    <v-layout>
                        <v-flex
                            :lg="isRent ? 12 : 6"
                            :md="isRent ? 12 : 6"
                            :sm="isRent ? 12 : 6"
                            :xl="isRent ? 12 : 6"
                            :xs="isRent ? 12 : 6"
                        >
                            <v-text-field
                                id="from"
                                class="rounded-3"
                                v-validate="'required'"
                                :error-messages="errors.collect('order_address_from')"
                                v-model="orderForm.displayFrom"
                                append-inner-icon="mdi-calendar-range"
                                background-color="grey lighten-5"
                                color="yellow darken-3"
                                data-vv-as="откуда"
                                dense
                                label="Откуда *"
                                name="order_address_from"
                                outlined
                                clearable
                                @click:clear="clearFromField()"
                            >
                                <template v-slot:prepend>
                                    <v-icon
                                        color="red darken-3"
                                        @click="detectFromLocation"
                                        v-text="'mdi-map-marker-circle'"
                                    />
                                </template>
                                <template v-slot:prepend-inner>
                                    <v-btn icon small @click="openTransportMenu('from')">
                                        <v-icon class="mb-1" color="yellow darken-3" v-text="'mdi-menu'" />
                                    </v-btn>
                                </template>
                            </v-text-field>
                        </v-flex>

                        <template v-if="!isRent">
                            <v-btn icon small style="margin-top: 10px" @click="switchFromTo">
                                <v-icon v-text="'mdi-swap-horizontal'" />
                            </v-btn>

                            <v-flex>
                                <v-text-field
                                    id="to"
                                    class="rounded-3"
                                    :error-messages="errors.collect('order_address_to')"
                                    v-model="orderForm.displayTo"
                                    background-color="grey lighten-5"
                                    color="yellow darken-3"
                                    dense
                                    label="Куда"
                                    name="order_address_to"
                                    outlined
                                    clearable
                                    prepend-inner-icon="mdi-map-search-outline"
                                    @click:clear="clearToField()"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-btn icon small @click="openTransportMenu('to')">
                                            <v-icon class="mb-1" color="yellow darken-3" v-text="'mdi-menu'" />
                                        </v-btn>
                                    </template>
                                </v-text-field>
                            </v-flex>
                        </template>
                    </v-layout>

                    <v-layout>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-text-field
                                class="rounded-3"
                                :value="getUsingPhoneAccordinglyPhoneMask(client.phone)"
                                v-mask="orderForm.phoneMask"
                                v-validate="'required'"
                                :disabled="disabledPhone"
                                :error-messages="errors.collect('order_phone')"
                                background-color="grey lighten-5"
                                clearable
                                color="yellow darken-3"
                                data-vv-as="номер телефона"
                                dense
                                hint="номер телефона для связи с вами"
                                label="Номер телефона *"
                                name="order_phone"
                                outlined
                                type="text"
                                @dblclick="this.disabledPhone = !this.disabledPhone"
                            />
                        </v-flex>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-select
                                :disabled="!orderForm.demands.length"
                                class="rounded-3"
                                v-model="carOptionPrice"
                                :items="orderForm.demands"
                                background-color="grey lighten-5"
                                color="yellow darken-3"
                                dense
                                hide-details
                                item-color="yellow darken-3"
                                item-text="option"
                                item-value="id"
                                label="Требования к автомобилю"
                                multiple
                                open-on-clear
                                outlined
                            >
                                <template v-slot:selection="{ item, index }">
                                    <span v-if="0 === index" v-text="'Выбрано '"></span>
                                    <v-chip v-if="0 === index" small>{{ order.car_option.length }}</v-chip>
                                </template>
                            </v-select>
                        </v-flex>
                    </v-layout>

                    <v-layout>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-select
                                class="rounded-3"
                                ref="payment_type"
                                v-model="order.payment_type"
                                v-validate="'required'"
                                :error-messages="errors.collect('payment_type')"
                                :items="orderForm.paymentMethods"
                                background-color="grey lighten-5"
                                color="yellow darken-3"
                                dense
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="id"
                                label="Способ оплаты *"
                                name="payment_type"
                                open-on-clear
                                outlined
                            />
                        </v-flex>

                        <v-flex lg6 md6 sm6 xl6 xs6 class="d-flex">
                            <v-radio-group v-model="isRent" :row="true" class="mt-0 pa-0" dense>
                                <v-radio label="Заказ" :value="false" />

                                <v-radio label="Аренда" @click="clearToField" :value="true" />
                            </v-radio-group>

                            <v-spacer v-if="isRent && rentTime" />

                            <v-select
                                v-if="isRent && rentTime"
                                v-model="rentTime"
                                :items="rentTimes"
                                item-text="name"
                                item-value="id"
                                placeholder="Часы"
                                class="pt-0 rounded-3"
                                color="yellow darken-3"
                                background-color="grey lighten-5"
                                outlined
                                dense
                                style="max-width: 120px"
                            />
                            <p v-else-if="isRent && !rentTime" v-text="'Rent Not supported'" />
                        </v-flex>

                        <v-flex
                            v-if="
                                (this.client.companies && 2 === order.payment_type) ||
                                (paymentCards.length && !paymentDialog && order.payment_type === PAYMENT_TYPE.CARD)
                            "
                            :lg="isRent ? 4 : 6"
                            :md="isRent ? 4 : 6"
                            :sm="isRent ? 4 : 6"
                            :xl="isRent ? 4 : 6"
                            :xs="isRent ? 4 : 6"
                        >
                            <v-select
                                class="rounded-3"
                                v-if="this.client.companies && PAYMENT_TYPE.COMPANY === order.payment_type"
                                ref="payment_type_company"
                                v-model="order.payment_type_company"
                                v-validate="'required_if:payment_type,2'"
                                :error-messages="errors.collect('payment.company')"
                                :items="client.companies"
                                background-color="grey lighten-5"
                                color="yellow darken-3"
                                data-vv-as="Company Name"
                                dense
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="id"
                                label="Выберите компанию *"
                                name="payment.company"
                                open-on-clear
                                outlined
                            >
                                <template v-slot:selection="data">
                                    <v-img v-if="data.item.logo" :src="data.item.logo" />
                                    <span v-text="data.item.name" />
                                </template>
                            </v-select>

                            <v-select
                                v-if="paymentCards.length && !paymentDialog && order.payment_type === PAYMENT_TYPE.CARD"
                                class="rounded-3"
                                ref="payment_type_company"
                                v-model="order.payment_type_card"
                                v-validate="'required_if:payment_type,3'"
                                :error-messages="errors.collect('payment.card')"
                                :items="client.pay_cards"
                                background-color="grey lighten-5"
                                color="yellow darken-3"
                                data-vv-as="Company Name"
                                dense
                                item-color="yellow darken-3"
                                item-text="number"
                                item-value="id"
                                label="Выберите карточку *"
                                name="payment.card"
                                open-on-clear
                                outlined
                            >
                                <template v-slot:selection="{ item }">
                                    <span>{{ item.number | VMask("NNNN-NNNN-NNNN-NNNNNNN") }}</span>
                                </template>
                                <template v-slot:prepend-item>
                                    <v-list-item ripple class="hover-list pointer">
                                        <v-list-item-action>
                                            <v-icon color="yellow darken-3" v-text="'mdi-plus'" />
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                <span
                                                    class="orange--text"
                                                    @click="paymentDialog = true"
                                                    v-text="'Добавить карточку'"
                                                />
                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider class="mt-2" />
                                </template>
                            </v-select>
                        </v-flex>
                    </v-layout>

                    <v-divider />
                    <small>*указывает обязательное поле</small>

                    <v-layout>
                        <v-flex lg8 md8 sm8 xl8 xs8>
                            <h3 class="text-center">Класс Автомобиля</h3>
                            <v-sheet v-if="orderForm.carClasses.length" class="mb-1" max-width="495">
                                <v-slide-group v-model="carClassPrice" :show-arrows="false" mandatory>
                                    <v-slide-item
                                        v-for="carClass in orderForm.carClasses"
                                        :key="carClass.car_class_id || carClass.class_id"
                                        v-slot:default="{ active, toggle }"
                                        :value="carClass.car_class_id || carClass.class_id"
                                    >
                                        <v-card
                                            :color="active ? 'BFC9C9C9' : 'BFEAEAEA'"
                                            class="ma-4"
                                            elevation="0"
                                            height="50"
                                            width="80"
                                            @click="toggle"
                                        >
                                            <v-layout align-center fill-height justify-center>
                                                <p
                                                    v-if="carClass.class_name"
                                                    class="text--accent-1"
                                                    style="font-size: small"
                                                >
                                                    {{ carClass.class_name.charAt(0) }}
                                                </p>
                                                <p v-else class="text--accent-1" style="font-size: small">
                                                    {{ carClass.name.charAt(0) }}
                                                </p>
                                                <v-scale-transition>
                                                    <div v-if="active" class="text-xs-center">
                                                        <img
                                                            :src="carClass.image"
                                                            alt="taxiCab"
                                                            style="height: 100%; width: 100%"
                                                        />
                                                    </div>
                                                    <div v-else class="text-xs-center" style="opacity: 0.2">
                                                        <img
                                                            :src="carClass.image"
                                                            alt="taxiCab"
                                                            style="height: 100%; width: 100%"
                                                        />
                                                    </div>
                                                </v-scale-transition>
                                            </v-layout>
                                            <v-skeleton-loader class="mt-2" v-if="calcCoin" type="text" />
                                            <p v-else class="text--accent-1 car-price-text" style="font-size: small">
                                                <span v-if="!order.address_to_coordinates.length">
                                                    От {{ carClass.coin }} {{ carClass.currency }}
                                                </span>
                                                <span v-else> {{ carClass.coin }} {{ carClass.currency }} </span>
                                            </p>
                                        </v-card>
                                    </v-slide-item>
                                </v-slide-group>
                            </v-sheet>
                            <v-skeleton-loader
                                v-else
                                class="mt-2 d-flex align-start justify-start"
                                type="actions, actions, actions"
                            />

                            <v-divider />

                            <v-layout align-center row>
                                <v-flex lg4 md4 sm4 xl4 xs4>
                                    <v-checkbox
                                        v-model="imPassenger"
                                        :label="`Для меня`"
                                        color="yellow darken-3"
                                        dense
                                    />
                                </v-flex>

                                <v-flex lg4 md4 sm4 xl4 xs4>
                                    <v-menu v-model="repeatOrderMenu" :close-on-content-click="false">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" color="grey darken-1" small text v-text="'Повторение'" />
                                        </template>

                                        <v-card>
                                            <v-date-picker
                                                v-model="repeatOrder"
                                                class="elevation-0"
                                                color="yellow darken-3"
                                                multiple
                                                scrollable
                                            />

                                            <v-divider />

                                            <v-card-actions>
                                                <v-spacer />
                                                <v-btn text @click="closeRepeatOrder">cansel</v-btn>
                                                <v-btn color="yellow darken-3" dark @click="repeatOrderMenu = false">
                                                    accept
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-menu>
                                </v-flex>

                                <v-flex lg4 md4 sm4 xl4 xs4>
                                    <v-btn small text @click="comment = !comment" v-text="'Комментарии'" />
                                </v-flex>
                            </v-layout>

                            <v-layout>
                                <v-flex lg6 md6 sm6 xl6 xs6>
                                    <v-text-field
                                        v-if="!imPassenger"
                                        v-model="order.passenger_phone"
                                        v-mask="orderForm.phoneMask"
                                        color="yellow darken-3"
                                        dense
                                        label="Телефон посажира"
                                        outlined
                                    />
                                </v-flex>

                                <v-flex lg6 md6 sm6 xl6 xs6>
                                    <v-textarea
                                        v-if="comment"
                                        v-model="order.comment"
                                        color="yellow darken-3"
                                        dense
                                        label="Комментарии"
                                        outlined
                                        rows="1"
                                    />
                                </v-flex>
                            </v-layout>
                        </v-flex>

                        <v-flex class="pl-5" lg4 md4 sm4 xl4 xs4>
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-bind="attrs" v-on="on">
                                        <PreOrder :preorder="preorder" :orderFormOpened='orderForm.open' />
                                    </div>
                                </template>
                                <span v-if="preorder" v-text="'лимит ПЗ. исчерпан'" />
                            </v-tooltip>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-container>
        </v-card-text>

        <v-divider />

        <v-card-actions>
            <p
                :class="this.order.address_from ? 'price-text' : ''"
                class="text-justify font-weight-medium ma-0 ml-1"
                v-text="orderForm.priceText"
            />
            <v-layout>
                <v-flex lg12 md12 sm12 xl12 xs12>
                    <v-progress-circular
                        v-show="orderForm.pricePending"
                        :indeterminate="orderForm.pricePending"
                        :rotate="0"
                        :size="25"
                        :value="0"
                        :width="2"
                        class="ml-1"
                        color="yellow darken-3"
                    />
                </v-flex>
            </v-layout>
            <v-spacer />
            <v-btn
                :disabled="errors.any() || !validateDisabledButton || orderForm.pricePending"
                color="yellow darken-3"
                outlined
                @click="makeOrder"
                class="mr-1 rounded-2"
                width="110"
                v-text="'Заказать'"
            />
        </v-card-actions>
    </v-card>
</template>

<script src="./OrderTaxiForm.main.js" />
<style lang="scss" scoped src="./OrderTaxiForm.style.scss" />
