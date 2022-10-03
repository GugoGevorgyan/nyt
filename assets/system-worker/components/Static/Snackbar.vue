<!-- @format -->

<template>
    <v-snackbar
        :value="snackbar.show"
        :color="snackbar.color"
        :top="true"
        :right="true"
        :timeout="-1"
        elevation="6"
        class="mt-12"
    >
        {{ snackbar.text }}

        <v-btn v-if="hasAction" @click="$root.$refs.policy.$emit(snackbar.action.name, snackbar.action.payload)" text>
            {{ snackbar.action.name }}
        </v-btn>

        <template v-slot:action="{ attrs }">
            <v-btn v-bind="attrs" class="float-right" text icon @click="closeSnackbar">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    name: "Snackbar",

    data() {
        return {
            timer: null,
        };
    },

    watch: {
        snackbar: function (snackbar) {
            if (snackbar.show) {
                clearTimeout(this.timer);

                this.timer = setTimeout(() => {
                    this.closeSnackbar();
                }, 5000);
            }
        },
    },

    methods: {
        closeSnackbar() {
            clearTimeout(this.timer);

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
