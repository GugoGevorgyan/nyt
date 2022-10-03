<!-- @format -->

<template>
    <div>
        <v-autocomplete
            :items="cities"
            :loading="loading"
            clearable
            color="yellow darken-3"
            data-vv-as="города"
            deletable-chips
            item-text="name"
            dense
            item-value="city_id"
            :label="'Города ' + region.name"
            multiple
            name="city_ids"
            outlined
            prepend-icon="mdi-city"
            small-chips
            v-model="cities_ids"
        >
            <template v-slot:selection="{ item, index }">
                <v-chip small v-if="index < 1">
                    <span>{{ item.name }}</span>
                </v-chip>
                <span v-if="index === 1" class="grey--text caption">(+{{ cities_ids.length - 1 }} других) </span>
                <v-icon color="grey" v-if="index === 1">mdi-magnify</v-icon>
            </template>
        </v-autocomplete>
    </div>
</template>

<script>
import axios from 'axios';
import Snackbar from '../../facades/Snackbar';

export default {
    name: 'RegionsCities',
    props: {
        region: {
            required: true,
        },
        region_city_ids: {
            required: true,
        },
        updateMode: {
            required: true,
            type: Boolean,
        },
    },
    data() {
        return {
            cities: [],
            cities_ids: this.region_city_ids,
            loading: false,
        };
    },
    watch: {
        cities_ids: function () {
            this.$emit('updateCities', { region: this.region.region_id, cities: this.cities_ids });
        },
    },
    methods: {
        getCities() {
            this.loading = true;
            axios
                .get(`/admin/super/get/cities/` + this.region.region_id)
                .then(response => {
                    this.loading = false;
                    this.cities = response.data;
                    this.setCities(response.data);
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                    this.cities = [];
                });
        },

        setCities(data) {
            if (!this.updateMode) {
                data.forEach(item => {
                    this.cities_ids.push(item.city_id);
                });
            }
        },
    },
    created() {
        this.getCities();
    },
};
</script>
