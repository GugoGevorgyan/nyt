<!-- @format -->
<template>
    <v-container fluid grid-list-lg>
        <worker-form
            :sub-phones="subPhones"
            :worker-obj="workerObj"
            :module-roles="moduleRoles"
            @submit="store()"
        />
    </v-container>
</template>

<script>
import WorkerForm from "./WorkerForm";

export default {
    name: "WorkerEdit",
    components: { WorkerForm },
    props: {
        workerObj: {
            required: true,
            type: Object,
        },
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
