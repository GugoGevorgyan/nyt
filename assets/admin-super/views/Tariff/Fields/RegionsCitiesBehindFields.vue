<template>
    <v-card height="100%" tile p-10>
        <v-card-title>
            <span class="headline">Регионально-городской тариф - Зопределено</span>
            <v-spacer />
        </v-card-title>
        <v-divider />
        <v-card-text>
        <v-row class="mt-4">
            <v-col cols="12" md="4">
                <v-text-field
                    v-if="tariff.regions_cities.tariff_behind
                     && tariff.regions_cities.tariff_behind.hasOwnProperty('price_min')"
                    type="number"
                    :error-messages="errors.collect('price_min')"
                    color="yellow darken-3"
                    label="Цена минуты"
                    name="price_min"
                    outlined
                    dense
                    v-model="tariff.regions_cities.tariff_behind.price_min"
                    v-validate="tariff.rules.tariff_behind.price_min"
                    data-vv-as="цена минуты"
                />
                <v-text-field
                    v-if="tariff.regions_cities.tariff_behind
                     && tariff.regions_cities.tariff_behind.hasOwnProperty('price_km')"
                    type="number"
                    :error-messages="errors.collect('price_km')"
                    color="yellow darken-3"
                    label="Цена киломентра"
                    name="price_km"
                    outlined
                    dense
                    v-model="tariff.regions_cities.tariff_behind.price_km"
                    v-validate="tariff.rules.tariff_behind.price_km"
                    data-vv-as="цена километра"
                />
                <v-text-field
                    v-if="tariff.regions_cities.tariff_behind
                    && tariff.regions_cities.tariff_behind.hasOwnProperty('zone_distance')"
                    type="number"
                    :error-messages="errors.collect('zone_distance')"
                    color="yellow darken-3"
                    label="Дистанционная зона"
                    hint="Дистанционная зона"
                    name="zone_distance"
                    outlined
                    dense
                    v-model="tariff.regions_cities.tariff_behind.zone_distance"
                    v-validate="tariff.rules.tariff_behind.zone_distance"
                    data-vv-as="Дистанционная зона"
                />
                <v-text-field
                    v-if="tariff.regions_cities.tariff_behind
                    && tariff.regions_cities.tariff_behind.hasOwnProperty('free_wait_stop_minutes')"
                    type="number"
                    :error-messages="errors.collect('free_wait_stop_minutes')"
                    color="yellow darken-3"
                    label="Количество бесплатных минут"
                    hint="Количество бесплатных минут ожидания при остановке"
                    name="free_wait_stop_minutes"
                    outlined
                    dense
                    v-model="tariff.regions_cities.tariff_behind.free_wait_stop_minutes"
                    v-validate="tariff.rules.tariff_behind.free_wait_stop_minutes"
                    data-vv-as="количество минут ожидания"
                />
                <v-text-field
                    v-if="tariff.regions_cities && tariff.regions_cities.tariff_behind.hasOwnProperty('paid_wait_stop_minute')"
                    type='number'
                    :error-messages="errors.collect('paid_wait_stop_minute')"
                    color='yellow darken-3'
                    label='Цена минуты ожидания'
                    hint='Цена минуты ожидания при остановке'
                    name='paid_wait_stop_minute'
                    outlined
                    dense
                    v-model='tariff.regions_cities.tariff_behind.paid_wait_stop_minute'
                    v-validate='tariff.rules.tariff_behind.paid_wait_stop_minute'
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
                    v-model='tariff.regions_cities.tariff_behind.minimal_distance_value'
                    v-validate='tariff.rules.tariff_behind.minimal_distance_value'
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
                    v-model='tariff.regions_cities.tariff_behind.minimal_duration_value'
                    v-validate='tariff.rules.tariff_behind.minimal_duration_value'
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
                    v-model='tariff.regions_cities.tariff_behind.change_initial_price_percent'
                    data-vv-as='Процент перебора'
                />
            </v-col>
            <v-col cols='12' md='4' mt='2'>
                <sitting-fee :option='tariff.regions_cities.tariff_behind' :tariff-obj='tariff' ref='sittingFee'></sitting-fee>
            </v-col>
            <v-col cols="12" md="4">
                <v-switch
                    v-if="tariff.regions_cities.tariff_behind && tariff.regions_cities.tariff_behind.hasOwnProperty('enable_speed_wait')"
                    :true-value="1"
                    :false-value="0"
                    label="Плата за низкую скорость"
                    name="enable_speed_wait"
                    v-model="tariff.regions_cities.tariff_behind.enable_speed_wait"
                    v-validate="tariff.rules.tariff_behind.enable_speed_wait"
                    :error-messages="errors.collect('enable_speed_wait')"
                    dense
                    data-vv-as="плата за низкую скорость"
                >
                </v-switch>
                <div v-if="tariff.regions_cities.tariff_behind.enable_speed_wait">
                    <v-text-field
                        v-if="tariff.regions_cities.tariff_behind && tariff.regions_cities.tariff_behind.hasOwnProperty('speed_wait_limit')"
                        type="number"
                        :error-messages="errors.collect('speed_wait_limit')"
                        color="yellow darken-3"
                        label="Минимальная скорость"
                        hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                        name="speed_wait_limit"
                        outlined
                        dense
                        v-model="tariff.regions_cities.tariff_behind.speed_wait_limit"
                        v-validate="tariff.rules.tariff_behind.speed_wait_limit"
                        data-vv-as="минимальная скорость"
                    />
                    <v-text-field
                        name="speed_wait_price_minute"
                        v-if="tariff.regions_cities.tariff_behind && tariff.regions_cities.tariff_behind.hasOwnProperty('speed_wait_price_minute')"
                        type="number"
                        :error-messages="errors.collect('speed_wait_price_minute')"
                        color="yellow darken-3"
                        label="Цена минуты при низкой скорости"
                        hint="Минимальная скорость, ниже которой, будет начисляться дополнительная плата"
                        outlined
                        dense
                        v-model="tariff.regions_cities.tariff_behind.speed_wait_price_minute"
                        v-validate="tariff.rules.tariff_behind.speed_wait_price_minute"
                        data-vv-as="цена минуты при низкой скорости"
                    />
                </div>
                <div v-else>
                    <v-alert type="info" outlined dense> Плата за низкую скорость не установлена </v-alert>
                </div>
            </v-col>
        </v-row>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" @click='cancel(tariff.regions_cities.tariff_behind)'>
                {{ haveCompletedFields ? 'Очистить все поля' : 'Отменить' }}
            </v-btn>
            <v-btn color="primary" @click='save()'> Хорошо </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import SittingFee from '../components/SittingFee';
export default {
    name: 'RegionsCitiesBehindFields',
    components: { SittingFee },
    props:['tariff'],
    data() {
        return {
            haveCompletedFields: this.tariff.regions_cities.tariff_behind.tariff_region_behind_id ?? null
        }
    },
    methods: {
        save() {
            let validation = [];
            validation.push(
                this.$refs.sittingFee.$validator.validateAll().then(valid => {
                    return valid;
                }),
                this.$validator.validateAll().then(valid => {
                    return valid;
                }),
            );
            Promise.all(validation).then(values => {
                if (!values.includes(false)) {
                    this.$emit('save');
                }
            });
        },
        cancel(data) {
            if (data.price_km) {
                data.price_km = null;
            }
            if (data.price_min) {
                data.price_min = null;
            }
            if (data.sit_price_km) {
                data.sit_price_km = null;
            }
            if (data.sit_price_minute) {
                data.sit_price_minute = null;
            }
            if (data.tariff_region_behind_id) {
                data.tariff_region_behind_id = null
            }
            data.zone_distance = null;
            data.sitting_fee = null;
            data.sit_fix_price = null;
            data.free_wait_stop_minutes = null;
            data.paid_wait_stop_minute = null;
            data.minimal_distance_value = null;
            data.minimal_duration_value = null;
            data.change_initial_price_percent = null;
            data.enable_speed_wait = 0;
            data.speed_wait_limit = null;
            data.speed_wait_price_minute = null;

            this.$emit('close');
        },
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    created() {
        this.$validator.reset();
    }
};
</script>

<style scoped>

</style>
