<!-- @format -->

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="4">
                <select-location :tariff="tariff"></select-location>
                <v-text-field
                    v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('free_wait_stop_minutes')"
                    type="number"
                    :error-messages="errors.collect('free_wait_stop_minutes')"
                    color="yellow darken-3"
                    label="Количество бесплатных минут"
                    hint="Количество бесплатных минут ожидания при остановке"
                    name="free_wait_stop_minutes"
                    outlined
                    dense
                    v-model="tariff.regions_cities.free_wait_stop_minutes"
                    v-validate="tariff.rules.regions_cities.free_wait_stop_minutes"
                    data-vv-as="количество минут ожидания"
                />
                <v-text-field
                    v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('paid_wait_stop_minute')"
                    type='number'
                    :error-messages="errors.collect('paid_wait_stop_minute')"
                    color='yellow darken-3'
                    label='Цена минуты ожидания'
                    hint='Цена минуты ожидания при остановке'
                    name='paid_wait_stop_minute'
                    outlined
                    dense
                    v-model='tariff.regions_cities.paid_wait_stop_minute'
                    v-validate='tariff.rules.regions_cities.paid_wait_stop_minute'
                    data-vv-as='цена минуты ожидания'
                />
                <v-text-field
                    v-if='tariff.tariff_type_id === 2 || tariff.tariff_type_id === 3'
                    type='number'
                    :error-messages="errors.collect('minimal_distance_value')"
                    color='yellow darken-3'
                    label='Минимальная дистанция в метрах'
                    hint='Минимальная дистанция в метрах при остановке'
                    name='minimal_distance_value'
                    outlined
                    dense
                    v-model='tariff.regions_cities.minimal_distance_value'
                    v-validate='tariff.rules.regions_cities.minimal_distance_value'
                    data-vv-as='Минимальная дистанция'
                />
                <v-text-field
                    class='pa-0'
                    v-if='tariff.tariff_type_id === 1 || tariff.tariff_type_id === 3'
                    type='number'
                    :error-messages="errors.collect('minimal_duration_value')"
                    color='yellow darken-3'
                    label='Минимальная время в минутах'
                    hint='Минимальная дистанция в метрах при остановке'
                    name='minimal_duration_value'
                    outlined
                    dense
                    v-model='tariff.regions_cities.minimal_duration_value'
                    v-validate='tariff.rules.regions_cities.minimal_duration_value'
                    data-vv-as='Минимальная время'
                />
                <v-text-field
                    name='change_initial_price_percent'
                    v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('change_initial_price_percent')"
                    type='number'
                    color='yellow darken-3'
                    label='Процент перебора'
                    hint='Процент перебора'
                    outlined
                    dense
                    v-model='tariff.regions_cities.change_initial_price_percent'
                    data-vv-as='Процент перебора'
                />
            </v-col>
            <v-col cols='12' md='4' mt='2'>
                <sitting-fee :option='tariff.regions_cities' :tariff-obj='tariff'></sitting-fee>
            </v-col>
            <v-col cols="12" md="4">
                <v-switch
                    v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('enable_speed_wait')"
                    :true-value="1"
                    :false-value="0"
                    label="Плата за низкую скорость"
                    name="enable_speed_wait"
                    v-model="tariff.regions_cities.enable_speed_wait"
                    v-validate="tariff.rules.regions_cities.enable_speed_wait"
                    :error-messages="errors.collect('enable_speed_wait')"
                    dense
                    data-vv-as="плата за низкую скорость"
                >
                </v-switch>
                <div v-if="tariff.regions_cities.enable_speed_wait">
                    <v-text-field
                        v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('speed_wait_limit')"
                        type="number"
                        :error-messages="errors.collect('speed_wait_limit')"
                        color="yellow darken-3"
                        label="Минимальная скорость"
                        hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                        name="speed_wait_limit"
                        outlined
                        dense
                        v-model="tariff.regions_cities.speed_wait_limit"
                        v-validate="tariff.rules.regions_cities.speed_wait_limit"
                        data-vv-as="минимальная скорость"
                    />
                    <v-text-field
                        name="speed_wait_price_minute"
                        v-if="tariff.regions_cities && tariff.regions_cities.hasOwnProperty('speed_wait_price_minute')"
                        type="number"
                        :error-messages="errors.collect('speed_wait_price_minute')"
                        color="yellow darken-3"
                        label="Цена минуты при низкой скорости"
                        hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                        outlined
                        dense
                        v-model="tariff.regions_cities.speed_wait_price_minute"
                        v-validate="tariff.rules.regions_cities.speed_wait_price_minute"
                        data-vv-as="цена минуты при низкой скорости"
                    />
                </div>
                <div v-else>
                    <v-alert type="info" outlined dense> Плата за низкую скорость не установлена </v-alert>
                </div>
            </v-col>
            <v-col cols="12" md="4">
                <v-btn color="info" @click='behindFormIsOpen = !behindFormIsOpen'> Зопределено тариф </v-btn>
                <v-dialog v-if='behindFormIsOpen' v-model="behindFormIsOpen" max-width="900px" width="100%">
                    <regions-cities-behind-fields :tariff='tariff' @save='behindFieldsIsCompleted()' @close='closeBehindSection()'></regions-cities-behind-fields>
                </v-dialog>
            </v-col>
        </v-row>
        <v-row class='mt-4'>
            <v-col cols='12' md='12' style='height: 600px'>
                <select-area :tariff='tariff' :option='tariff.regions_cities' />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import SelectLocation from '../components/SelectLocation';
import SittingFee from '../components/SittingFee';
import SelectArea from '../components/SelectArea';
import RegionsCitiesBehindFields from './RegionsCitiesBehindFields';
export default {
    components: { RegionsCitiesBehindFields, SelectArea, SittingFee, SelectLocation },
    props: ['tariff'],
    name: 'RegionsCitiesFields',
    data() {
        return {
            showFields: {
                    price_km: null,
                    price_min: null,
                    sitting_fee: null,
                    sit_price_km: null,
                    sit_fix_price: null,
                    price_type_id: this.tariff.tariff_type_id,
                    sit_price_minute: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
                    area_id: null,
                    merge_km_minute: 1,
                    minimal_distance_value: null,
                    minimal_duration_value: null,
                    sit_type_id: this.tariff.tariff_type_id,
                    change_initial_price_percent: null,
                    tariff_behind: this.tariff.tariff_behind ?? null
            },
            behindFormIsOpen: false,
            showBehindFields: {
                    price_type_id: this.tariff.tariff_type_id,
                    sit_type_id: this.tariff.tariff_type_id,
                    zone_distance: null,
                    price_km: null,
                    price_min: null,
                    sitting_fee: null,
                    sit_price_km: null,
                    sit_fix_price: null,
                    sit_price_minute: null,
                    free_wait_stop_minutes: null,
                    paid_wait_stop_minute: null,
                    minimal_distance_value: null,
                    minimal_duration_value: null,
                    change_initial_price_percent: null,
                    enable_speed_wait: 0,
                    speed_wait_limit: null,
                    speed_wait_price_minute: null,
            }
        };
    },
    watch: {
        'tariff.minimal_price': function() {
            if (this.showFields.hasOwnProperty('price_min')) {
                if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_region_city_id) {
                    this.tariff.current_tariff.price_min = this.tariff.minimal_price;
                } else {
                    this.showFields.price_min = this.tariff.minimal_price;
                }
            }
            if (this.showFields.hasOwnProperty('price_km')) {
                if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_region_city_id) {
                    this.tariff.current_tariff.price_km = this.tariff.minimal_price;
                } else {
                    this.showFields.price_km = this.tariff.minimal_price;
                }
            }
        },
        'behindFormIsOpen': function() {
            if (this.behindFormIsOpen) {
                this.tariff.regions_cities.tariff_behind = this.tariff.tariff_behind ?? this.showBehindFields;
            }
        }
    },
    methods: {
        setValues() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_region_city_id) {
                this.tariff.regions_cities = this.tariff.current_tariff;
            } else if (this.tariff.tariff_type && this.showFields) {
                this.tariff.regions_cities = this.showFields;
            }
        },
        closeBehindSection() {
            this.tariff.regions_cities.tariff_behind = null;
            this.behindFormIsOpen = false;
        },
        behindFieldsIsCompleted() {
            this.behindFormIsOpen = false;
        },
        checkRegionCitiesFields() {
            if (this.tariff.tariff_type.type === 1) {
                if (this.showFields.hasOwnProperty('price_km')) {
                    delete this.showFields.price_km;
                }
                if (this.showFields.hasOwnProperty('sit_price_km')) {
                    delete this.showFields.sit_price_km;
                }
                if (this.showFields.hasOwnProperty('minimal_distance_value')) {
                    delete this.showFields.minimal_distance_value;
                }
            }
            if (this.tariff.tariff_type.type === 2) {
                if (this.showFields.hasOwnProperty('price_min')) {
                    delete this.showFields.price_min;
                }
                if (this.showFields.hasOwnProperty('sit_price_minute')) {
                    delete this.showFields.sit_price_minute;
                }
                if (this.showFields.hasOwnProperty('minimal_duration_value')) {
                    delete this.showFields.minimal_duration_value;
                }
            }
            this.checkRegionBehindFields();
        },
        checkRegionBehindFields() {
            if (this.tariff.tariff_type.type === 1) {
                if (this.tariff.tariff_behind) {
                    if (this.tariff.tariff_behind.hasOwnProperty('price_km')) {
                        delete this.tariff.tariff_behind.price_km;
                    }
                    if (this.tariff.tariff_behind.hasOwnProperty('sit_price_km')) {
                        delete this.tariff.tariff_behind.sit_price_km;
                    }
                    if (this.tariff.tariff_behind.hasOwnProperty('minimal_distance_value')) {
                        delete this.tariff.tariff_behind.minimal_distance_value;
                    }
                } else {
                    if (this.showBehindFields.hasOwnProperty('price_km')) {
                        delete this.showBehindFields.price_km;
                    }
                    if (this.showBehindFields.hasOwnProperty('sit_price_km')) {
                        delete this.showBehindFields.sit_price_km;
                    }
                    if (this.showBehindFields.hasOwnProperty('minimal_distance_value')) {
                        delete this.showBehindFields.minimal_distance_value;
                    }
                }
            }
            if (this.tariff.tariff_type.type === 2) {
                if (this.tariff.tariff_behind) {
                    if (this.tariff.tariff_behind.hasOwnProperty('price_min')) {
                        delete this.tariff.tariff_behind.price_min;
                    }
                    if (this.tariff.tariff_behind.hasOwnProperty('sit_price_minute')) {
                        delete this.tariff.tariff_behind.sit_price_minute;
                    }
                    if (this.tariff.tariff_behind.hasOwnProperty('minimal_duration_value')) {
                        delete this.tariff.tariff_behind.minimal_duration_value;
                    }
                } else {
                    if (this.showBehindFields.hasOwnProperty('price_min')) {
                        delete this.showBehindFields.price_min;
                    }
                    if (this.showBehindFields.hasOwnProperty('sit_price_minute')) {
                        delete this.showBehindFields.sit_price_minute;
                    }
                    if (this.showBehindFields.hasOwnProperty('minimal_duration_value')) {
                        delete this.showBehindFields.minimal_duration_value;
                    }
                }
            }
            this.setValues();
        }
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    beforeDestroy() {
        this.tariff.removeComponent(this);
    },
    created() {
        this.checkRegionCitiesFields();
    },
};
</script>
