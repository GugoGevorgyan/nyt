<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <entity-form :entity-obj="null" :countries="countries" :entity-types="entityTypes" />
    </v-container>
</template>

<script>
import EntityForm from "../form/EntityForm";
export default {
    components: { EntityForm },
    props: {
        entityTypes: {
            required: true,
            type: Array,
        },
        countries: {
            required: true,
            type: Array,
        },
    },
};
</script>
