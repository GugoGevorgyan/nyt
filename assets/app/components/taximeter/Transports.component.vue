<!-- @format -->

<template>
    <v-card class="transport-card" elevation="6" v-if="orderForm.fromClick || orderForm.toClick">
        <v-tabs height="44px" background-color="white" color="grey darken-3" fixed-tabs v-model="tab">
            <v-tab
                :key="item.title"
                background-color="grey lighten-3"
                light
                slider-color="yellow darken-2"
                v-for="item in items"
            >
                {{ item.title }}
            </v-tab>
        </v-tabs>

        <v-card-text>
            <v-tabs-items v-model="tab">
                <v-tab-item :key="item.tab" v-for="item in items">
                    <!--TRANSPORTS-->
                    <v-autocomplete
                        :items="transports.metros"
                        background-color="grey lighten-5"
                        color="yellow darken-3"
                        dense
                        eager
                        item-text="name"
                        item-value="metro_id"
                        label="Select Metro"
                        outlined
                        v-if="item.title === 'metro'"
                        v-model="metroItem"
                    />
                    <v-autocomplete
                        :loading="!transports.airports.length"
                        loader-height="2"
                        :items="transports.airports"
                        background-color="grey lighten-5"
                        color="yellow darken-3"
                        dense
                        eager
                        item-text="name"
                        item-value="airport_id"
                        label="Select Airport"
                        outlined
                        v-if="item.title === 'airport'"
                        v-model="airportItem"
                    />
                    <v-autocomplete
                        :items="transports.stations"
                        :loading="!transports.stations.length"
                        loader-height="2"
                        background-color="grey lighten-5"
                        color="yellow darken-3"
                        dense
                        eager
                        item-text="name"
                        item-value="railway_station_id"
                        label="Select Station"
                        outlined
                        v-if="item.title === 'station'"
                        v-model="stationItem"
                    />
                    <!--END TRANSPORTS-->

                    <!--MEET-->
                    <v-form autocomplete="off">
                        <div v-if="order.meet.is_meet && orderForm.fromClick">
                            <v-text-field
                                background-color="grey lighten-5"
                                color="yellow darken-2"
                                dense
                                label="Текст на табличке"
                                outlined
                                v-model="order.meet.text"
                            />
                            <v-text-field
                                background-color="grey lighten-5"
                                color="yellow darken-2"
                                dense
                                label="Number"
                                outlined
                                v-model="order.meet.number"
                            />
                        </div>
                    </v-form>
                    <!--END MEET-->

                    <v-switch
                        class="float-right mt-0"
                        color="yellow darken-3"
                        dense
                        label="Встреча"
                        v-if="orderForm.fromClick"
                        v-model="order.meet.is_meet"
                    />
                </v-tab-item>
            </v-tabs-items>
            <v-btn @click="initOrderForm({ fromClick: false, toClick: false })" icon outlined small tile>
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
        </v-card-text>
    </v-card>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import { Map } from "../../services";

export default {
    name: "TransportComponent",

    mixins: [Map],

    data() {
        return {
            tab: null,
            items: [{ title: "metro" }, { title: "airport" }, { title: "station" }],
            metroItem: null,
            airportItem: null,
            stationItem: null,
        };
    },

    computed: {
        ...mapState(["orderForm", "transports", "order", "maps"]),
    },

    watch: {
        metroItem(val) {
            let search = this.transports.metros.find(item => item.metro_id === val);
            this.removeMapPayload();

            ymaps.geocode([search.lat, search.lut]).then(res => {
                let geoObject = res.geoObjects.get(0);

                if (this.orderForm.fromClick) {
                    this.modelsMeetData(geoObject.getAddressLine(), geoObject.geometry.getCoordinates(), "metro", val);
                } else {
                    this.initOrder({
                        address_to_coordinates: geoObject.geometry.getCoordinates(),
                        address_to: geoObject.getAddressLine(),
                    });
                }
            });
        },

        airportItem(val) {
            let search = this.transports.airports.find(item => item.airport_id === val);
            this.removeMapPayload();

            ymaps.geocode([search.lat, search.lut]).then(res => {
                let geoObject = res.geoObjects.get(0);

                if (this.orderForm.fromClick) {
                    this.modelsMeetData(
                        geoObject.getAddressLine(),
                        geoObject.geometry.getCoordinates(),
                        "airport",
                        val,
                    );
                } else {
                    this.initOrder({
                        address_to_coordinates: geoObject.geometry.getCoordinates(),
                        address_to: geoObject.getAddressLine(),
                    });
                }
            });
        },

        stationItem(val) {
            let search = this.transports.stations.find(item => item.railway_station_id === val);
            this.removeMapPayload();

            ymaps.geocode([search.lat, search.lut]).then(res => {
                let geoObject = res.geoObjects.get(0);
                if (this.orderForm.fromClick) {
                    this.modelsMeetData(
                        geoObject.getAddressLine(),
                        geoObject.geometry.getCoordinates(),
                        "station",
                        val,
                    );
                } else {
                    this.initOrder({
                        address_to_coordinates: geoObject.geometry.getCoordinates(),
                        address_to: geoObject.getAddressLine(),
                    });
                }
            });
        },
    },

    methods: {
        ...mapMutations({
            initOrderForm: "initOrderForm",
            initTransports: "initTransports",
            initOrder: "orderInit",
            initMap: "initMap",
        }),

        modelsMeetData(address, coordinate, type, id) {
            if (this.order.meet.is_meet) {
                this.initOrder({
                    address_from_coordinates: coordinate,
                    address_from: address,
                    meet: { transport_id: id, type: type },
                });
            } else {
                this.initOrder({
                    address_from_coordinates: coordinate,
                    address_from: address,
                    meet: { transport_id: id, type: type },
                });
            }
        },

        removeMapPayload() {
            if (this.orderForm.fromClick) {
                this.initOrder({ address_from: "", address_from_coordinates: [] });
                this.initMap({ from: {}, from_name: "" });
                this.initOrderForm({ priceText: this.orderForm.priceTextDefault });
                this.maps.map.geoObjects.remove(this.maps.from);
            } else {
                this.initOrder({ address_to: "", address_to_coordinates: [] });
                this.initMap({ to: {}, to_name: "" });
                this.initOrderForm({ priceText: this.orderForm.priceTextDefault });
                this.maps.map.geoObjects.remove(this.maps.to);
            }
        },
    },
};
</script>

<style scoped>
.transport-card {
    border-radius: 8px !important;
}
</style>
