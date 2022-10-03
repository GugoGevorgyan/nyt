<!-- @format -->

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="4">
                <select-location :tariff="tariff" />
                <v-text-field
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('price_min')"
                    type="number"
                    :error-messages="errors.collect('price_min')"
                    color="yellow darken-3"
                    label="Цена минуты"
                    name="price_min"
                    outlined
                    dense
                    v-model="tariff.fictional_region.price_min"
                    v-validate="tariff.rules.fictional_region.price_min"
                    data-vv-as="цена минуты"
                />
                <v-text-field
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('price_km')"
                    type="number"
                    :error-messages="errors.collect('price_km')"
                    color="yellow darken-3"
                    label="Цена киломентра"
                    name="price_km"
                    outlined
                    dense
                    v-model="tariff.fictional_region.price_km"
                    v-validate="tariff.rules.fictional_region.price_km"
                    data-vv-as="цена километра"
                />
                <v-text-field
                    v-if="tariff.tariff_type_id === 2 || tariff.tariff_type_id === 3"
                    type="number"
                    :error-messages="errors.collect('minimal_distance_value')"
                    color="yellow darken-3"
                    label="Минимальная дистанция в метрах"
                    name="minimal_distance_value"
                    outlined
                    dense
                    v-model="tariff.fictional_region.minimal_distance_value"
                    v-validate="tariff.rules.fictional_region.minimal_distance_value"
                    data-vv-as="Минимальная дистанция"
                />
                <v-text-field
                    v-if="tariff.tariff_type_id === 1 || tariff.tariff_type_id === 3"
                    type="number"
                    :error-messages="errors.collect('minimal_duration_value')"
                    color="yellow darken-3"
                    label="Минимальная время в минутах"
                    name="minimal_duration_value"
                    outlined
                    dense
                    v-model="tariff.fictional_region.minimal_duration_value"
                    v-validate="tariff.rules.fictional_region.minimal_duration_value"
                    data-vv-as="Минимальная время"
                />
                <v-switch
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('sitting_fee')"
                    :true-value="1"
                    :false-value="0"
                    label="Плата за сидение"
                    name="sitting_fee"
                    v-model="tariff.fictional_region.sitting_fee"
                    v-validate="tariff.rules.fictional_region.sitting_fee"
                    :error-messages="errors.collect('sitting_fee')"
                    dense
                    data-vv-as="плата за сидение"
                />
                <v-text-field
                    v-if="
                        tariff.fictional_region &&
                        tariff.fictional_region.hasOwnProperty('sit_price_minute') &&
                        tariff.fictional_region.sitting_fee
                    "
                    type="number"
                    :error-messages="errors.collect('sit_price_minute')"
                    color="yellow darken-3"
                    label="Цена минуты сидения"
                    name="sit_price_minute"
                    outlined
                    dense
                    v-model="tariff.fictional_region.sit_price_minute"
                    v-validate="tariff.rules.fictional_region.sit_price_minute"
                    data-vv-as="цена минуты сидения"
                />
                <v-text-field
                    v-if="
                        tariff.fictional_region &&
                        tariff.fictional_region.hasOwnProperty('sit_price_km') &&
                        tariff.fictional_region.sitting_fee
                    "
                    type="number"
                    :error-messages="errors.collect('sit_price_km')"
                    color="yellow darken-3"
                    label="Цена километра сидения"
                    name="sit_price_km"
                    outlined
                    dense
                    v-model="tariff.fictional_region.sit_price_km"
                    v-validate="tariff.rules.fictional_region.sit_price_km"
                    data-vv-as="цена километра сидения"
                />
                <v-text-field
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('free_wait_stop_minutes')"
                    type="number"
                    :error-messages="errors.collect('free_wait_stop_minutes')"
                    color="yellow darken-3"
                    label="Количество бесплатных минут"
                    hint="Количество бесплатных минут ожидания при остановке"
                    name="free_wait_stop_minutes"
                    outlined
                    dense
                    v-model="tariff.fictional_region.free_wait_stop_minutes"
                    v-validate="tariff.rules.fictional_region.free_wait_stop_minutes"
                    data-vv-as="количество минут ожидания"
                />
                <v-text-field
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('paid_wait_stop_minute')"
                    type="number"
                    :error-messages="errors.collect('paid_wait_stop_minute')"
                    color="yellow darken-3"
                    label="Цена минуты ожидания"
                    hint="Цена минуты ожидания при остановке"
                    name="paid_wait_stop_minute"
                    outlined
                    dense
                    v-model="tariff.fictional_region.paid_wait_stop_minute"
                    v-validate="tariff.rules.fictional_region.paid_wait_stop_minute"
                    data-vv-as="цена минуты ожидания"
                />
                <v-switch
                    v-if="tariff.fictional_region && tariff.fictional_region.hasOwnProperty('enable_speed_wait')"
                    :true-value="1"
                    :false-value="0"
                    label="Плата за низкую скорость"
                    name="enable_speed_wait"
                    v-model="tariff.fictional_region.enable_speed_wait"
                    v-validate="tariff.rules.fictional_region.enable_speed_wait"
                    :error-messages="errors.collect('enable_speed_wait')"
                    dense
                    data-vv-as="плата за низкую скорость"
                />
                <v-text-field
                    v-if="
                        tariff.fictional_region &&
                        tariff.fictional_region.hasOwnProperty('speed_wait_limit') &&
                        tariff.fictional_region.enable_speed_wait
                    "
                    type="number"
                    :error-messages="errors.collect('speed_wait_limit')"
                    color="yellow darken-3"
                    label="Минимальная скорость"
                    hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                    name="speed_wait_limit"
                    outlined
                    dense
                    v-model="tariff.fictional_region.speed_wait_limit"
                    v-validate="tariff.rules.fictional_region.speed_wait_limit"
                    data-vv-as="минимальная скорость"
                />
                <v-text-field
                    name="speed_wait_price_minute"
                    v-if="
                        tariff.fictional_region &&
                        tariff.fictional_region.hasOwnProperty('speed_wait_price_minute') &&
                        tariff.fictional_region.enable_speed_wait
                    "
                    type="number"
                    :error-messages="errors.collect('speed_wait_price_minute')"
                    color="yellow darken-3"
                    label="Цена минуты при низкой скорости"
                    hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                    outlined
                    dense
                    v-model="tariff.fictional_region.speed_wait_price_minute"
                    v-validate="tariff.rules.fictional_region.speed_wait_price_minute"
                    data-vv-as="цена минуты при низкой скорости"
                />
                <v-switch
                    v-if="tariff.fictional_region"
                    :true-value="1"
                    :false-value="0"
                    label="указать цены вне выделенной области"
                    name="fictional_behind_fields"
                    v-model="tariff.fictional_behind_fields"
                    v-validate="tariff.rules.fictional_behind_fields"
                    :error-messages="errors.collect('fictional_behind_fields')"
                    dense
                    data-vv-as="указать цены вне выделенной области"
                />
            </v-col>
            <v-col cols="12" md="8">
                <select-area :tariff="tariff" option="fictional_region" />
            </v-col>
            <v-col cols="12">
                <fictional-behind-fields :tariff="tariff" v-if="tariff.fictional_behind_fields" />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import FictionalBehindFields from './FictionalBehindFields';
import SelectArea from '../components/SelectArea';
import SelectLocation from '../components/SelectLocation';
export default {
    components: { SelectLocation, SelectArea, FictionalBehindFields },
    props: ['tariff', 'tariffTypes', 'tariffTypesLoading'],
    name: 'FictionalRegionsFields',
    data() {
        return {
            fictional_behind: null,
            showFields: {
                1: {
                    area_id: null,
                    price_min: null,
                    sitting_fee: 0,
                    sit_price_minute: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                    fictional_behind: null,
                },
                2: {
                    area_id: null,
                    price_km: null,
                    sitting_fee: 0,
                    sit_price_km: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                    fictional_behind: null,
                },
                3: {
                    area_id: null,
                    price_min: null,
                    price_km: null,
                    sitting_fee: 0,
                    sit_price_minute: null,
                    sit_price_km: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                    fictional_behind: null,
                },
            },
        };
    },
    watch: {
        'tariff.fictional_behind_fields': function () {
            if (!this.tariff.fictional_behind_fields) {
                this.tariff.fictional_region.fictional_behind = null;
            } else if (this.fictional_behind) {
                this.tariff.fictional_region.fictional_behind = { ...this.fictional_behind };
            }
        },
    },
    methods: {
        setValues() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_fictional_region_id) {
                this.tariff.fictional_region = this.tariff.current_tariff;
                if (this.tariff.fictional_region.fictional_behind) {
                    this.tariff.fictional_behind_fields = 1;
                }
            } else if (this.tariff.tariff_type && this.showFields[this.tariff.tariff_type.type] && !this.tariff.fictional_region) {
                this.tariff.fictional_region = this.showFields[this.tariff.tariff_type.type];
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
        if (this.tariff.current_tariff) {
            this.fictional_behind = { ...this.tariff.current_tariff.fictional_behind };
        }
        this.setValues();
    },
};
</script>
