<!-- @format -->

<template>
    <v-card
        :key="`Mkad-${l}`"
        class="mb-2"
        outlined
        v-for="(Mkad, l) in tariff.tariff_behind_mkad"
        v-if="3287 === tariff.region_id && tariff.tariffDestination !== tariff.tariff_type_id"
    >
        <v-card-title v-text="`за мкад`"></v-card-title>
        <v-card-text>
            <v-layout row>
                <v-flex xs12>
                    <v-select
                        :items="getTariffTypes"
                        color="yellow darken-3"
                        item-color="yellow darken-3"
                        item-text="name"
                        dense
                        outlined
                        item-value="tariff_type_id"
                        placeholder="выберите тариф"
                        name="tariff_behind_mkad.0.tariff_type"
                        v-model="Mkad.tariff_type_id"
                        v-validate="tariff.rules.behindMkad.tariff_type_id"
                        :error-messages="errors.collect('tariff_behind_mkad.0.tariff_type')"
                    ></v-select>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :label="`${'в пределах района до 15км'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        type="number"
                        outlined
                        dense
                        name="tariff_behind_mkad.0.price_distance_1_15"
                        v-model="Mkad.price_distance_1_15"
                        v-validate="tariff.rules.behindMkad.price_distance_1_15"
                        :error-messages="errors.collect('tariff_behind_mkad.0.price_distance_1_15')"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :label="`${'в пределах района 16-30км'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        type="number"
                        name="tariff_behind_mkad.0.price_distance_16_30"
                        v-model="Mkad.price_distance_16_30"
                        v-validate="tariff.rules.behindMkad.price_distance_16_30"
                        :error-messages="errors.collect('tariff_behind_mkad.0.price_distance_16_30')"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :label="`${'в пределах района 31-60км'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        type="number"
                        outlined
                        dense
                        name="tariff_behind_mkad.0.price_distance_31_60"
                        v-model="Mkad.price_distance_31_60"
                        v-validate="tariff.rules.price_distance_31_60"
                        :error-messages="errors.collect('tariff_behind_mkad.0.price_distance_31_60')"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('moresixteen')"
                        :label="`${'в пределах района 60км+'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="moresixteen"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.price_distance_61_more"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('overFifteenMinute')"
                        :label="`${'в пределах района до 15минут'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="overFifteenMinute"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.price_distance_1_15_minute"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('overTirtteenMinute')"
                        :label="`${'в пределах района 16-30минут'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="overTirtteenMinute"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.price_distance_16_30_minute"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('overSixteennMinute')"
                        :label="`${'в пределах района 31-60 минут'}`"
                        color="yellow darken-3"
                        hint="км"
                        name="overSixteenn"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.price_distance_31_60_minute"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('moresixteenminute')"
                        :label="`${'в пределах района 60 минут+'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="moresixteenminute"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.price_distance_61_more_minute"
                        v-validate="'required'"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="tariff.tariffMinute !== Mkad.tariff_type_id" xs6>
                    <v-text-field
                        :error-messages="errors.collect('back')"
                        :label="`${'обратно'}`"
                        color="yellow darken-3"
                        hint="RUB за км"
                        name="back"
                        outlined
                        dense
                        type="number"
                        v-model="Mkad.back"
                        v-validate="''"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="tariff.tariffKm !== Mkad.tariff_type_id" xs6>
                    <v-text-field
                        :error-messages="errors.collect('backMinute')"
                        :label="`${'обратно'}`"
                        color="yellow darken-3"
                        hint="RUB за минут"
                        name="backMinute"
                        type="number"
                        outlined
                        dense
                        v-model="Mkad.back_minute"
                        v-validate="''"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-card-text>

        <v-divider :inset="false" class="mb-5"></v-divider>
    </v-card>
</template>

<script>
import Destination from '../../models/Destination';
import { mapMutations, mapState } from 'vuex';

export default {
    name: 'OutMkadComponent',

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
