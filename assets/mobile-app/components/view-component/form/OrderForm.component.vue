<!-- @format -->

<template>
    <div>
        <accept :dialog="acceptDialog" />
        <v-layout class="mt-3 elevation-11" column wrap>
            <v-form autocomplete="off" class="mobile-form" :style="{ 'margin-top': window.height - 250 + 'px' }">
                <v-progress-linear v-if="mainLoader" height="2" indeterminate color="yellow darken-3" />
                <v-flex lg12 md12 sm12 xl12 xs12 class="pa-2">
                    <v-flex lg12 md12 sm12 xl12 xs12 class="from-mobile-input-height">
                        <v-text-field
                            :error-messages="errors.collect('from')"
                            :value="order.address_from"
                            :disabled="fromDisable"
                            @change="changeFrom"
                            @click="createSuggestFrom"
                            @click:clear="clearFromField()"
                            @input="subscribeFromInput"
                            append-inner-icon="mdi-calendar-range"
                            clearable
                            color="yellow darken-3"
                            data-vv-as="откуда"
                            dense
                            id="from"
                            label="Откуда *"
                            name="from"
                            outlined
                            v-validate="'required'"
                            class="mobile-input"
                        >
                            <template v-slot:prepend-inner>
                                <v-icon
                                    @click="detectFromLocation"
                                    color="red darken-3"
                                    v-text="'mdi-map-marker-circle'"
                                />
                            </template>
                        </v-text-field>
                    </v-flex>
                    <v-window v-model="orderType" :touchless="false" continuous @change="orderRentToggle">
                        <v-btn small text @click="orderRentToggle" class="order-rent-toggle-btn">
                            <v-icon small v-text="'mdi-arrow-left'" class="float-left" />
                            {{ rentOrderTitle }}
                            <v-icon small v-text="'mdi-arrow-right'" class="float-right" />
                        </v-btn>
                        <v-flex lg12 md12 sm12 xl12 xs12 style="height: 50px">
                            <v-window-item :value="1">
                                <v-text-field
                                    :value="order.address_to"
                                    :disabled="toDisable"
                                    @change="changeTo"
                                    @click="createSuggestTo"
                                    @click:clear="clearToField()"
                                    @input="subscribeToInput"
                                    append-inner-icon="mdi-calendar-range"
                                    clearable
                                    color="yellow darken-3"
                                    dense
                                    id="to"
                                    label="Куда"
                                    name="to"
                                    outlined
                                    class="mobile-input"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon color="blue darken-3" v-text="'mdi-map-marker-radius-outline'" />
                                    </template>
                                </v-text-field>
                            </v-window-item>

                            <v-window-item :value="2">
                                <v-select
                                    v-model="rentTime"
                                    :items="rentTimes"
                                    item-text="name"
                                    item-value="id"
                                    outlined
                                    dense
                                    class="mobile-input"
                                    color="yellow darken-3"
                                    label="Аренда (час)"
                                />
                            </v-window-item>
                        </v-flex>
                    </v-window>

                    <v-sheet :max-width="window.width - 28" class="mb-1 mx-auto" v-if="carClasses">
                        <v-slide-group
                            prev-icon="mdi-minus"
                            next-icon="mdi-plus"
                            style="height: 85px"
                            v-model="order.car_class_id"
                            mandatory
                        >
                            <v-slide-item
                                v-for="carClass in carClassess"
                                :value="carClass.car_class_id"
                                :key="carClass.car_class_id"
                                v-slot:default="{ active, toggle }"
                            >
                                <v-card
                                    elevation="0"
                                    :color="active ? 'BFC9C9C9' : 'BFEAEAEA'"
                                    @click="toggle"
                                    class="ma-3"
                                    height="50"
                                    width="80"
                                >
                                    <v-layout align-center fill-height justify-center>
                                        <p v-if="carClass.class_name" class="text--accent-1" style="font-size: small">
                                            {{ carClass.class_name.charAt(0) }}
                                        </p>
                                        <p v-else class="text--accent-1" style="font-size: small">
                                            {{ carClass.name.charAt(0) }}
                                        </p>
                                        <v-scale-transition>
                                            <div class="text-xs-center" color="grey lighten-2" v-if="active">
                                                <img
                                                    :src="carClass.image"
                                                    alt="taxiCab"
                                                    style="height: 100%; width: 100%"
                                                />
                                            </div>
                                            <div class="text-xs-center" style="opacity: 0.3" v-else>
                                                <img
                                                    :src="carClass.image"
                                                    alt="taxiCab"
                                                    style="height: 100%; width: 100%"
                                                />
                                            </div>
                                        </v-scale-transition>
                                    </v-layout>
                                    <v-skeleton-loader class="mt-1" v-if="mainLoader" type="text" />
                                    <p v-else class="text--accent-1" style="font-size: small">
                                        {{ carClass.coin }} {{ carClass.currency }}
                                    </p>
                                </v-card>
                            </v-slide-item>
                            <v-layout v-if="!carClasses.length" fill-height justify-center align-content-end>
                                <v-flex col-6 offset-3 align-self-center>
                                    <v-progress-circular :size="50" width="2" color="amber" indeterminate />
                                </v-flex>
                            </v-layout>
                        </v-slide-group>
                    </v-sheet>

                    <v-flex lg12 md12 sm12 xl12 xs12 class="mobile-input-height">
                        <v-select
                            :disabled="!demands.length"
                            v-model="order.car_option"
                            :items="demands"
                            item-text="option"
                            item-value="id"
                            label="Пожелания"
                            multiple
                            outlined
                            dense
                            class="mobile-input"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                        >
                            <template v-slot:selection="{ item, index }">
                                <v-chip v-if="0 === index" small>
                                    <span>{{ item.option }}</span>
                                </v-chip>
                                <span v-if="1 === index" class="grey--text caption">
                                    (+{{ order.car_option.length - 1 }} других)
                                </span>
                            </template>
                        </v-select>
                    </v-flex>
                    <v-flex lg12 md12 sm12 xl12 xs12 class="mobile-input-height">
                        <v-select
                            :error-messages="errors.collect('payment_type')"
                            :items="paymentMethods"
                            color="yellow darken-3"
                            dense
                            item-color="yellow darken-3"
                            item-text="name"
                            item-value="id"
                            label="Способ оплаты *"
                            name="payment_type"
                            open-on-clear
                            outlined
                            ref="payment_type"
                            v-model="order.payment_type"
                            v-validate="'required'"
                            class="mobile-input"
                        />
                    </v-flex>
                    <v-flex
                        lg12
                        md12
                        sm12
                        v-if="this.client.companies && 2 === order.payment_type"
                        xl12
                        xs12
                        class="mobile-input-height"
                    >
                        <v-select
                            :error-messages="errors.collect('payment.Company')"
                            :items="this.client.companies"
                            color="yellow darken-3"
                            data-vv-as="Имя Компании"
                            dense
                            flat
                            item-color="yellow darken-3"
                            item-text="name"
                            item-value="company_id"
                            label="Выберите Компанию *"
                            name="payment.company"
                            open-on-clear
                            outlined
                            v-model="order.payment_type_company"
                            v-validate="'required_if:payment_type,2'"
                            class="mobile-input"
                        />
                    </v-flex>
                </v-flex>

                <v-flex class="pa-3 bg-yellow">
                    <v-divider />
                    <v-layout>
                        <v-flex lg12 md12 sm12 xl12 xs12>
                            <span class="text-justify font-weight-medium ma-0" v-text="orderForm.priceText" />
                            <p
                                v-if="orderForm.distance"
                                class="text-justify font-weight-medium ma-0"
                                v-text="`Расстояние: ${orderForm.distance} КМ`"
                            />
                            <p
                                v-if="orderForm.time"
                                class="text-justify font-weight-medium ma-0"
                                v-text="`Продолжительность: ${orderForm.time} Мин `"
                            />
                            <v-progress-circular
                                :indeterminate="orderForm.pricePending"
                                :rotate="0"
                                :size="20"
                                :value="0"
                                :width="2"
                                class="ml-1 mobile-input"
                                color="yellow darken-3"
                                v-show="orderForm.pricePending"
                            />
                        </v-flex>
                    </v-layout>
                    <v-divider />
                </v-flex>

                <v-flex class="pa-3">
                    <v-text-field
                        :disabled="disabledPhone"
                        :error-messages="errors.collect('phone')"
                        @dblclick="this.disabledPhone = !this.disabledPhone"
                        color="yellow darken-3"
                        data-vv-as="Телефон"
                        dense
                        label="Телефон *"
                        name="phone"
                        placeholder="+7(995)-999-99-99"
                        type="tel"
                        v-mask="orderForm.phoneMask"
                        v-model="client.phone"
                        v-validate="'required'"
                        class="mobile-input"
                    >
                        <template v-slot:append>
                            <v-icon v-if="disabledPhone" color="green darken-2">mdi-account-check</v-icon>
                            <v-icon v-else color="red darken-2">mdi-account-check</v-icon>
                        </template>
                    </v-text-field>
                    <v-btn
                        :disabled="errors.any() || !validateDisabledButton || mainLoader"
                        color="yellow darken-1"
                        @click="makeOrder"
                        block
                        tile
                        :class="validateDisabledButton ? 'btn-hover color-2 mb-1' : 'mb-1'"
                        v-text="'Заказать'"
                    />
                </v-flex>
            </v-form>
        </v-layout>
    </div>
</template>

<script lang="js" src="./OrderFormComponent.main.js" />
<style lang="scss" src="./OrderForm.style.scss" scoped />
