<!-- @format -->

<template>
    <v-container fluid grid-list-md>
        <v-layout no-wrap row>
            <v-flex xs12>
                <!--franchise-->
                <franchise-form
                    :franchise-obj="{}"
                    :countries="countries"
                    :entities="entities"
                    :entity-types="entityTypes"
                    :modules="modules"
                    :driver-types="driverTypes"
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import RegionsCities from './RegionsCities';
import FranchiseForm from './FranchiseForm';

export default {
    name: 'FranchiseCreate',
    components: { FranchiseForm, RegionsCities },
    props: {
        modules: {
            required: true,
            type: Array,
        },
        countries: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
        entityTypes: {
            required: true,
            type: Array,
        },
        driverTypes: {
            required: true,
            type: Array,
        },
    },
};
</script>
