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
                            @click:clear="clearFromField"
                        />
                        <v-btn
                            :disabled="errors.any()"
                            outlined
                            tile
                            block
                            class="mt-6"
                            color="grey darken-3"
                            @click="edit"
                        >
                            Обновить
                        </v-btn>
                    </v-form>
                </v-layout>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import Railway from "../../../models/stations/Railway";
import Snackbar from "../../../facades/Snackbar";
import MapComponent from "../MapComponent";
import { mapState } from "vuex";

export default {
    name: "RailwayEditComponent",

    components: { "map-form": MapComponent },

    props: {
        railwayData: {},
        paginated: {},
    },

    data() {
        return {
            railway: new Railway(),
        };
    },

    computed: { ...mapState(["maps"]) },

    methods: {
        clearFromField() {
            this.railway.address = "";
            this.railway.cord = [];
            this.maps.map.geoObjects.remove(this.maps.from);
        },

        edit() {
            this.$validator.validateAll().then(valid => {
                if (!valid) {
                    return false;
                }
                this.railway
                    .update({ railway: this.railwayData.railway_id })
                    .then(response => {
                        this.movePayload();
                        Snackbar.info(response.data.message);
                        this.$emit("editDialog", false);
                    })
                    .catch(error => {
                        Snackbar.error("Data Invalid");
                        Railway.errors(error.response).forEach(err => this.errors.add(err));
                    });
            });
        },

        movePayload() {
            let pIndex = this.paginated._payload.findIndex(item => item.railway_id === this.railwayData.railway_id);
            let cIndex = this.railway.cityData.findIndex(item => item.city_id === this.railway.city);

            this.railway.railway_id = this.railwayData.railway_id;
            this.paginated._payload[pIndex].name = this.railway.name;
            this.paginated._payload[pIndex].input = this.railway.input;
            this.paginated._payload[pIndex].city.name = this.railway.cityData[cIndex].name;
            this.paginated._payload[pIndex].address = this.railway.address;
            this.paginated._payload[pIndex].cord.lat = this.railway.cord[0];
            this.paginated._payload[pIndex].cord.lut = this.railway.cord[1];
        },
    },

    created() {
        this.railway.name = this.railwayData.name;
        this.railway.city = this.railwayData.city.city_id;
        this.railway.input = this.railwayData.input;
        this.railway.address = this.railwayData.address;
        this.railway.cord = this.railwayData.cord
            ? [this.railwayData.cord.lat, this.railwayData.cord.lut]
            : this.railwayData.cord;
    },
};
</script>

<style scoped></style>
