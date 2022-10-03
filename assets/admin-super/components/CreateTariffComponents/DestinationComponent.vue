<!-- @format -->

<template>
    <v-card :key="`destination-${d}`" class="mb-2" outlined v-for="(destination, d) in tariff.destinations">
        <v-card-title v-text="`destination: ${d + 1}`"></v-card-title>

        <v-card-text>
            <v-layout row>
                <v-flex xs6>
                    <v-autocomplete
                        :error-messages="errors.collect('destinationTariffType')"
                        :items="destinationAreas"
                        clearable
                        color="yellow darken-3"
                        item-color="yellow darken-3"
                        item-text="name"
                        item-value="destination_area_id"
                        label="Address from area"
                        name="destinationTariffType"
                        outlined
                        dense
                        v-model="destination.locations.from"
                        v-validate="tariff.rules.destination.tariffType"
                    ></v-autocomplete>
                </v-flex>

                <v-flex xs6>
                    <v-autocomplete
                        :items="destinationAreas"
                        clearable
                        color="yellow darken-3"
                        item-color="yellow darken-3"
                        item-text="name"
                        item-value="destination_area_id"
                        dense
                        outlined
                        label="Address to area"
                        v-model="destination.locations.to"
                    ></v-autocomplete>
                </v-flex>

                <v-flex xs12>
                    <v-radio-group row v-model="tariff.car_class_id">
                        <v-radio
                            :key="car.car_class_id"
                            :label="car.class_name"
                            :value="car.car_class_id"
                            color="yellow darken-3"
                            v-for="car in getCars"
                        ></v-radio>
                    </v-radio-group>
                </v-flex>

                <v-flex xs12>
                    <v-text-field
                        :error-messages="errors.collect('price')"
                        color="yellow darken-3"
                        hint="RUB"
                        label="цена"
                        name="price"
                        type="number"
                        outlined
                        dense
                        v-model="destination.price"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex xs5>
                    <v-text-field
                        :error-messages="errors.collect('intent')"
                        :label="` ${'ожидание бесплатно'}`"
                        color="yellow darken-3"
                        hint="на минуту"
                        name="intent"
                        type="number"
                        outlined
                        dense
                        v-model="destination.free_wait"
                    ></v-text-field>
                </v-flex>

                <v-flex xs5>
                    <v-text-field
                        :error-messages="errors.collect('intent')"
                        :label="` ${'за минуты ожидание тариф'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="intent"
                        type="number"
                        outlined
                        dense
                        v-model="destination.paid_wait"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-card-text>
    </v-card>
</template>

<script>
import Destination from '../../models/Destination';
import { mapMutations, mapState } from 'vuex';
import MapComponent from './MapComponent';

export default {
    name: 'DestinationComponent',

    components: {
        MapComponent: MapComponent,
    },

    data: () => ({
        destination: new Destination(),
    }),

    computed: {
        ...mapState([]),
    },

    methods: {
        ...mapMutations([]),
    },
};
</script>

<style></style>
