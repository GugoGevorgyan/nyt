<!-- @format -->

<template>
    <v-card v-if="order" max-height="650" class="border-lg overflow-hidden">
        <v-card-title class="grey lighten-5">
            <small>
                Номер:
                <b>{{ order.order_id }}</b>
            </small>
            <v-divider vertical class="ml-3 mr-3" />
            <small class="text--accent-1 font-weight-regular text-decoration-underline">
                {{ order.address_from | formatAdd }}
            </small>
            <v-spacer />
            <v-btn icon color="grey" @click="$emit('close')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider class="ma-0" />
        <v-card-text>
            <v-alert
                v-if="resultMsg"
                v-model="resultMsg"
                v-text="resultMsg"
                dense
                class="mt-1"
                border="left"
                type="warning"
                text
            />

            <v-row class="mt-5">
                <v-col cols="12" md="9" lg="9">
                    <v-progress-linear
                        v-if="loadDriver"
                        :active="loadDriver"
                        color="secondary"
                        height="2"
                        indeterminate
                    />
                    <v-simple-table dense :style="{ height: height + 'px' }" style="overflow-y: auto" class="pa-0">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left"> Водитель </th>
                                    <th class="text-left"> Автомобиль </th>
                                    <th class="text-left"> Телефон </th>
                                    <th class="text-left"> Радиус </th>
                                    <th class="text-left"> Состояние </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="driver in drivers"
                                    @click="clickRow(driver)"
                                    :id="'row-' + driver.driver.driver_id"
                                    class="pointer"
                                >
                                    <td v-text="driver.driver.name" />
                                    <td v-text="driver.car.mark + driver.car.model" />
                                    <td v-text="driver.driver.phone" />
                                    <td v-text="driver.radius + 'KM' || '?'" />
                                    <td v-text="driver.driver.status.text || '?'" />
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>

                <v-col cols="12" md="3" lg="3">
                    <v-btn
                        tile
                        :disabled="!drivers.length"
                        v-text="'Отправить список'"
                        class="mb-3"
                        text
                        color="secondary"
                        @click="sendDriverList"
                        :loading="sendListLoading"
                    />

                    <div>
                        <small v-text="'Время заказа'" />
                        <div class="d-flex row" style="margin-left: -3px">
                            <el-date-picker
                                disabled
                                v-model="orderDate"
                                :value="orderInitialDate"
                                type="datetime"
                                placeholder="Датa и время"
                                format="HH:mm dd-MM-yyyy"
                                popper-class="orderTimePicker"
                                :clearable="false"
                                class="mb-5 mt-2"
                                style="width: 178px"
                            />
                            <v-tooltip left>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-bind="attrs" v-on="on">
                                        <v-checkbox disabled class="mt-3 ml-2" v-model="justNow" dense hide-details />
                                    </div>
                                </template>
                                <span v-text="'сейчас (Автоматический поиск)'" />
                            </v-tooltip>
                        </div>
                    </div>

                    <small v-text="'Водители'" />
                    <v-select
                        :menu-props="{ bottom: true, offsetY: true }"
                        hide-details
                        dense
                        :disabled="!filtersDriver"
                        :items="filtersDriver"
                        v-model="type"
                        item-text="text"
                        item-value="value"
                        class="mb-3"
                    />

                    <small v-text="'Радиус'" />
                    <v-select
                        :menu-props="{ bottom: true, offsetY: true }"
                        hide-details
                        dense
                        :disabled="!filtersDistance"
                        :items="filtersDistance"
                        v-model="radius"
                        item-text="text"
                        item-value="value"
                        clearable
                    />

                    <div class="mt-5 text-center">
                        <v-btn
                            tile
                            :disabled="validationSend"
                            v-text="'Прикрепить'"
                            color="primary"
                            text
                            :loading="sendPinLoading"
                            @click="changeOrder"
                        />
                    </div>

                    <v-card
                        v-if="Object.keys(selectedDriver).length"
                        outlined
                        tile
                        height="150px"
                        class="mt-5"
                        style="border-color: orangered"
                    >
                        <v-card-text style="overflow-y: auto" :style="{ height: 120 + 'px' }">
                            <v-row>
                                <v-col cols="12" md="8" lg="8">
                                    <small>{{ selectedDriver.driver.name }}</small>
                                    <small>{{ selectedDriver.car.mark }}</small>
                                    <small>{{ selectedDriver.car.model }}</small>
                                    <small>{{ selectedDriver.car.year }}</small>
                                    <small>{{ selectedDriver.driver.status.text }}</small>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script src="./OrderActions.main.js" />
