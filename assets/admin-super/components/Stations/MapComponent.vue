<!-- @format -->

<template>
    <div id="create-map" style="width: 100%; height: 100%"></div>
</template>

<script>
import MapMix from '../../mixins/MapMix';

export default {
    name: 'MapComponent',

    props: { station: {} },

    mixins: [MapMix],

    data() {
        return {
            suggestViewFrom: undefined,
            markGeoObjects: undefined,
            marks: [],
        };
    },

    watch: {
        ['station.address'](val) {
            if (val) {
                ymaps.geocode(val).then(res => {
                    const firstGeoObject = res.geoObjects.get(0);
                    const coords = firstGeoObject.geometry.getCoordinates();

                    if (!this.markGeoObjects) {
                        this.markGeoObjects = new ymaps.GeoObjectCollection();
                    }

                    const from = 'A';

                    let indexFrom = this.marks.findIndex(item => {
                        return from === item.id;
                    });

                    this.markSet(from, coords, indexFrom);
                });
            }
        },
    },

    methods: {
        initSuggest() {
            this.suggestViewFrom = new ymaps.SuggestView('address');

            this.suggestViewFrom.events.add('select', e => {
                this.station.address = e.get('item').value;

                ymaps.geocode(e.get('item').value).then(res => {
                    this.station.cord = res.geoObjects.get(0).geometry._coordinates;
                });
            });
        },
    },

    created() {
        ymaps.ready(this.init).then(result => {
            if (!this.markGeoObjects) {
                this.markGeoObjects = new ymaps.GeoObjectCollection();
                this.maps.map.geoObjects.add(this.markGeoObjects);
            }

            let mark = this.createMark('A', this.station.cord);
            this.maps.map.geoObjects.add(mark);
        });
        ymaps.ready(this.initSuggest).then(res => {});
    },
};
</script>

<style scoped></style>
