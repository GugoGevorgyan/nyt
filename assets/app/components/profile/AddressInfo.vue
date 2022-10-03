<template>
    <v-dialog v-model='addressInfo' persistent overlay-opacity="0.5" max-width="500px" width="100%">
        <v-card>
            <v-card-title>
                Информация
                <v-spacer />
                <v-icon @click="$emit('close')" v-text="'mdi-close'" />
            </v-card-title>

            <v-card-text>
                <div id="map" style="width: 100%; height: 400px"></div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState, mapMutations } from "vuex";
export default {
    name: 'AddressInfo',
    props: ['coordinates', 'addressInfo'],
    computed: {
        ...mapState(["maps"]),
    },
    methods: {
        ...mapMutations({ initMap: "initMap" }),

        init() {
            this.initMap({
                map: new ymaps.Map(
                    "map",
                    {
                        center: this.coordinates,
                        zoom: 12,
                        controls: [],
                        behaviors: ["scrollZoom", "drag", "multiTouch"],
                    },
                    {
                        minZoom: 4,
                    },
                ),
            });
            this.maps.map.geoObjects
                .add(new ymaps.Placemark(this.coordinates, {
                    balloonContent: 'цвет <strong>воды пляжа бонди</strong>'
                }))
        }
    },
    created() {
        ymaps.ready(this.init);
    }
};
</script>

<style scoped>

</style>
