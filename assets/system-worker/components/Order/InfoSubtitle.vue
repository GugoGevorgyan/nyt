<!-- @format -->

<template>
    <div>
        <h5 class="font-weight-light mt-0 mb-1">{{ text }}</h5>
        <v-divider class="mx-0 mb-2 mt-0"></v-divider>
    </div>
</template>

<script>
export default {
    name: "InfoSubtitle",
    props: {
        text: {
            required: true,
        },
    },
};
</script>
