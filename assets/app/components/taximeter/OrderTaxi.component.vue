<!-- @format -->

<template>
    <v-menu
        v-if="!inOrderStatus"
        v-model="openForm"
        :close-on-content-click="false"
        :close-on-click="false"
        transition="slide-x-transition"
        fixed
        class="border"
    >
        <template v-slot:activator="{ on }">
            <v-btn
                v-if="!openForm"
                v-on="on"
                v-hotkey="keymap"
                class="mt-10 border"
                fab
                small
                color="white darken-3"
                dark
            >
                <v-icon color="yellow darken-3" v-text="'mdi-taxi'" />
            </v-btn>
            <v-btn
                v-else
                v-on="on"
                v-hotkey="keymap"
                class="mt-10 border transparent"
                fab
                small
                color="white darken-3"
                dark
            >
                <v-icon color="yellow darken-3" v-text="'mdi-taxi'" />
            </v-btn>
        </template>

        <OrderTaxiForm />
    </v-menu>
</template>

<script>
import OrderTaxiForm from "./form/OrderTaxiForm";
import { mapMutations, mapState } from "vuex";

export default {
    name: "OrderTaxi",

    components: { OrderTaxiForm },

    methods: {
        ...mapMutations(["initOrderForm"]),

        toggle() {
            this.initOrderForm({ open: (this.orderForm.open = true !== this.orderForm.open) });
        },
    },

    computed: {
        inOrderStatus() {
            return this.$store.state.inOrder;
        },

        openForm: {
            set(value) {
                this.$store.state.orderForm.open = value;
            },
            get() {
                return this.$store.state.orderForm.open;
            },
        },

        keymap() {
            return {
                "alt+o": this.toggle,
            };
        },
    },
};
</script>

<style scoped>
.v-menu__content {
    border-radius: 8px !important;
}
</style>
