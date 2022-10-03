<!-- @format -->

<template>
    <v-card height="100%" tile>
        <v-card-title>
            <span class="headline">{{ area.area_id ? 'Обновить зону' : 'Создать новую зону' }}</span>
            <v-spacer />
            <v-btn color="error" icon @click="$emit('close')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-text>
            <v-row class="mt-2">
                <v-col cols="12" md="4">
                    <v-form autocomplete="off">
                        <v-text-field
                            v-model="area.title"
                            v-validate="area.rules.title"
                            :error-messages="errors.collect('title')"
                            color="yellow darken-3"
                            data-vv-as="название"
                            dense
                            label="Название"
                            name="title"
                            outlined
                            class='rounded-0'
                        />
                        <v-textarea
                            v-model="area.description"
                            v-validate="area.rules.description"
                            :error-messages="errors.collect('description')"
                            color="yellow darken-3"
                            data-vv-as="описание"
                            rows="3"
                            dense
                            label="Описание"
                            name="description"
                            class='rounded-0'
                        />

                        <v-divider class='mb-3' />
                        <p>
                            Или можете создать раздел и скопировать координаты вставить поля внизу !
                            <a target="_blank" href="https://yandex.ru/map-constructor/location-tool/">Yandex</a>
                        </p>

                        <p class="text--primary red text-center"
                            >! Внимание первый и последний координат должны быть одно и то же, чтобы сформировался полигон</p
                        >
                        <v-textarea
                            v-model="area.area"
                            class='rounded-0'
                            dense
                            rows="11"
                            outlined
                            background-color="grey lighten-3"
                            color="red darken-2"
                            label="Координаты"
                        />
                    </v-form>
                </v-col>
                <v-col cols="12" md="8">
                    <v-alert v-if="!coordsValid" dense outlined type="error"> Отметьте зону на карте!</v-alert>
                    <paint-map :area-coords="area.area" @updateCoordinates="area.area = $event" />
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn tile :loading="areaLoading" color="yellow darken-3" @click="area.area_id ? updateArea() : makeArea()">
                {{ area.area_id ? 'Обновить' : 'Создать' }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import axios from 'axios';
import Snackbar from '../../facades/Snackbar';
import Area from '../../models/Area';
import PaintMap from './PaintMap';

export default {
    name: 'AreaForm',

    props: ['areaObj'],

    components: { PaintMap },

    data: () => ({
        area: new Area({}),
        areaLoading: false,
        coordsValid: true,
    }),

    watch: {
        areaObj: function () {
            this.area = new Area(this.areaObj || {});
        },
        'area.area': function () {
            this.coordsValid = true;
        },
    },

    methods: {
        makeArea() {
            this.$validator.validateAll().then(valid => {
                if (this.coordsValidate() && valid) {
                    this.areaLoading = true;
                    axios
                        .post(`/admin/super/area/create`, this.area.createFormData())
                        .then(response => {
                            this.areaLoading = false;
                            Snackbar.success(response.data.message);
                            this.$emit('saved', response.data.area);
                            this.area = new Area({});
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                let errors = error.response.data.errors;

                                for (let key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        this.errors.add({
                                            field: key,
                                            msg: errors[key][0],
                                        });
                                    }
                                }
                            }
                            this.areaLoading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        updateArea() {
            this.$validator.validateAll().then(valid => {
                if (this.coordsValidate() && valid) {
                    this.areaLoading = true;
                    let data = this.area.createFormData();
                    data.append('_method', 'put');
                    axios
                        .post(`/admin/super/area/update/` + this.area.area_id, data)
                        .then(response => {
                            this.areaLoading = false;
                            Snackbar.success(response.data.message);
                            this.$emit('saved');
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                let errors = error.response.data.errors;

                                for (let key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        this.errors.add({
                                            field: key,
                                            msg: errors[key][0],
                                        });
                                    }
                                }
                            }
                            this.areaLoading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        coordsValidate() {
            return (this.coordsValid = !!this.area.area.length);
        },
    },

    created() {
        this.area = new Area(this.areaObj || {});
    },
};
</script>
