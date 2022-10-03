<!-- @format -->

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
            v-if='tariff.tariff_type_id === 4'
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
            v-model="tariff.region"
            data-vv-as="регион"
            multiple
            @input='limiter'
            clearable
        />
        <v-autocomplete
            v-else
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
            v-model="tariff.region[0]"
            data-vv-as="регион"
            clearable
        />
    </div>
</template>
<script>
import axios from 'axios';

export default {
    props: ['tariff'],
    name: 'SelectLocation',

    data() {
        return {
            countries: [],
            countriesLoading: false,

            regions: [],
            regionsLoading: false,
            regionsLimitWarning: false,
        };
    },

    watch: {
        'tariff.country_id': function () {
            this.tariff.region_id = null;
            this.tariff.city = [];

            this.getRegions();
        }
    },

    methods: {
        limiter(e) {
            if(e && e.length > 2) {
                this.regionsLimitWarning = true;
                e.pop()
                confirm('Вы можете выбрать до двух регионов')
            } else {
                this.regionsLimitWarning = false;
            }
        },
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
                    this.regions = Object.values(response.data.regions);
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
            this.getRegions();
        }

        this.tariff.setCity();
    },
};
</script>
