<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <car-form :car-obj="null" :statuses="statuses" :classes="classes" :entities="entities"/>
    </v-container>
</template>

<script>
import CarForm from "./CarForm";
export default {
    components: { CarForm },
    props: {
        classes: {
            required: true,
            type: Array,
        },
        statuses: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
    },
};
</script>
