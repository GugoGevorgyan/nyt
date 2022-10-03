<!-- @format -->

<template>
    <div style="height: 100%; overflow-y: auto; width: 100%; overflow-x: hidden" class="mb-1">
        <v-overlay v-if="reCalcLoader" absolute opacity="0.1">
            <v-progress-circular :size="50" color="amber" indeterminate />
        </v-overlay>
        <h3 class="d-flex align-center justify-center mt-3">Пересечение</h3>
        <div v-if="hasDetails && hasCrossing">
            <v-layout align-content-center class="ml-3 mr-3" row>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Расстояние внутри</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ crossing.in_distance }} 'KM'
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>

                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Расстояние Снаружи</v-list-item-title>
                        <v-edit-dialog @save="reCalcDistance">
                            {{ crossing.out_distance }} KM
                            <v-icon color="green" small v-text="'mdi-marker'" />
                            <template v-slot:input>
                                <v-text-field
                                    class="font-weight-black"
                                    type="number"
                                    hide-details
                                    v-model="crossing.out_distance"
                                    single-line
                                    dense
                                />
                            </template>
                        </v-edit-dialog>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>

                <v-divider />
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Время внутри</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ crossing.in_duration }} 'М'
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>

                <v-divider />
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Время снаружи</v-list-item-title>

                        <v-edit-dialog @save="reCalcDuration">
                            {{ crossing.out_duration }} М
                            <v-icon color="green" small v-text="'mdi-marker'" />
                            <template v-slot:input>
                                <v-text-field
                                    class="font-weight-black"
                                    type="number"
                                    hide-details
                                    v-model="crossing.out_duration"
                                    single-line
                                    dense
                                />
                            </template>
                        </v-edit-dialog>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>

                <v-divider />
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Цена внутри</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ "₽ " + new Intl.NumberFormat("de-DE").format(crossing.in_price) }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>

                <v-divider />
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Цена снаруже</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ "₽ " + new Intl.NumberFormat("de-DE").format(crossing.out_price) }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
            </v-layout>
        </div>
        <div v-else>
            <div class="transition-swing text--primary ml-6" v-text="'Пересечение не было'"> </div>
        </div>

        <!--    Основная    -->
        <div>
            <h3 class="d-flex align-center justify-center mt-5">Основная</h3>
            <v-layout align-content-center class="ml-3 mr-3" row v-if="!tariffs.length">
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Изначальная цена </v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ "₽ " + new Intl.NumberFormat("de-DE").format(order.initial_price) }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Изначальная отправная точка </v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ order.address_from | formatAdd }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Изначальный пункт отправления </v-list-item-title>
                        <v-list-item-subtitle v-if="order.address_to" class="font-weight-black">
                            {{ order.address_to | formatAdd }}
                        </v-list-item-subtitle>
                        <v-list-item-subtitle v-else class="font-weight-black" v-text="'NONE'" />
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Пункт прибытия</v-list-item-title>
                        <v-list-item-subtitle v-if="details.destination_address" class="font-weight-black">
                            {{ details.destination_address | formatAdd }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Расстояние</v-list-item-title>
                        <v-edit-dialog v-if="!hasCrossing" @save="reCalcDistance">
                            {{ details.distance }} KM
                            <v-icon color="green" small v-text="'mdi-marker'" />
                            <template v-slot:input>
                                <v-text-field
                                    class="font-weight-black"
                                    type="number"
                                    hide-details
                                    v-model="details.distance"
                                    single-line
                                    dense
                                />
                            </template>
                        </v-edit-dialog>
                        <v-list-item-subtitle v-else class="font-weight-black">
                            {{ details.distance }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">МИНУТА</v-list-item-title>
                        <v-edit-dialog v-if="!hasCrossing" @save="reCalcDuration" style="overflow: hidden">
                            {{ details.duration }} МИНУТ
                            <v-icon color="green" small v-text="'mdi-marker'" />
                            <template v-slot:input>
                                <v-text-field
                                    class="font-weight-black"
                                    type="number"
                                    hide-details
                                    v-model="details.duration"
                                    single-line
                                    dense
                                />
                            </template>
                        </v-edit-dialog>
                        <v-list-item-subtitle v-else class="font-weight-black">
                            {{ details.duration }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Тарифф</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black"> {{ tariffs.name }}</v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Минимальная цена</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{
                                "₽ " + new Intl.NumberFormat("de-DE").format(tariffs.minimal_price)
                            }}</v-list-item-subtitle
                        >
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Цена за км</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ "₽ " + new Intl.NumberFormat("de-DE").format(tariffs.current_tariff.price_km) }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Цена за минуты</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{ "₽ " + new Intl.NumberFormat("de-DE").format(tariffs.current_tariff.price_min) }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Цена за сидение</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            {{
                                "₽ " +
                                new Intl.NumberFormat("de-DE").format(
                                    tariffs.current_tariff.sitting_fee
                                        ? tariffs.current_tariff.sit_price_km + tariffs.current_tariff.sit_price_min
                                        : "отсуствует",
                                )
                            }}
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Дополнительные опции</v-list-item-title>
                        <v-list-item-subtitle
                            class="font-weight-black"
                            v-if="order.car_options.length"
                            v-for="(option, index) of order.car_options"
                            :key="index"
                        >
                            {{ option.name }} {{ "₽ " + new Intl.NumberFormat("de-DE").format(option.price) }}
                        </v-list-item-subtitle>
                        <v-list-item-subtitle v-else> NONE </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item sex-line>
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-light">Окончательная цена</v-list-item-title>
                        <v-list-item-subtitle class="font-weight-black">
                            <v-edit-dialog @save="changePrice">
                                {{ "₽ " + new Intl.NumberFormat("de-DE").format(details.cost ? details.cost : "NUN") }}
                                <v-icon color="green" small v-text="'mdi-marker'" />
                                <template v-slot:input>
                                    <v-text-field
                                        class="font-weight-black"
                                        type="number"
                                        hide-details
                                        v-model="details.cost"
                                        single-line
                                        dense
                                    />
                                </template>
                            </v-edit-dialog>
                        </v-list-item-subtitle>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
            </v-layout>
            <v-layout v-else>
                <div class="transition-swing text--primary ml-6" v-text="'Пока не доступно '" />
            </v-layout>
        </div>
    </div>
</template>

<script>
import Snackbar from "../../../../facades/Snackbar";

export default {
    name: "OrderInfoDetail",

    props: {
        details: {
            required: true,
        },
        crossing: {
            required: true,
        },
        tariffs: {
            required: true,
        },
        order: {
            required: true,
        },
    },

    data() {
        return {
            hasDetails: true,
            hasCrossing: true,
            reCalcLoader: false,
        };
    },

    methods: {
        changePrice() {
            this.reCalcLoader = true;
            this.$http
                .put(`call-center-dispatcher/od-re-calc/${this.order.order_id}`, {
                    cost: this.details.cost,
                })
                .then(response => {
                    this.$emit("details", response.data);
                    this.reCalcLoader = false;
                })
                .catch(error => {
                    this.reCalcLoader = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        reCalcDistance() {
            this.reCalcLoader = true;
            this.$http
                .put(`call-center-dispatcher/od-re-calc/${this.order.order_id}`, {
                    distance: this.hasCrossing ? this.crossing.out_distance : this.details.distance,
                    cross: this.hasCrossing,
                })
                .then(response => {
                    this.$emit("details", response.data);
                    this.reCalcLoader = false;
                })
                .catch(error => {
                    this.reCalcLoader = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        reCalcDuration() {
            this.reCalcLoader = true;
            this.$http
                .put(`call-center-dispatcher/od-re-calc/${this.order.order_id}`, {
                    duration: this.hasCrossing ? this.crossing.out_duration : this.details.duration,
                    cross: this.hasCrossing,
                })
                .then(response => {
                    this.$emit("details", response.data);

                    this.reCalcLoader = false;
                })
                .catch(error => {
                    this.reCalcLoader = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },

    created() {
        this.hasDetails = this.details.length ? true : false;
        this.hasCrossing = this.crossing.length ? true : false;
    },
};
</script>

<style scoped></style>
