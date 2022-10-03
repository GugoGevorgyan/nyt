<!-- @format -->

<template>
    <v-navigation-drawer
        app
        clipped
        permanent
        temporary
        fixed
        hide-overlay
        width="300"
        height="100%"
        class="mt-12 navigation-custom"
        v-model="drawer"
    >
        <v-list>
            <v-list-item link :to="{ name: 'mobile_tariffs' }">
                <v-list-item-icon>
                    <v-icon>mdi-guy-fawkes-mask</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>Tariffs</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item link :to="{ name: 'mobile_preorders' }">
                <v-list-item-icon>
                    <v-icon>mdi-guy-fawkes-mask</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>Preorders</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapMutations, mapState } from 'vuex';

export default {
    name: 'Drawer',

    computed: {
        drawer: {
            get() {
                return this.$store.state.drawer;
            },
            set(val) {
                this.$store.state.drawer = val;
            },
        },
    },

    methods: {},
};
</script>

<style scoped>
.navigation-custom {
    box-shadow: none !important;
}
</style>
