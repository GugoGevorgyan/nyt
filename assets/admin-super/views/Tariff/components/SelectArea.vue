<!-- @format -->

<template>
    <div style="width: 100%; height: 100%">
        <v-row no-gutters>
            <v-col cols="12" md="6">
                <v-select
                    v-if="option.hasOwnProperty('area_id')"
                    :loading="areasLoading"
                    :eager="true"
                    :error-messages="errors.collect('area_id')"
                    :items="areas"
                    color="yellow darken-3"
                    item-color="yellow darken-3"
                    item-text="title"
                    item-value="area_id"
                    outlined
                    dense
                    name="area_id"
                    persistent-hint
                    placeholder="Выберите зону тарифа"
                    label="Выбрать зону тарифа"
                    v-model="area_id"
                    v-validate="tariff.rules[tariff.option].area_id"
                    data-vv-as="зона тарифа"
                    clearable
                >
                </v-select>
            </v-col>
            <v-col cols="12" md="6" class="d-flex justify-end">
                <v-btn color="primary" @click="showAreaDialog()">Новая зона</v-btn>
            </v-col>
        </v-row>
        <div id="map" style="width: 100%; height: 100%"></div>
        <v-dialog persistent v-model="areaDialog" max-width="1500px" width="100%">
            <area-form @saved="areaSaved($event)" @close="closeAreaDialog()"></area-form>
        </v-dialog>
    </div>
</template>
<script>
import axios from 'axios';
import AreaForm from '../../Area/AreaForm';

export default {
    components: { AreaForm },
    props: ['tariff', 'option'],
    name: 'SelectArea',
    data() {
        return {
            area_id: null,
            area: null,
            areas: [],
            areasLoading: false,
            areaDialog: false,
            idsForRequest: {
                country_id: null,
                region: null,
            },
        };
    },
    watch: {
        area_id: function () {
            this.option.area_id = this.area_id;
            this.setAreaObj();
        },
        'tariff.country_id':  function() {
            this.idsForRequest.country_id = this.tariff.country_id;
            this.getAreas();
        },
        'tariff.region':  function() {
            this.idsForRequest.region = this.tariff.region;
            this.getAreas();
        }
    },
    methods: {
        showAreaDialog() {
            this.areaDialog = true;
        },

        closeAreaDialog() {
            this.areaDialog = false;
        },

        areaSaved(savedArea) {
            this.area_id = savedArea.area_id;
            this.closeAreaDialog();
            this.getAreas();
        },

        setValues() {
            this.idsForRequest.country_id = this.tariff.country_id;
            this.idsForRequest.region = this.tariff.region;
            this.area_id = this.option.area_id || null;
            if (this.idsForRequest.country_id || this.idsForRequest.region.length) {
                this.getAreas();
            }
        },

        init() {
            this.map = new ymaps.Map('map', {
                center: [55.73, 37.75],
                zoom: 10,
                controls: [],
            });

            this.setArea();
        },

        getAreas() {
            this.areasLoading = true;

            axios
                .get(`/admin/super/get/areas/${this.idsForRequest.country_id}/${this.idsForRequest.region}`)
                .then(response => {
                    this.areasLoading = false;
                    this.areas = response.data;
                    this.setAreaObj();
                })
                .catch(error => {
                    this.areasLoading = false;
                    console.log(error);
                });
        },

        getAverageNumeral(array) {
            let sum = array.reduce((a, b) => a + b, 0);
            return sum / array.length;
        },

        getCenter(coordinates) {
            let lats = [];
            let longs = [];
            coordinates.forEach(coords => {
                lats.push(Number(coords[0]));
                longs.push(Number(coords[1]));
            });

            return [this.getAverageNumeral(lats), this.getAverageNumeral(longs)];
        },

        setArea() {
            if (this.map) {
                let coords = this.area ? this.area.area : [];
                let geoObject = new ymaps.Polygon([coords]);
                this.map.geoObjects.remove(this.map.geoObjects.get(0));
                this.map.geoObjects.add(geoObject);
                coords.length ? this.map.setCenter(this.getCenter(coords)) : null;
            }
        },

        setAreaObj() {
            if (this.area_id) {
                this.area = this.areas.find(item => {
                    return item.area_id === this.area_id;
                });
            } else {
                this.area = null;
            }

            this.setArea();
        },
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    beforeDestroy() {
        this.tariff.removeComponent(this);
    },
    created() {
        this.setValues();
        ymaps.ready(this.init);
    },
};
</script>
