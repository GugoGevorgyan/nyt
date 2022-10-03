<!-- @format -->

<template
    ><v-card
        :key="`BehindCity-${c}`"
        class="mb-2"
        outlined
        v-for="(BehindCity, c) in tariff.tariff_behind_city"
        v-if="3287 !== tariff.region_id && tariff.tariffDestination !== tariff.tariff_type_id"
    >
        <v-card-title v-text="`загород`"></v-card-title>
        <v-card-text>
            <v-layout row>
                <v-flex xs12>
                    <v-select
                        :error-messages="errors.collect('tariff_behind_city.0.tariff_type')"
                        :items="getTariffTypes"
                        color="yellow darken-3"
                        item-color="yellow darken-3"
                        item-text="name"
                        outlined
                        dense
                        item-value="tariff_type_id"
                        name="tariff_behind_city.0.tariff_type"
                        placeholder="выберите тариф"
                        v-model="BehindCity.tariff_type_id"
                        v-validate="tariff.rules.behindCity.tariff_type_id"
                    ></v-select>
                </v-flex>

                <v-flex v-if="BehindCity.tariff_type_id !== tariff.tariffMinute" xs4>
                    <v-text-field
                        :data-vv-as="`${3 === tariff.tariff_type_id ? 'цена за км' : 'цена'}`"
                        :error-messages="errors.collect('tariff_behind_city.0.price')"
                        :label="`${3 === tariff.tariff_type_id ? 'цена за км' : 'цена'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        name="tariff_behind_city.0.price"
                        type="number"
                        v-model="BehindCity.price"
                        v-validate="tariff.rules.behindCity.price"
                    ></v-text-field>
                </v-flex>

                <v-flex xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.price_minute')"
                        :label="` ${3 === tariff.tariff_type_id ? 'цена за мин' : 'за км минимальный'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        name="tariff_behind_city.0.price_minute"
                        type="number"
                        outlined
                        dense
                        v-model="BehindCity.price_minute"
                        v-validate="tariff.rules.behindCity.behindPriceMinute"
                    ></v-text-field>
                </v-flex>

                <v-flex xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.minimal_price')"
                        :label="` ${3 === tariff.tariff_type_id ? 'за мин минимальный' : 'за км минимальный'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        name="tariff_behind_city.0.minimal_price"
                        type="minimal_price"
                        v-model="BehindCity.minimal_price"
                        v-validate="tariff.rules.behindCity.minimal_price"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="5 !== tariff.tariff_type_id && 3 !== tariff.tariff_type_id" xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.sit_price_km')"
                        :label="`стоимость посадки за км`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        name="tariff_behind_city.0.sit_price_km"
                        type="number"
                        v-model="BehindCity.sit_price_km"
                        v-validate="tariff.rules.behindCity.sit_price_km"
                    ></v-text-field>
                </v-flex>

                <v-flex xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.sit_price_minute')"
                        :label="`${'стоимость посадки за мин'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        name="tariff_behind_city.0.sit_price_minute"
                        type="number"
                        v-model="BehindCity.sit_price_minute"
                        v-validate="tariff.rules.behindCity.sit_price_minute"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="3 !== tariff.tariff_type_id" xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.free_wait')"
                        :label="`${'ожидание  бесплатно до поездки'}`"
                        color="yellow darken-3"
                        hint="минут"
                        outlined
                        dense
                        name="tariff_behind_city.0.free_wait"
                        type="number"
                        v-model="BehindCity.free_wait"
                        v-validate="tariff.rules.behindCity.free_wait"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="3 !== tariff.tariff_type_id" xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.free_wait_every_stop')"
                        :label="`${
                            3 === tariff.tariff_type_id
                                ? 'ожидание бесплатно во время остоновки'
                                : 'ожидание  бесплатно во время остоновки '
                        }`"
                        color="yellow darken-3"
                        hint="минут"
                        outlined
                        dense
                        name="tariff_behind_city.0.free_wait_every_stop"
                        type="number"
                        v-model="BehindCity.free_wait_every_stop"
                        v-validate="tariff.rules.behindCity.free_wait_every_stop"
                    ></v-text-field>
                </v-flex>

                <v-flex v-if="3 !== tariff.tariff_type_id" xs xs4>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.paid_wait')"
                        :label="`${'за минуты ожидание тариф'}`"
                        color="yellow darken-3"
                        hint="RUB"
                        outlined
                        dense
                        name="tariff_behind_city.0.paid_wait"
                        type="number"
                        v-model="BehindCity.paid_wait"
                        v-validate="tariff.rules.behindCity.paid_wait"
                    ></v-text-field>
                </v-flex>

                <v-flex xs5>
                    <v-select
                        :error-messages="errors.collect('tariff_behind_city.0.paid_wait_every_stop')"
                        :items="roundingText"
                        item-color="yellow darken-3"
                        item-text="name"
                        outlined
                        dense
                        item-value="rounding_price"
                        label="правило округление"
                        name="tariff_behind_city.0.paid_wait_every_stop"
                        v-model="BehindCity.paid_wait_every_stop"
                        v-validate="tariff.rules.behindCity.paid_wait_every_stop"
                    ></v-select>
                </v-flex>

                <v-flex xs5>
                    <v-select
                        :error-messages="errors.collect('tariff_behind_city.0.rounding_price')"
                        :items="roundingText"
                        item-color="yellow darken-3"
                        item-text="name"
                        dense
                        outlined
                        item-value="rounding_price"
                        label="правило округление"
                        name="tariff_behind_city.0.rounding_price"
                        v-model="BehindCity.rounding_price"
                        v-validate="tariff.rules.behindCity.rounding_price"
                    ></v-select>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.back')"
                        :label="`${'обратно'}`"
                        color="yellow darken-3"
                        hint="RUB за км"
                        outlined
                        dense
                        name="tariff_behind_city.0.back"
                        type="number"
                        v-model="BehindCity.back"
                        v-validate="tariff.rules.behindCity.back"
                    ></v-text-field>
                </v-flex>

                <v-flex xs>
                    <v-text-field
                        :error-messages="errors.collect('tariff_behind_city.0.back_minute')"
                        :label="`${'обратно'}`"
                        color="yellow darken-3"
                        hint="RUB за минут"
                        outlined
                        dense
                        name="tariff_behind_city.0.back_minute"
                        type="number"
                        v-model="BehindCity.back_minute"
                        v-validate="tariff.rules.behindCity.back_minute"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-card-text> </v-card
></template>

<script>
import Destination from '../../models/Destination';
import { mapMutations, mapState } from 'vuex';

export default {
    name: 'OutCityComponent',

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
