<!-- @format -->

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="4">
                <select-location :tariff="tariff"></select-location>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    type="number"
                    :error-messages="errors.collect('initial_minute')"
                    color="yellow darken-3"
                    label="Время в минутах"
                    placeholder="количество минут"
                    name="initial_minute"
                    outlined
                    dense
                    v-model="tariff.fix_time.initial_minute"
                    v-validate="tariff.rules.fix_time.initial_minute"
                    data-vv-as="количество минут"
                />
                <v-text-field
                    type="number"
                    :error-messages="errors.collect('initial_minute_price')"
                    color="yellow darken-3"
                    label="Цена минуты (Пред) "
                    name="initial_minute_price"
                    outlined
                    dense
                    v-model="tariff.fix_time.initial_minute_price"
                    v-validate="tariff.rules.fix_time.initial_minute_price"
                    data-vv-as="цена"
                />
            </v-col>
<!--            <v-col cols="12" md="4">
                <div v-if="!regionsCitiesTariffsLoading">
                    <v-alert v-if="!tariff.city_ids || !tariff.city_ids.length" outlined dense type="info">
                        Сначала укажите город(а) на територии которых будет действовать тариф.
                    </v-alert>
                    <v-alert v-else-if="!regionsCitiesTariffs.length" outlined dense type="error">
                        Указанные город(а) не имеют действующих тарифов требуемого типа (регионально-городской тариф).
                    </v-alert>
                </div>
                <v-select
                    :disabled="!tariff.city_ids || !tariff.city_ids.length"
                    :loading="regionsCitiesTariffsLoading"
                    :eager="true"
                    :error-messages="errors.collect('regions_cities_tariff_id')"
                    :items="regionsCitiesTariffs"
                    color="yellow darken-3"
                    item-color="yellow darken-3"
                    item-text="name"
                    item-value="tariff_id"
                    outlined
                    dense
                    name="regions_cities_tariff_id"
                    persistent-hint
                    label="Тариф действующий после окончаия основного"
                    v-model="tariff.fix_time.regions_cities_tariff_id"
                    v-validate="tariff.rules.fix_time.regions_cities_tariff_id"
                    data-vv-as="тариф"
                    clearable
                />
            </v-col>-->
        </v-row>
    </v-container>
</template>
<script>
import SelectLocation from '../components/SelectLocation';
import axios from 'axios';
export default {
    props: ['tariff'],
    name: 'FixTimesFields',
    components: { SelectLocation },
    data() {
        return {
            regionsCitiesTariffsLoading: false,
            regionsCitiesTariffs: [],

            showFields: {
                initial_minute: null,
                initial_minute_price: null,
                regions_cities_tariff_id: null,
            },
        };
    },
    methods: {
        getRegionsCitiesTariffs() {
            this.regionsCitiesTariffsLoading = true;
            axios
                .get(`/admin/super/get/regions-cities-tariffs`, { params: { city: this.tariff.city_ids } })
                .then(response => {
                    this.regionsCitiesTariffsLoading = false;
                    this.regionsCitiesTariffs = response.data;
                    if (!this.regionsCitiesTariffs.length) {
                        this.tariff.fix_time.regions_cities_tariff_id = null;
                    }
                })
                .catch(error => {
                    this.regionsCitiesTariffsLoading = false;
                    console.log(error);
                });
        },

        setValues() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_fix_time_id) {
                this.tariff.fix_time = this.tariff.current_tariff;
                this.getRegionsCitiesTariffs();
            } else {
                this.tariff.fix_time = this.showFields;
            }
        },
    },
    watch: {
        'tariff.city_ids': function () {
            if (this.tariff.city_ids.length) {
                this.getRegionsCitiesTariffs();
            } else {
                this.regionsCitiesTariffs = [];
                this.tariff.fix_time.regions_cities_tariff_id = null;
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
    }
};
</script>
