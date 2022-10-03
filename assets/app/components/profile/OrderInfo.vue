<!-- @format -->

<template>
    <v-dialog v-model="dialog" persistent overlay-opacity="0.5" max-width="500px" width="100%">
        <v-card>
            <v-card-title>
                Информация
                <v-spacer />
                <v-icon @click="$emit('dialog')" v-text="'mdi-close'" />
            </v-card-title>
            <v-divider/>
            <v-card-subtitle class='pt-2 pb-1'>
                <v-layout column>
                    <v-flex>
                        <span>Время - {{ this.duration }} мин.</span>
                    </v-flex>
                    <v-flex>
                        <span>Расстояние - {{ data.distance }} км.</span>
                    </v-flex>
                </v-layout>
            </v-card-subtitle>

            <v-divider />

            <v-card-text>
                <div id="map" style="width: 100%; height: 400px"></div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState, mapMutations } from "vuex";
import { round } from 'lodash';

export default {
    name: "OrderInfo",

    props: {
        dialog: undefined,
        data: undefined,
    },

    computed: {
        ...mapState(["maps"]),
        duration () {
            return round(this.data.duration / 60);
        }
    },

    methods: {
        ...mapMutations({ initMap: "initMap" }),

        init() {
            this.initMap({
                map: new ymaps.Map(
                    "map",
                    {
                        center: [55.745508, 37.435225],
                        zoom: 8,
                        controls: [],
                        behaviors: ["scrollZoom", "drag", "multiTouch"],
                    },
                    {
                        minZoom: 4,
                    },
                ),
            });
                const coordinates = this.data.real_road ? this.formatCoords(this.data.real_road) : [];
                if (coordinates.length) {
                    const centerCord = coordinates[Math.round((coordinates.length - 1) / 2)];
                    this.maps.map.setCenter(centerCord);

                    const road = new ymaps.Polyline(
                        coordinates,
                        { hintContent: "Траектория" },
                        {
                            strokeColor: "#00C853",
                            strokeWidth: 5,
                            strokeOpacity: 0.5,
                        },
                    );

                    this.maps.map.geoObjects.add(road);
                } else {
                    this.maps.map.geoObjects.removeAll();
                }
        },
        formatCoords(coords) {
            let result = [];

            for (let [key, value] of Object.entries(coords)) {
                result.push([value.lat, value.lut]);
            }

            return result;
        },
        setCenter(coords) {
            if (1 === coords.length) {
                this.map.setCenter(this.formatCoords([coords[0]])[0]);
            } else {
                let center = [];
                center.push((coords[0].lat + coords[1].lat) / 2);
                center.push((coords[0].lut + coords[1].lut) / 2);
                this.map.setCenter(center);
            }
        },
    },

    mounted() {
        ymaps.ready(this.init);
    },
};
</script>
