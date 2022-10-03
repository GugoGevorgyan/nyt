<!-- @format -->

<template>
    <v-container fill-height fluid grid-list-md>
        <v-snackbar v-model="snackbar.display" :color="snackbar.success ? 'green' : 'red'" :right="true" :top="true">
            {{ snackbar.snackbarText }}
        </v-snackbar>
        <v-row>
            <v-col cols="12" md="8">
                <v-card outlined tile width="100%">
                    <v-card-title>{{ tariff.optionTitle ? tariff.optionTitle : "Тип и опция тарифа" }}</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text :style="{ height: window.height + 'px' }" style="overflow-y: auto">
                        <div v-if="tariff.optionComponent" class="py-2">
                            <span v-if="tariff.optionComponent !== 'RentFields'">
                                <component :is="tariff.optionComponent" :tariff="tariff"></component>
                            </span>
                            <span v-else>
                                <component
                                    :is="tariff.optionComponent"
                                    :alternativeTariffs="alternativeTariffs"
                                    :loading="alt_loading"
                                    :tariff="tariff"
                                    @openAlternativeTariffs="displayAltTariffs"
                                ></component>
                            </span>
                        </div>
                        <div v-else class="d-flex justify-center align-center" style="height: 100%">
                            <span class="display-1 text-center"
                                >Для дополнительных настроек выберите тип и(или) опцию тарифа</span
                            >
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card outlined tile width="100%">
                    <v-card-title>Основная информация</v-card-title>
                    <v-divider />
                    <v-card-text :style="{ height: window.height + 'px' }" style="overflow-y: auto">
                        <div class="py-2">
                            <v-select
                                v-model="tariff.tariff_type_id"
                                v-validate="tariff.rules.tariff_type_id"
                                :disabled="optionExists"
                                :eager="true"
                                :error-messages="errors.collect('tariff_type_id')"
                                :items="tariffTypes"
                                color="yellow darken-3"
                                data-vv-as="тип тарифа"
                                dense
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="tariff_type_id"
                                label="Тип тарифа"
                                name="tariff_type_id"
                                outlined
                                persistent-hint
                                placeholder="Выберите тип тарифа"
                                @change="changeTariffType()"
                            />
                            <v-select
                                v-show="!optionsHidden"
                                v-model="tariff.option"
                                v-validate="tariff.rules.option"
                                :disabled="!currentType"
                                :eager="true"
                                :error-messages="errors.collect('option')"
                                :items="tariff.optionsSelectable"
                                color="yellow darken-3"
                                data-vv-as="опция тарифа"
                                dense
                                item-color="yellow darken-3"
                                item-disabled="disabled"
                                item-text="text"
                                item-value="value"
                                label="Опция тарифа"
                                name="option"
                                outlined
                                persistent-hint
                                placeholder="Выберите опцию тарифа"
                            />
                            <v-text-field
                                v-model="tariff.name"
                                v-validate="tariff.rules.name"
                                :error-messages="errors.collect('name')"
                                color="yellow darken-3"
                                data-vv-as="название"
                                dense
                                label="Название"
                                name="name"
                                outlined
                            />
                            <v-switch
                                v-model="tariff.is_default"
                                v-validate="tariff.rules.is_default"
                                :disabled="disableDef"
                                :error-messages="errors.collect('is_default')"
                                :false-value="0"
                                :true-value="1"
                                data-vv-as="дефолтный тариф"
                                dense
                                label="Дефолтный тариф"
                                name="is_default"
                            />
                            <v-switch
                                v-model="tariff.status"
                                v-validate="tariff.rules.status"
                                :error-messages="errors.collect('status')"
                                :false-value="0"
                                :true-value="1"
                                data-vv-as="статус"
                                dense
                                label="Статус"
                                name="status"
                            />
                            <v-switch
                                v-model="tariff.tool_roads_client"
                                v-validate="tariff.rules.tool_roads_client"
                                :error-messages="errors.collect('tool_roads_client')"
                                :false-value="0"
                                :true-value="1"
                                data-vv-as="платные дороги"
                                dense
                                label="Платные дороги за счет клиента"
                                name="tool_roads_client"
                            />
                            <v-switch
                                v-model="tariff.paid_parking_client"
                                v-validate="tariff.rules.paid_parking_client"
                                :error-messages="errors.collect('paid_parking_client')"
                                :false-value="0"
                                :true-value="1"
                                data-vv-as="платные парковки"
                                dense
                                label="Платные парковки  за счет клиента"
                                name="paid_parking_client"
                            />
                            <v-select
                                v-model="tariff.payment_type_id"
                                v-validate="tariff.rules.payment_type_id"
                                :eager="true"
                                :error-messages="errors.collect('payment_type_id')"
                                :items="paymentTypes"
                                color="yellow darken-3"
                                data-vv-as="тип оплаты"
                                dense
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="payment_type_id"
                                label="Тип оплаты"
                                name="payment_type_id"
                                outlined
                                persistent-hint
                                placeholder="Выберите тип оплаты"
                            />
                            <v-select
                                v-model="tariff.car_class_id"
                                v-validate="tariff.rules.car_class_id"
                                :eager="true"
                                :error-messages="errors.collect('car_class_id')"
                                :items="carClasses"
                                color="yellow darken-3"
                                data-vv-as="класс автомобилей"
                                dense
                                item-color="yellow darken-3"
                                item-text="class_name"
                                item-value="car_class_id"
                                label="Класс автомобилей"
                                name="car_class_id"
                                outlined
                                persistent-hint
                                placeholder="Выберите класс автомобилей"
                            />
                            <v-text-field
                                v-model="tariff.minimal_price"
                                v-validate="tariff.rules.minimal_price"
                                :error-messages="errors.collect('minimal_price')"
                                color="yellow darken-3"
                                data-vv-as="минимальная цена"
                                dense
                                label="Минимальная цена"
                                name="minimal_price"
                                outlined
                                type="number"
                            />
                            <v-select
                                v-model="tariff.rounding_price"
                                v-validate="tariff.rules.rounding_price"
                                :error-messages="errors.collect('rounding_price')"
                                :items="rounds"
                                color="yellow darken-3"
                                data-vv-as="цена округления"
                                dense
                                item-text="text"
                                item-value="type"
                                label="Тип округления"
                                name="rounding_price"
                                outlined
                                type="number"
                            />
                            <v-text-field
                                v-model="tariff.free_wait_minutes"
                                v-validate="tariff.rules.free_wait_minutes"
                                :error-messages="errors.collect('free_wait_minutes')"
                                color="yellow darken-3"
                                data-vv-as="количество минут"
                                dense
                                label="Количество минут бесплатного ожидания"
                                name="free_wait_minutes"
                                outlined
                                type="number"
                            />
                            <v-text-field
                                v-model="tariff.paid_wait_minute"
                                v-validate="tariff.rules.paid_wait_minute"
                                :error-messages="errors.collect('paid_wait_minute')"
                                color="yellow darken-3"
                                data-vv-as="цена минуты"
                                dense
                                label="Цена минуты ожидания"
                                name="paid_wait_minute"
                                outlined
                                type="number"
                            />
                            <v-menu
                                ref="date_from_menu"
                                v-model="date_from_menu"
                                :close-on-content-click="false"
                                max-width="290px"
                                min-width="290px"
                                offset-y
                                transition="scale-transition"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="tariff.date_from"
                                        v-on="on"
                                        v-validate="tariff.rules.date_from"
                                        :error-messages="errors.collect('date_from')"
                                        color="yellow darken-3"
                                        dense
                                        hint="MM/DD/YYYY format"
                                        label="Дата начала"
                                        name="date_from"
                                        outlined
                                        prepend-icon="mdi-calendar"
                                        readonly
                                    />
                                </template>
                                <v-date-picker
                                    v-model="tariff.date_from"
                                    :max="tariff.date_to"
                                    no-title
                                    @input="date_from_menu = false"
                                />
                            </v-menu>
                            <v-menu
                                ref="date_to_menu"
                                v-model="date_to_menu"
                                :close-on-content-click="false"
                                max-width="290px"
                                min-width="290px"
                                offset-y
                                transition="scale-transition"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="tariff.date_to"
                                        v-on="on"
                                        v-validate="tariff.rules.date_to"
                                        :error-messages="errors.collect('date_to')"
                                        color="yellow darken-3"
                                        dense
                                        hint="MM/DD/YYYY format"
                                        label="Дата окончания"
                                        name="date_to"
                                        outlined
                                        prepend-icon="mdi-calendar"
                                        readonly
                                    />
                                </template>
                                <v-date-picker
                                    v-model="tariff.date_to"
                                    :min="tariff.date_from"
                                    no-title
                                    @input="date_to_menu = false"
                                >
                                </v-date-picker>
                            </v-menu>
                        </div>
                        <div class="d-flex justify-end">
                            <v-btn
                                :loading="tariff.tariff_id ? tariff.updateLoading : tariff.storeLoading"
                                color="primary"
                                @click="save()"
                            >
                                {{ tariff.tariff_id ? "Обновить" : "Сохранить" }}
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import Tariff from "../../models/Tariff";
import RegionsCitiesFields from "./Fields/RegionsCitiesFields";
import DestinationFields from "./Fields/DestinationsFields";
import RentFields from "./Fields/RentFields";
import FictionalRegionsFields from "./Fields/FictionalRegionsFields";
import FixTimesFields from "./Fields/FixTimesFields";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";

export default {
    props: ["tariffObj", "carClasses", "tariffTypes", "paymentTypes", "rounds"],
    name: "TariffForm",
    components: { FixTimesFields, FictionalRegionsFields, DestinationFields, RegionsCitiesFields, RentFields },
    data() {
        return {
            formComponents: [],

            date_from_menu: false,
            date_to_menu: false,

            tariff: new Tariff(this.tariffObj || {}),
            disableDef: false,
            optionExists: false,
            snackbar: {
                display: false,
                success: false,
                snackbarText: "",
            },

            window: {
                width: 0,
                height: window.innerHeight - 220,
            },
            alternativeTariffs: [],
            alt_loading: false,
        };
    },
    computed: {
        currentType() {
            if (this.tariff.tariff_type_id) {
                return this.tariffTypes.find(item => {
                    return item.tariff_type_id === this.tariff.tariff_type_id;
                });
            }
        },

        optionsHidden() {
            return !!(this.currentType && (this.currentType.type === 4 || this.currentType.type === 5));
        },
    },
    methods: {
        changeTariffType() {
            this.tariff.resetFields();
        },
        checkDisableDef() {
            let payment = this.paymentTypes.find(item => {
                return item.payment_type_id === this.tariff.payment_type_id;
            });
            this.disableDef = payment && payment.type === 2;
            if (this.disableDef && this.tariff.option === "default") {
                this.tariff.option = null;
            }
            if (this.disableDef) {
                this.tariff.is_default = 0;
            }
            this.tariff.optionsSelectable.map(item => {
                item.disabled = item.value === "default" ? this.disableDef : false;
            });
        },

        setTypeOption() {
            if (this.currentType) {
                if (this.currentType.type === 4) {
                    this.tariff.option = "destination";
                } else if (this.currentType.type === 5) {
                    this.tariff.option = "rent";
                } else if (this.currentType.type === 6) {
                    this.tariff.option = "fix_time";
                } else {
                    this.tariff.option = null;
                }
            }
        },

        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 220;
        },

        save() {
            let validation = [];
            this.tariff.components.forEach(i => {
                validation.push(
                    i.$validator.validateAll().then(valid => {
                        return valid;
                    }),
                );
            });
            Promise.all(validation).then(values => {
                if (!values.includes(false)) {
                    this.tariff.tariff_id
                        ? this.tariff
                              .updateTariff()
                              .then(res => {
                                  this.tariff.updateLoading = false;
                                  this.snackbar.success = true;
                                  this.snackbar.display = true;
                                  this.snackbar.snackbarText = res.data.message;
                                  window.location.href = "/admin/super/tariff";
                              })
                              .catch(error => {
                                  this.tariff.updateLoading = false;
                                  this.snackbar.success = false;
                                  this.snackbar.display = true;
                                  this.snackbar.snackbarText = error.response.data.message;
                              })
                        : this.tariff
                              .storeTariff()
                              .then(res => {
                                  this.tariff.storeLoading = false;
                                  this.snackbar.success = true;
                                  this.snackbar.display = true;
                                  this.snackbar.snackbarText = res.data.message;
                                  window.location.href = "/admin/super/tariff";
                              })
                              .catch(error => {
                                  this.snackbar.success = false;
                                  this.snackbar.display = true;
                                  this.snackbar.snackbarText = error.response.data.message;
                                  this.tariff.storeLoading = false;
                              });
                } else {
                    console.error("invalid form");
                }
            });
        },
        getAltTariffs(car_class_id, country_id) {
            if (!this.tariff.all_alternatives) {
                this.alt_loading = true;
                axios.get(`/admin/super/tariff/alt_tariffs/${car_class_id}/${country_id}`).then((response) => {
                    this.alternativeTariffs = response.data.alternativeTariffs
                    this.alt_loading = false
                })
            }
        },
        displayAltTariffs() {
            if (this.tariff.car_class_id && this.tariff.country_id) {
                this.getAltTariffs(this.tariff.car_class_id, this.tariff.country_id);
            }
        },
    },
    watch: {
        currentType: function () {
            this.tariff.tariff_type = this.currentType;
        },

        "tariff.payment_type_id": function () {
            this.checkDisableDef();
        },

        "tariff.tariff_type_id": function () {
            this.setTypeOption();
        },

        "tariff.option": function () {
            this.$validator.reset();
            if (this.tariff.option) {
                if (this.tariff.tariff_id) {
                    this.optionExists = true;
                }
                let option = this.tariff.options.find(item => {
                    return item.value === this.tariff.option;
                });
                this.tariff.optionComponent = option.component;
                this.tariff.optionTitle = option.title;
            }
        },
        "snackbar.display": function () {
            if (this.snackbar.display) {
                setTimeout(() => {
                    this.snackbar.display = !this.snackbar.display;
                }, 1500);
            }
        },
        "tariff.car_class_id": function (newVal, oldVal) {
            if (newVal && newVal !== oldVal && this.tariff.country_id) {
                this.getAltTariffs(newVal, this.tariff.country_id);
            }
        },
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    created() {
        if (this.tariff.all_alternatives) {
            this.alternativeTariffs = this.tariff.all_alternatives;
        }
        // else {
        //     if (this.tariff.current_tariff) {
        //         if (this.tariff.current_tariff.alt_behind.length) {
        //             this.alternativeTariffs = this.tariff.current_tariff.alt_behind;
        //         }
        //         if (this.tariff.current_tariff.alt_region.length) {
        //             this.alternativeTariffs = this.tariff.current_tariff.alt_region;
        //         }
        //         if (this.tariff.current_tariff.alt_destination.length) {
        //             this.alternativeTariffs = this.tariff.current_tariff.alt_destination;
        //         }
        //     }
        // }

        if (this.tariffObj) {
            this.tariff.setOption();
            this.tariff.tariff_type = this.currentType;
        }
        this.checkDisableDef();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
