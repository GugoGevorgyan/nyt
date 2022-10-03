<template>
    <v-card>
        <v-card-title>Место нарушения на карте
            <v-spacer />
            <v-btn
                icon
                @click="$emit('close')"
            >
                <v-icon
                    color='error'
                >mdi-close
                </v-icon>
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-actions>
            <div id="place-violation-map" style="width: 100%; height: 400px"> </div>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'PlaceViolation',
    props:['penalty'],
    data () {
        return {
            map: null,
        }
    },
    methods: {
        init() {
            this.map = new ymaps.Map('place-violation-map', {
                center: [this.penalty.lat, this.penalty.lut],
                zoom: 12,
                controls: [],
            });

            this.map.geoObjects.add(new ymaps.Placemark([this.penalty.lat, this.penalty.lut]));
    },
    },
    created() {
        ymaps.ready(this.init);
    }
};
</script>

<style scoped>

</style>
