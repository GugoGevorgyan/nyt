<!-- @format -->

<template>
    <v-card height="605" width="1000" class="border" color="grey lighten-5">
        <v-system-bar class="mb-3" height="30">
            <v-card-subtitle>Добавить ЖД станцию</v-card-subtitle>
            <v-spacer />
            <v-btn icon small @click="$emit('createDialog', false)">
                <v-icon color="grey darken-1">mdi-close</v-icon>
            </v-btn>
        </v-system-bar>

        <v-row style="height: 575px; width: 100%" cols12>
            <v-col cols="7" class="m-0 pa-0">
                <map-form :station="railway" />
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
                            v-model="railway.name"
                            v-validate="railway.rules.name"
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
                            v-model="railway.input"
                            v-validate="railway.rules.input"
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
                            :items="railway.cityData"
                            item-value="city_id"
                            item-text="name"
                            v-model="railway.city"
                            v-validate="railway.rules.city"
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
                            :value="railway.address"
                            v-validate="railway.rules.address"
                            :error-messages="errors.collect('address')"
                            label="Адрес"
                            clearable
                            @click:clear="clearFromField()"
                        />
                        <v-btn :disabled="errors.any()" outlined tile block class="mt-6" color="grey darken-3" @click="create">
                            Добавить
                        </v-btn>
                    </v-form>
                </v-layout>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import MapComponent from '../MapComponent';
import { mapState } from 'vuex';
import Railway from '../../../models/stations/Railway';
import Snackbar from '../../../facades/Snackbar';

export default {
    name: 'RailwayCreateComponent',

    components: { 'map-form': MapComponent },

    props: { paginated: {} },

    data() {
        return {
            railway: new Railway(),
        };
    },

    computed: { ...mapState(['maps']) },

    methods: {
        clearFromField() {
            this.railway.address = '';
            this.railway.cord = [];
            this.maps.map.geoObjects.remove(this.maps.from);
        },

        create() {
            this.$validator.validateAll().then(valid => {
                if (!valid) {
                    return false;
                }

                this.railway
                .store()
                .then(response => {
                    this.pushPayload(response.data._payload.railway_id);
                    this.$emit('createDialog', false);
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    Snackbar.error('Data Invalid');
                    railway.errors(error.response).forEach(err => this.errors.add(err));
                });
            });
        },

        pushPayload(railway_id) {
            let cIndex = this.railway.cityData.findIndex(item => item.city_id === this.railway.city);
            let railway = {
                railway_id: railway_id,
                name: this.railway.name,
                input: this.railway.input,
                address: this.railway.address,
                city: { name: this.railway.cityData[cIndex].name },
                cord: {
                    lat: this.railway.cord[0],
                    lut: this.railway.cord[1],
                },
            };

            this.paginated._payload.push(railway);
        },
    },
};
</script>

<style scoped></style>
