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
                <map-form :station="metro" />
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
                            v-model="metro.name"
                            v-validate="metro.rules.name"
                            :error-messages="errors.collect('name')"
                        />
                        <v-text-field
                            prepend-inner-icon="mdi-video-input-antenna"
                            class="rounded-0"
                            color="yellow darken-3"
                            background-color="white"
                            outlined
                            dense
                            label="Вход"
                            name="input"
                            v-model="metro.input"
                            v-validate="metro.rules.input"
                            :error-messages="errors.collect('input')"
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
                            :items="metro.cityData"
                            item-value="city_id"
                            item-text="name"
                            v-model="metro.city"
                            v-validate="metro.rules.city"
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
                            :value="metro.address"
                            v-validate="metro.rules.address"
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
import Metro from '../../../models/stations/Metro';
import Snackbar from '../../../facades/Snackbar';
import MapComponent from '../MapComponent';
import { mapState } from 'vuex';

export default {
    name: 'MetroEditComponent',

    components: { 'map-form': MapComponent },

    props: {
        metroData: {},
        paginated: {},
    },

    data() {
        return {
            metro: new Metro(),
        };
    },

    computed: { ...mapState(['maps']) },

    methods: {
        clearFromField() {
            this.metro.address = '';
            this.metro.cord = [];
            this.maps.map.geoObjects.remove(this.maps.from);
        },

        edit() {
            this.$validator.validateAll().then(valid => {
                if (!valid) {
                    return false;
                }
                this.metro
                .update({ metro: this.metroData.metro_id })
                .then(response => {
                    this.movePayload();
                    Snackbar.info(response.data.message);
                    this.$emit('editDialog', false);
                })
                .catch(error => {
                    Snackbar.error('Data Invalid');
                    metro.errors(error.response).forEach(err => this.errors.add(err));
                });
            });
        },

        movePayload() {
            let pIndex = this.paginated._payload.findIndex(item => item.metro_id === this.metroData.metro_id);
            let cIndex = this.metro.cityData.findIndex(item => item.city_id === this.metro.city);

            this.metro.metro_id = this.metroData.metro_id;
            this.paginated._payload[pIndex].name = this.metro.name;
            this.paginated._payload[pIndex].input = this.metro.input;
            this.paginated._payload[pIndex].city.name = this.metro.cityData[cIndex].name;
            this.paginated._payload[pIndex].address = this.metro.address;
            this.paginated._payload[pIndex].cord.lat = this.metro.cord[0];
            this.paginated._payload[pIndex].cord.lut = this.metro.cord[1];
        },
    },

    created() {
        this.metro.name = this.metroData.name;
        this.metro.city = this.metroData.city.city_id;
        this.metro.input = this.metroData.input;
        this.metro.address = this.metroData.address;
        this.metro.cord = this.metroData.cord ? [this.metroData.cord.lat, this.metroData.cord.lut] : this.metroData.cord;
    },
};
</script>

<style scoped></style>
