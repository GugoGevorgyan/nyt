<!-- @format -->

<template>
    <v-card color="grey lighten-4" flat height="40" tile>
        <v-toolbar dense class="mobile-system-bar">
            <v-icon @click="drawer = !drawer" large dense left>{{ !drawer ? 'mdi-menu' : 'mdi-close' }}</v-icon>

            <v-divider vertical />

            <v-layout fill-height justify-center align-content-end>
                <v-flex col-6 offset-2 align-self-center>
                    <router-link :to="{ name: 'mobile_index' }">
                        <v-img height="auto" src="/storage/img/logos/logo-normal.png" width="auto" />
                    </router-link>
                </v-flex>
            </v-layout>

            <v-spacer />

            <router-link :to="{ name: 'mobile_auth' }">
                <v-icon size="30">mdi-login</v-icon>
            </router-link>
        </v-toolbar>
    </v-card>
</template>

<script>
export default {
    name: 'SystemBar',

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
};
</script>

<style scoped>
.mobile-system-bar {
    height: 48px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 999;
}
</style>
