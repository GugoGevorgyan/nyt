<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <worker-form :sub-phones="subPhones" :worker-obj="null" :module-roles="moduleRoles" @submit="store()" />
    </v-container>
</template>

<script>
import WorkerForm from "./WorkerForm";

export default {
    name: "WorkerCreate",

    components: { WorkerForm },

    props: {
        moduleRoles: {
            required: true,
            type: Array,
        },
        subPhones: {
            required: true,
            type: Array,
        },
    },
};
</script>
