<template>
    <div>
        <v-autocomplete
            :loading="countriesLoading"
            :error-messages="errors.collect('country_id')"
            :items="countries"
            color="yellow darken-3"
            item-color="yellow darken-3"
            item-text="name"
            item-value="country_id"
            outlined
            dense
            name="country_id"
            persistent-hint
            placeholder="Выберите страну"
            label="Страна"
            v-model="tariff.country_id"
            v-validate="tariff.rules.country_id"
            data-vv-as="страна"
        />
        <v-autocomplete
            :disabled="!regions.length"
            :loading="regionsLoading"
            :eager="true"
            :items="regions"
            color="yellow darken-3"
            item-color="yellow darken-3"
            item-text="name"
            item-value="region_id"
            outlined
            dense
            name="region_id"
            persistent-hint
            placeholder="Выберите регион"
            label="Регион"
            v-model="tariff.region_id"
            data-vv-as="регион"
        />
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['tariff'],
    name: 'SelectLocationRentOption',
    data() {
        return {
            countries: [],
            countriesLoading: false,

            regions: [],
            regionsLoading: false,
        };
    },

    watch: {
        'tariff.country_id': function () {
            this.tariff.region_id = null;
            this.tariff.city = [];
            this.getRegions();
            this.$emit('getRequestId', {
                country_id: this.tariff.country_id,
                region_id: this.tariff.region_id,
            });
        },
        'tariff.region_id': function () {
            this.tariff.city = [];
            this.tariff.region.push(this.tariff.region_id)
            this.$emit('getRequestId',{
                country_id: this.tariff.region_id ? null: this.tariff.country_id,
                region_id: this.tariff.region_id,
            });
        },
    },

    methods: {
        getCountries() {
            this.countriesLoading = true;
            axios
                .get(`/admin/super/get/countries`)
                .then(response => {
                    this.countriesLoading = false;
                    this.countries = response.data;
                })
                .catch(error => {
                    this.countriesLoading = false;
                    console.log(error);
                });
        },

        getRegions() {
            this.regionsLoading = true;
            axios
                .get(`/admin/super/get/regions/` + this.tariff.country_id)
                .then(response => {
                    this.regionsLoading = false;
                    this.regions = response.data.regions;
                })
                .catch(error => {
                    this.regionsLoading = false;
                    console.log(error);
                });
        },
    },

    mounted() {
        this.tariff.addComponent(this);
    },

    beforeDestroy() {
        this.tariff.removeComponent(this);
    },

    created() {
        this.getCountries();
        if (this.tariff.country_id) {
            if (this.tariff.region) {
                this.tariff.region_id = this.tariff.region[0];
            }
            this.getRegions();
        }
    },
};
</script>

<style scoped>

</style>
