<!-- @format -->

<template>
    <v-card elevation="4" max-height="600" max-width="1000" class="border-lg">
        <v-progress-linear
            :active="order.loadDialogData"
            :indeterminate="order.loadDialogData"
            absolute
            color="yellow darken-3"
            class="mb-0"
            height="2"
            top
        />
        <v-overlay v-if="!order.status" :absolute="true" :value="true">
            <v-layout align-center>
                <v-flex>
                    <h1>{{ order.message }}</h1>
                </v-flex>
                <v-flex>
                    <v-btn @click="cancel" icon>
                        <v-icon v-text="'mdi-close'" />
                    </v-btn>
                </v-flex>
            </v-layout>
        </v-overlay>
        <v-system-bar class="grey lighten-5 border-lg" v-if="employee" style="height: 40px">
            <span v-text="'Создать заказ для: '" />
            <b class="text-decoration-underline" v-if="employee.client">
                {{ " " + employee.client.patronymic }} {{ employee.client.name }} {{ employee.client.surname }}
            </b>
            <v-spacer />
            <v-btn @click="cancel" icon>
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-system-bar>
        <v-divider class="mb-1" />

        <v-card-text class="pa-0 ma-0">
            <v-container grid-list-md>
                <v-form autocomplete="off">
                    <v-layout row>
                        <v-radio-group hide-details v-model="order.is_rent" :row="true" class="mt-0 pa-0" dense>
                            <v-radio color="grey darken-3" label="Заказ" :value="false" />

                            <v-radio color="grey darken-3" label="Аренда" :value="true" />
                        </v-radio-group>

                        <v-menu
                            :close-on-click="false"
                            :close-on-content-click="false"
                            nudge-right="20"
                            nudge-top="10"
                            offset-x
                        >
                            <template v-slot:activator="{ on }">
                                <v-card class="pa-0 border ml-5" elevation="0">
                                    <v-layout style="margin-top: -18px">
                                        <v-card-actions>
                                            <el-date-picker
                                                v-model="order.time.time"
                                                type="datetime"
                                                placeholder="Датa и время"
                                                format="yyyy-MM-dd HH:mm"
                                                :picker-options="datePickerOptions"
                                                popper-class="orderTimePicker"
                                                :clearable="false"
                                            />

                                            <v-btn small icon class="ml-2">
                                                <v-icon color="grey darken-3" v-text="'mdi-check'" />
                                            </v-btn>
                                            <v-btn small @click="close" icon>
                                                <v-icon color="red darken-5" v-text="'mdi-window-close'" />
                                            </v-btn>
                                        </v-card-actions>
                                    </v-layout>
                                </v-card>
                            </template>
                        </v-menu>
                    </v-layout>

                    <v-divider class="mb-2" />

                    <v-layout>
                        <v-flex
                            :lg="order.is_rent ? 9 : 6"
                            :md="order.is_rent ? 9 : 6"
                            :sm="order.is_rent ? 9 : 6"
                            :xl="order.is_rent ? 9 : 6"
                            :xs="order.is_rent ? 9 : 6"
                        >
                            <v-text-field
                                :error-messages="errors.collect('order.address_from')"
                                :value="order.route.from_address"
                                @click:clear="clearFromField"
                                append-inner-icon="mdi-calendar-range"
                                background-color="grey lighten-4"
                                clearable
                                color="yellow darken-3"
                                data-vv-as="откуда"
                                dense
                                id="from"
                                label="Откуда *"
                                name="order.route.from_address"
                                outlined
                                prepend-inner-icon="mdi-map-search-outline"
                                v-validate="order.rules.address_from"
                            >
                                <template v-slot:prepend-inner>
                                    <v-icon
                                        @click="detectFromLocation"
                                        color="yellow darken-3"
                                        v-text="'mdi-map-marker-circle'"
                                    />
                                </template>
                            </v-text-field>
                        </v-flex>

                        <v-btn v-if="!order.is_rent" @click="switchFromTo" icon small style="margin-top: 10px">
                            <v-icon>mdi-swap-horizontal</v-icon>
                        </v-btn>

                        <v-flex
                            :lg="order.is_rent ? 3 : 6"
                            :md="order.is_rent ? 3 : 6"
                            :sm="order.is_rent ? 3 : 6"
                            :xl="order.is_rent ? 3 : 6"
                            :xs="order.is_rent ? 3 : 6"
                        >
                            <v-text-field
                                v-if="!order.is_rent"
                                :error-messages="errors.collect('order.route.to_address')"
                                :value="order.route.to_address"
                                @click:clear="clearToField()"
                                background-color="grey lighten-4"
                                clearable
                                color="yellow darken-3"
                                dense
                                id="to"
                                label="Куда"
                                name="order.route.to_address"
                                outlined
                                prepend-inner-icon="mdi-map-search-outline"
                            />
                            <v-select
                                v-else
                                dense
                                outlined
                                :items="order.rentTimes"
                                v-model="order.rent_time"
                                item-text="name"
                                item-value="id"
                                placeholder="Часы"
                                class="pt-0"
                                background-color="grey lighten-4"
                            />
                        </v-flex>
                    </v-layout>

                    <v-layout>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-text-field
                                v-if="employee.client"
                                :error-messages="errors.collect('order.phone.client')"
                                :value="employee.client.phone"
                                background-color="grey lighten-4"
                                color="yellow darken-3"
                                data-vv-as="номер телефона"
                                dense
                                disabled
                                hint="phone number to contact with you"
                                label="Номер телефона *"
                                name="order.phone.client"
                                outlined
                                type="text"
                                v-mask="'+#(###)-###-##-##'"
                                v-model="order.phone.client"
                                v-validate="'required'"
                            />
                        </v-flex>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-select
                                :items="order.carOptionValues"
                                background-color="grey lighten-4"
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
                                v-model="order.car.options"
                            >
                                <template v-slot:selection="{ item, index }">
                                    <span v-if="0 === index" v-text="'Выбрано '"></span>
                                    <v-chip small v-if="0 === index">{{ order.car.options.length }}</v-chip>
                                </template>
                            </v-select>
                        </v-flex>
                    </v-layout>

                    <v-divider />
                    <small>*indicates required field</small>

                    <v-layout align-content-start>
                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <h3 class="text-center">Класс Автомобиля</h3>
                            <v-sheet class="mb-1" max-width="495">
                                <v-slide-group :show-arrows="false" mandatory v-model="order.car.class">
                                    <v-slide-item
                                        v-for="(carClass, index) in order.carClassValues"
                                        :key="index"
                                        :value="carClass.class_id"
                                        v-slot:default="{ active, toggle }"
                                    >
                                        <v-card
                                            :color="active ? 'BFC9C9C9' : 'BFEAEAEA'"
                                            @click="toggle"
                                            class="ma-3"
                                            elevation="0"
                                            height="50"
                                            width="80"
                                        >
                                            <v-layout align-center fill-height justify-center>
                                                <p class="text--accent-1" style="font-size: small">
                                                    {{ carClass.name.charAt(0) }}
                                                </p>
                                                <v-scale-transition>
                                                    <div class="text-xs-center" color="grey lighten-2" v-if="active">
                                                        <img
                                                            :src="`/${carClass.image}`"
                                                            alt="taxiCab"
                                                            style="height: 100%; width: 100%"
                                                        />
                                                    </div>
                                                    <div class="text-xs-center" style="opacity: 0.3" v-else>
                                                        <img
                                                            :src="`/${carClass.image}`"
                                                            alt="taxiCab"
                                                            style="height: 100%; width: 100%"
                                                        />
                                                    </div>
                                                </v-scale-transition>
                                            </v-layout>
                                            <p class="text--accent-1" style="font-size: 13px">
                                                {{ carClass.coin }} {{ carClass.currency }}
                                            </p>
                                        </v-card>
                                    </v-slide-item>
                                </v-slide-group>
                            </v-sheet>
                        </v-flex>

                        <v-divider class="mx-4 mt-0" inset vertical />

                        <v-flex lg6 md6 sm6 xl6 xs6>
                            <v-textarea
                                class="mb-0"
                                background-color="grey lighten-4"
                                color="yellow darken-3"
                                dense
                                label="Комментарии"
                                rows="3"
                                single-line
                                v-model="order.car.comment"
                            />
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-container>
            <v-divider />
        </v-card-text>
        <v-card-actions>
            <p class="text-justify font-weight-medium ma-0" v-text="order.priceText"></p>
            <v-layout>
                <v-flex lg12 md12 sm12 xl12 xs12>
                    <v-progress-circular
                        :indeterminate="order.pricePending"
                        :rotate="0"
                        :size="25"
                        :value="0"
                        :width="2"
                        class="ml-1"
                        color="yellow darken-3"
                        v-show="order.pricePending"
                    />
                </v-flex>
            </v-layout>

            <v-spacer />
            <v-divider class="mx-3 mt-0" vertical />
            <v-btn
                :loading="order.loading"
                class="rounded-2"
                light
                :disabled="order.loading"
                @click="createOrder"
                width="120px"
                color="yellow darken-2"
                depressed
                v-text="!order.is_rent ? 'ЗАКАЗАТЬ' : 'АРЕНДОВАТЬ'"
            />
        </v-card-actions>
    </v-card>
</template>

<script lang="js" src="./OrderDialog.main.js" />
