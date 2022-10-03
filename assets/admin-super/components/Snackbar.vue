<!-- @format -->

<template>
    <v-snackbar :value="snackbar.show" :color="snackbar.color" :bottom="true" :right="true" :timeout="10">
        {{ snackbar.text }}
        <v-btn v-if="hasAction" @click="$root.$refs.policy.$emit(snackbar.action.name, snackbar.action.payload)" text>
            {{ snackbar.action.name }}
        </v-btn>

        <v-btn icon color="white" class="float-right" @click="closeSnackbar">
            <v-icon>mdi-close</v-icon>
        </v-btn>
    </v-snackbar>
</template>

<script>
// TODO: bug fix - listen to snackbar automatic close event and mutate state snackbar
export default {
    name: "Snackbar",
    methods: {
        closeSnackbar() {
            this.$store.commit("SNACKBAR", { show: false, text: "", color: undefined, action: {} });
        },
    },
    computed: {
        snackbar() {
            return this.$store.state.snackbar;
        },
        hasAction() {
            return !!Object.keys(this.snackbar.action).length;
        },
    },
};
</script>

<style scoped></style>
