<!-- @format -->

<template>
    <v-card height="100%" class="border" elevation="4">
        <v-tabs
            fixed-tabs
            height="35"
            active-class="grey lighten-4"
            color="grey darken-3"
            v-model="tabOrder"
            @change="changeTab"
        >
            <v-tab fixed-tabs :key="item.key" v-for="item in itemsOrder">
                {{ item.title }}
            </v-tab>
            <v-btn color="orange darken-3" class="float-right rounded-0" icon @click="$emit('closeDialogue')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-tabs>
        <v-divider />

        <v-tabs-items v-model="tabOrder">
            <v-tab-item v-for="(item, index) in itemsOrder" :key="item.key">
                <div v-if="4 !== item.key" :id="'map-' + index" style="width: 100%; height: 550px; overflow: hidden" />

                <v-row v-else cols12 class="grey lighten-5" style="height: 573px; overflow-y: auto">
                    <v-col cols="7" class="pr-0 mr-0">
                        <div :id="'map-' + index" style="height: 100%; width: 100%" />
                    </v-col>

                    <v-col cols="5" class="pl-0 ml-0" v-if="data.details.in_distance && data.details.out_distance">
                        <v-layout align-content-center class="mt-4 ml-0" row>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Расстояние внутри</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.in_distance }} 'KM' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Расстояние Снаружи</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.out_distance }} 'KM' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-divider />
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Время внутри</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.in_duration }} 'М' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-divider />
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Время снаружи</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.out_duration }} 'М' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-divider />
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Цена внутри</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.in_price }} 'М' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-divider />
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Цена снаруже</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.out_price }} 'М' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                        </v-layout>

                        <v-switch dense class="mt-4 ml-3" label="Снаруже" color="yellow darken-2" />
                    </v-col>

                    <v-col v-else>
                        <v-layout align-content-center class="mt-4 ml-0" row>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Изначальная отправная точка </v-list-item-title>
                                    <v-list-item-subtitle> {{ data.order.from }} </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Изначальная цена</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ data.details.initial_price }} {{ data.order.currency }}
                                    </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Точка прибытия</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ data.details.destination_address }}
                                    </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Расстояние</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.distance }} 'KM' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Время</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.details.duration }} 'М' </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Тариф</v-list-item-title>
                                    <v-list-item-subtitle> {{ data.tariff.name }}</v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Минимальная цена </v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ data.tariff.minimal_price }} {{ data.order.currency }}</v-list-item-subtitle
                                    >
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Цена за КМ </v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ data.tariff.current_tariff ? data.tariff.current_tariff.price_km : ""}}
                                        {{ data.order.currency }}
                                    </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item sex-line>
                                <v-list-item-content>
                                    <v-list-item-title>Цена за Минути </v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ data.tariff.current_tariff ? data.tariff.current_tariff.price_min : "" }}
                                        {{ data.order.currency }}
                                    </v-list-item-subtitle>
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                        </v-layout>
                    </v-col>
                </v-row>
            </v-tab-item>
        </v-tabs-items>
    </v-card>
</template>

<script>
export default {
    name: "RoadMapComponent",

    props: {
        data: undefined,
    },

    data() {
        return {
            ex: "tetetetete",
            selectedTab: undefined,
            maps: undefined,
            geoObjects: undefined,
            tabOrder: undefined,
            itemsOrder: [
                { title: "Полный путь", key: 1 },
                { title: "От 'B' До 'C'", key: 2 },
                { title: "От 'A' До 'B'", key: 3 },
                { title: "Детали", key: 4 },
            ],
        };
    },

    methods: {
        changeTab(val) {
            this.selectedTab = val;
            ymaps.ready(this.init);
        },

        init() {
            this.maps = new ymaps.Map(
                "map-" + this.selectedTab,
                {
                    center: [
                        this.data.road.process.real_road[0].lat,
                        this.data.road.process.real_road[0].lut ?? 40.181785,
                        44.505531,
                    ],
                    zoom: 16,
                    controls: [],
                    behaviors: ["scrollZoom", "drag"],
                },
                {
                    minZoom: 10,
                },
            );

            let zoomControl = new ymaps.control.ZoomControl({
                options: {
                    layout: "round#zoomLayout",
                    maxWidth: "large",
                    size: "large",
                    position: {
                        right: 10,
                        top: 200,
                    },
                },
            });

            this.maps.controls.add(zoomControl);
            this.createRoad();
        },

        createRoad() {
            this.geoObjects = new ymaps.GeoObjectCollection();

            let realRoad = [];
            let road = [];

            if (this.selectedTab === 0) {
                realRoad = this.data.road.way.real_road.concat(this.data.road.process.real_road);
            }
            if (this.selectedTab === 1 && this.data.road.process.real_road) {
                realRoad = this.data.road.process.real_road;
            }
            if (this.selectedTab === 2 && this.data.road.way.real_road) {
                realRoad = this.data.road.way.real_road;
            }

            for (let roads of realRoad) {
                road.push([roads.lat, roads.lut]);
            }

            this.geoObjects = new ymaps.Polyline(
                road,
                {
                    hintContent: "Ломаная линия",
                },
                {
                    draggable: false,
                    strokeColor: "#7ac62a",
                    strokeWidth: 5,
                    strokeStyle: "50 0",
                },
            );

            this.maps.geoObjects.add(this.geoObjects);
        },
    },

    created() {
        console.log(this.data);
    },
};
</script>

<style scoped></style>
