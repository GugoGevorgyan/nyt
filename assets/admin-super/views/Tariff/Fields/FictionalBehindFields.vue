<!-- @format -->

<template>
    <div>
        <v-divider></v-divider>
        <v-subheader>Вне выделенной области</v-subheader>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field
                    v-if="
                        tariff.fictional_region.fictional_behind && tariff.fictional_region.fictional_behind.hasOwnProperty('zone_distance')
                    "
                    type="number"
                    :error-messages="errors.collect('zone_distance')"
                    color="yellow darken-3"
                    label="Радиус наружной области (в метрах)"
                    name="zone_distance"
                    outlined
                    dense
                    v-model="tariff.fictional_region.fictional_behind.zone_distance"
                    v-validate="tariff.rules.fictional_region.fictional_behind.zone_distance"
                    data-vv-as="радиус наружной области"
                ></v-text-field>
                <v-text-field
                    v-if="tariff.fictional_region.fictional_behind && tariff.fictional_region.fictional_behind.hasOwnProperty('price_min')"
                    type="number"
                    :error-messages="errors.collect('price_min')"
                    color="yellow darken-3"
                    label="Цена минуты"
                    name="price_min"
                    outlined
                    dense
                    v-model="tariff.fictional_region.fictional_behind.price_min"
                    v-validate="tariff.rules.fictional_region.fictional_behind.price_min"
                    data-vv-as="цена минуты"
                ></v-text-field>
                <v-text-field
                    v-if="tariff.fictional_region.fictional_behind && tariff.fictional_region.fictional_behind.hasOwnProperty('price_km')"
                    type="number"
                    :error-messages="errors.collect('price_km')"
                    color="yellow darken-3"
                    label="Цена киломентра"
                    name="price_km"
                    outlined
                    dense
                    v-model="tariff.fictional_region.fictional_behind.price_km"
                    v-validate="tariff.rules.fictional_region.fictional_behind.price_km"
                    data-vv-as="цена километра"
                ></v-text-field>
                <v-text-field
                    v-if="
                        tariff.fictional_region.fictional_behind &&
                        tariff.fictional_region.fictional_behind.hasOwnProperty('free_wait_stop_minutes')
                    "
                    type="number"
                    :error-messages="errors.collect('free_wait_stop_minutes')"
                    color="yellow darken-3"
                    label="Количество бесплатных минут"
                    hint="Количество бесплатных минут ожидания при остановке"
                    name="free_wait_stop_minutes"
                    outlined
                    dense
                    v-model="tariff.fictional_region.fictional_behind.free_wait_stop_minutes"
                    v-validate="tariff.rules.fictional_region.fictional_behind.free_wait_stop_minutes"
                    data-vv-as="количество минут ожидания"
                ></v-text-field>
                <v-text-field
                    v-if="
                        tariff.fictional_region.fictional_behind &&
                        tariff.fictional_region.fictional_behind.hasOwnProperty('paid_wait_stop_minute')
                    "
                    type="number"
                    :error-messages="errors.collect('paid_wait_stop_minute')"
                    color="yellow darken-3"
                    label="Цена минуты ожидания"
                    hint="Цена минуты ожидания при остановке"
                    name="paid_wait_stop_minute"
                    outlined
                    dense
                    v-model="tariff.fictional_region.fictional_behind.paid_wait_stop_minute"
                    v-validate="tariff.rules.fictional_region.fictional_behind.paid_wait_stop_minute"
                    data-vv-as="цена минуты ожидания"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-switch
                    v-if="
                        tariff.fictional_region.fictional_behind && tariff.fictional_region.fictional_behind.hasOwnProperty('sitting_fee')
                    "
                    :true-value="1"
                    :false-value="0"
                    label="Плата за сидение"
                    name="sitting_fee"
                    v-model="tariff.fictional_region.fictional_behind.sitting_fee"
                    v-validate="tariff.rules.fictional_region.fictional_behind.sitting_fee"
                    :error-messages="errors.collect('sitting_fee')"
                    dense
                    data-vv-as="плата за сидение"
                >
                </v-switch>
                <div v-if="tariff.fictional_region.fictional_behind.sitting_fee">
                    <v-text-field
                        v-if="
                            tariff.fictional_region.fictional_behind &&
                            tariff.fictional_region.fictional_behind.hasOwnProperty('sit_price_minute')
                        "
                        type="number"
                        :error-messages="errors.collect('sit_price_minute')"
                        color="yellow darken-3"
                        label="Цена минуты сидения"
                        name="sit_price_minute"
                        outlined
                        dense
                        v-model="tariff.fictional_region.fictional_behind.sit_price_minute"
                        v-validate="tariff.rules.fictional_region.fictional_behind.sit_price_minute"
                        data-vv-as="цена минуты сидения"
                    ></v-text-field>
                    <v-text-field
                        v-if="
                            tariff.fictional_region.fictional_behind &&
                            tariff.fictional_region.fictional_behind.hasOwnProperty('sit_price_km')
                        "
                        type="number"
                        :error-messages="errors.collect('sit_price_km')"
                        color="yellow darken-3"
                        label="Цена километра сидения"
                        name="sit_price_km"
                        outlined
                        dense
                        v-model="tariff.fictional_region.fictional_behind.sit_price_km"
                        v-validate="tariff.rules.fictional_region.fictional_behind.sit_price_km"
                        data-vv-as="цена километра сидения"
                    ></v-text-field>
                </div>
                <div v-else>
                    <v-alert type="info" outlined dense> Плата за сидение не установлена </v-alert>
                </div>
            </v-col>
            <v-col cols="12" md="4">
                <v-switch
                    v-if="
                        tariff.fictional_region.fictional_behind &&
                        tariff.fictional_region.fictional_behind.hasOwnProperty('enable_speed_wait')
                    "
                    :true-value="1"
                    :false-value="0"
                    label="Плата за низкую скорость"
                    name="enable_speed_wait"
                    v-model="tariff.fictional_region.fictional_behind.enable_speed_wait"
                    v-validate="tariff.rules.fictional_region.fictional_behind.enable_speed_wait"
                    :error-messages="errors.collect('enable_speed_wait')"
                    dense
                    data-vv-as="плата за низкую скорость"
                >
                </v-switch>
                <div v-if="tariff.fictional_region.fictional_behind.enable_speed_wait">
                    <v-text-field
                        v-if="
                            tariff.fictional_region.fictional_behind &&
                            tariff.fictional_region.fictional_behind.hasOwnProperty('speed_wait_limit')
                        "
                        type="number"
                        :error-messages="errors.collect('speed_wait_limit')"
                        color="yellow darken-3"
                        label="Минимальная скорость"
                        hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                        name="speed_wait_limit"
                        outlined
                        dense
                        v-model="tariff.fictional_region.fictional_behind.speed_wait_limit"
                        v-validate="tariff.rules.fictional_region.fictional_behind.speed_wait_limit"
                        data-vv-as="минимальная скорость"
                    ></v-text-field>
                    <v-text-field
                        name="speed_wait_price_minute"
                        v-if="
                            tariff.fictional_region.fictional_behind &&
                            tariff.fictional_region.fictional_behind.hasOwnProperty('speed_wait_price_minute')
                        "
                        type="number"
                        :error-messages="errors.collect('speed_wait_price_minute')"
                        color="yellow darken-3"
                        label="Цена минуты при низкой скорости"
                        outlined
                        dense
                        v-model="tariff.fictional_region.fictional_behind.speed_wait_price_minute"
                        v-validate="tariff.rules.fictional_region.fictional_behind.speed_wait_price_minute"
                        data-vv-as="цена минуты при низкой скорости"
                    ></v-text-field> </div
                ><div v-else>
                    <v-alert type="info" outlined dense> Плата за низкую скорость не установлена </v-alert>
                </div>
            </v-col>
        </v-row>
    </div>
</template>
<script>
export default {
    props: ['tariff'],
    name: 'FictionalBehindFields',
    data() {
        return {
            showFields: {
                1: {
                    zone_distance: null,
                    price_min: null,
                    sitting_fee: 0,
                    sit_price_minute: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                },
                2: {
                    zone_distance: null,
                    price_km: null,
                    sitting_fee: 0,
                    sit_price_km: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                },
                3: {
                    zone_distance: null,
                    price_km: null,
                    price_min: null,
                    sitting_fee: 0,
                    sit_price_km: null,
                    sit_price_minute: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                },
            },
        };
    },
    methods: {
        setValues() {
            if (
                this.tariff.tariff_type &&
                this.showFields[this.tariff.tariff_type.type] &&
                this.tariff.fictional_region &&
                !this.tariff.fictional_region.fictional_behind
            ) {
                this.tariff.fictional_region.fictional_behind = this.showFields[this.tariff.tariff_type.type];
            }
        },
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    beforeDestroy() {
        this.tariff.removeComponent(this);
    },
    created() {
        this.setValues();
    },
};
</script>
