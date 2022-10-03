<!-- @format -->

<template>
    <v-card height="605" width="1000" class="border" color="grey lighten-5">
        <v-system-bar class="mb-3" height="30">
            <v-card-subtitle>Редактировать информацию</v-card-subtitle>
            <v-spacer />
            <v-btn icon small @click="$emit('editDialog', false)">
                <v-icon color="red">mdi-close</v-icon>
            </v-btn>
        </v-system-bar>

        <v-row style="height: 575px; width: 100%" cols12>
            <v-col cols="7" class="m-0 pa-0">
                <map-form :station="airport" />
            </v-col>

            <v-col cols="5" class="m-0 pa-0">
                <v-layout column align-content-start class="mt-6 ml-3">
                    <v-form autocomplete="off">
                        <v-text-field
                            prepend-inner-icon="mdi-rename-box"
                            class="rounded-0"
                            color="yellow darken-3"
                            background-color="white"
                            outlined
                            dense
                            label="Имя"
                            name="name"
                            v-model="airport.name"
                            v-validate="airport.rules.name"
                            :error-messages="errors.collect('name')"
                        />
                        <v-text-field
                            prepend-inner-icon="mdi-airport"
                            class="rounded-0"
                            color="yellow darken-3"
                            background-color="white"
                            outlined
                            dense
                            label="Терминал"
                            name="terminal"
                            v-model="airport.terminal"
                            v-validate="airport.rules.terminal"
                            :error-messages="errors.collect('terminal')"
                        />
                        <v-autocomplete
                            prepend-inner-icon="mdi-city"
                            class="rounded-0"
                            background-color="white"
                            outlined
                            name="city"
                            dense
                            clearable
                            color="yellow darken-3"
                            label="Город"
                            :items="airport.cityData"
                            item-value="city_id"
                            item-text="name"
                            v-model="airport.city"
                            v-validate="airport.rules.city"
                            :error-messages="errors.collect('city')"
                        />
                        <v-text-field
                            prepend-inner-icon="mdi-location-enter"
                            class="rounded-0"
                            background-color="white"
                            outlined
                            color="yellow darken-3"
                            dense
                            id="address"
                            name="address"
                            :value="airport.address"
                            v-validate="airport.rules.address"
                            :error-messages="errors.collect('address')"
                            label="Адрес"
                            clearable
                            @click:clear="clearFromField"
                        />
                        <v-btn :disabled="errors.any()" outlined tile block class="mt-6" color="grey darken-3" @click="edit">
                            Обновить
                        </v-btn>
                    </v-form>
                </v-layout>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import Airport from '../../../models/stations/Airport';
import Snackbar from '../../../facades/Snackbar';
import MapComponent from '../MapComponent';
import { mapState } from 'vuex';

export default {
    name: 'AirportEditComponent',

    components: { 'map-form': MapComponent },

    props: {
        airportData: {},
        paginated: {},
    },

    data() {
        return {
            airport: new Airport(),
        };
    },

    computed: { ...mapState(['maps']) },

    methods: {
        clearFromField() {
            this.airport.address = '';
            this.airport.cord = [];
            this.maps.map.geoObjects.remove(this.maps.from);
        },

        edit() {
            this.$validator.validateAll().then(valid => {
                if (!valid) {
                    return false;
                }
                this.airport
                    .update({ airport: this.airportData.airport_id })
                    .then(response => {
                        this.movePayload();
                        Snackbar.info(response.data.message);
                        this.$emit('editDialog', false);
                    })
                    .catch(error => {
                        Snackbar.error('Data Invalid');
                        Airport.errors(error.response).forEach(err => this.errors.add(err));
                    });
            });
        },

        movePayload() {
            let pIndex = this.paginated._payload.findIndex(item => item.airport_id === this.airportData.airport_id);
            let cIndex = this.airport.cityData.findIndex(item => item.city_id === this.airport.city);

            this.airport.airport_id = this.airportData.airport_id;
            this.paginated._payload[pIndex].name = this.airport.name;
            this.paginated._payload[pIndex].terminal = this.airport.terminal;
            this.paginated._payload[pIndex].city.name = this.airport.cityData[cIndex].name;
            this.paginated._payload[pIndex].address = this.airport.address;
            this.paginated._payload[pIndex].cord.lat = this.airport.cord[0];
            this.paginated._payload[pIndex].cord.lut = this.airport.cord[1];
        },
    },

    created() {
        this.airport.name = this.airportData.name;
        this.airport.city = this.airportData.city.city_id;
        this.airport.terminal = this.airportData.terminal;
        this.airport.address = this.airportData.address;
        this.airport.cord = this.airportData.cord ? [this.airportData.cord.lat, this.airportData.cord.lut] : this.airportData.cord;
    },
};
</script>

<style scoped></style>
