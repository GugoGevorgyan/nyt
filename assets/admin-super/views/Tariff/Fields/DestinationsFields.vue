<!-- @format -->

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="4">
                <select-location :tariff='tariff'></select-location>
            </v-col>
            <v-col cols="12" md="4">
                <v-select
                    :loading="areasLoading"
                    :eager="true"
                    :error-messages="errors.collect('destination_from_id')"
                    :items="areas"
                    color="yellow darken-3"
                    item-color="yellow darken-3"
                    item-text="title"
                    item-value="area_id"
                    outlined
                    dense
                    name="destination_from_id"
                    persistent-hint
                    label="Из зоны"
                    v-model="tariff.destination.destination_from_id"
                    v-validate="tariff.rules.destination.destination_from_id"
                    data-vv-as="из"
                    clearable
                >
                </v-select>
                <v-select
                    :loading="areasLoading"
                    :eager="true"
                    :error-messages="errors.collect('destination_to_id')"
                    :items="areas"
                    color="yellow darken-3"
                    item-color="yellow darken-3"
                    item-text="title"
                    item-value="area_id"
                    outlined
                    dense
                    name="destination_to_id"
                    persistent-hint
                    label="До зоны"
                    v-model="tariff.destination.destination_to_id"
                    v-validate="tariff.rules.destination.destination_to_id"
                    data-vv-as="до"
                    clearable
                >
                </v-select>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    type="number"
                    :error-messages="errors.collect('free_wait_stop_minutes')"
                    color="yellow darken-3"
                    label="Количество бесплатных минут"
                    hint="Количество бесплатных минут ожидания при остановке"
                    name="free_wait_stop_minutes"
                    outlined
                    dense
                    v-model="tariff.destination.free_wait_stop_minutes"
                    v-validate="tariff.rules.destination.free_wait_stop_minutes"
                    data-vv-as="количество минут ожидания"
                ></v-text-field>
                <v-text-field
                    type="number"
                    :error-messages="errors.collect('paid_wait_stop_minute')"
                    color="yellow darken-3"
                    label="Цена минуты ожидания"
                    hint="Цена минуты ожидания при остановке"
                    name="paid_wait_stop_minute"
                    outlined
                    dense
                    v-model="tariff.destination.paid_wait_stop_minute"
                    v-validate="tariff.rules.destination.paid_wait_stop_minute"
                    data-vv-as="цена минуты ожидания"
                ></v-text-field>
            </v-col>
            <v-col cols='12' md='4'>
                <sitting-fee :option='tariff.destination' :tariffObj='tariff'></sitting-fee>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="12" style="height: 600px">
                <show-areas :areas="mapAreas" />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from 'axios';
import SelectLocation from '../components/SelectLocation';
import ShowAreas from './../components/ShowAreas';
import SittingFee from './../components/SittingFee';

export default {
    props: ['tariff'],
    name: 'DestinationFields',
    components: { ShowAreas, SelectLocation, SittingFee },
    data() {
        return {
            areas: [],
            areasLoading: false,
            fromArea: [],
            toArea: [],
            showFields: {
                price: null,
                destination_from_id: null,
                destination_to_id: null,
                free_wait_stop_minutes: null,
                paid_wait_stop_minute: null,
                price_type_id: this.tariff.tariff_type_id,
                sitting_fee: null,
                sit_fix_price: null,
                sit_price_km: null,
                sit_price_minute: null,
            },
            sittingIsPaid: false,
            isNull: false
        };
    },
    computed: {
        mapAreas: function () {
            return [this.fromArea, this.toArea];
        },
    },
    watch: {
        'tariff.destination.destination_from_id': function () {
            this.setFromArea();
        },
        'tariff.destination.destination_to_id': function () {
            this.setToArea();
        },
        'tariff.country_id': function() {
            this.getAreas()
        },
        'tariff.region': function() {
            this.getAreas()
        },
        'tariff.minimal_price': function() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_destination_id) {
                this.tariff.current_tariff.price = this.tariff.minimal_price
            } else {
                this.showFields.price = this.tariff.minimal_price
            }
        }
    },
    methods: {
        setValues() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_destination_id) {
                this.tariff.destination = this.tariff.current_tariff;
            } else {
                this.tariff.destination = this.showFields;
            }
        },
        setFromArea() {
            let area = this.areas.find(item => {
                return item.area_id === this.tariff.destination.destination_from_id;
            });
            this.fromArea = area ? area.area : [];
        },
        setToArea() {
            let area = this.areas.find(item => {
                return item.area_id === this.tariff.destination.destination_to_id;
            });
            this.toArea = area ? area.area : [];
        },
        getAreas() {
            this.areasLoading = true;
            axios
                .post(`/admin/super/get/specific-areas`, {
                    country_id: this.tariff.country_id,
                    region: this.tariff.region,
                })
                .then(response => {
                    this.areasLoading = false;
                    this.areas = response.data;
                    this.setFromArea();
                    this.setToArea();
                })
                .catch(error => {
                    this.areasLoading = false;
                    console.log(error);
                });
        },
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    beforeDestroy() {
        this.tariff.removeComponent(this);
    },
    created() {
        if (this.tariff.country_id) {
            this.getAreas();
        }
        this.setValues();
    },
};
</script>
