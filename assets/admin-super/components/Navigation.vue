<!-- @format -->

<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <v-navigation-drawer v-model="drawer" :mini-variant="mini" clipped fixed overflow app width='230'>
        <v-list dense nav tile>
            <v-list-item
                dense
                :href="$router.resolve({ name: 'admin.super.dashboard' }).href"
                :input-value="active($router.resolve({ name: 'admin.super.dashboard' }).href)"
                active-class="yellow--text text--darken-3"
                link
            >
                <v-list-item-action>
                    <v-icon>mdi-view-dashboard-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item
                dense
                :href="$router.resolve({ name: 'admin.super.franchises' }).href"
                :input-value="active($router.resolve({ name: 'admin.super.franchises' }).href)"
                active-class="yellow--text text--darken-3"
                link
            >
                <v-list-item-action>
                    <v-icon>mdi-toolbox-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Franchise's</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item
                :href="$router.resolve({ name: 'admin.super.tariff.index' }).href"
                :input-value="active($router.resolve({ name: 'admin.super.tariff.index' }).href)"
                active-class="yellow--text text--darken-3"
                link
                dense
            >
                <v-list-item-action>
                    <v-icon>mdi-currency-usd</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Tariffs</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item
                :href="$router.resolve({ name: 'admin.super.area.index' }).href"
                :input-value="active($router.resolve({ name: 'admin.super.area.index' }).href)"
                active-class="yellow--text text--darken-3"
                link
                dense
            >
                <v-list-item-action>
                    <v-icon>mdi-map-search-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Areas</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-group prepend-icon="mdi-artstation" color="yellow darken-3" :value="station" no-action>
                <template v-slot:activator>
                    <v-list-item-content>
                        <v-list-item-title>Stations</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-item
                    dense
                    :href="$router.resolve({ name: 'admin.super.station.airport' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.station.airport' }).href, 'station', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="1"
                    link
                >
                    <v-list-item-title>Airports</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-airport</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    dense
                    :href="$router.resolve({ name: 'admin.super.station.metro' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.station.metro' }).href, 'station', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="1"
                    link
                >
                    <v-list-item-title>Metros</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-metronome</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    dense
                    :href="$router.resolve({ name: 'admin.super.station.railway' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.station.railway' }).href, 'station', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="1"
                    link
                >
                    <v-list-item-title>Railway stations</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-metronome-tick</v-icon>
                    </v-list-item-icon>
                </v-list-item>
            </v-list-group>

            <v-list-group prepend-icon="mdi-settings-outline" color="yellow darken-3" :value="system" no-action>
                <template v-slot:activator>
                    <v-list-item-content>
                        <v-list-item-title>System</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-item
                    :href="$router.resolve({ name: 'admin.super.regions' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.regions' }).href, 'system', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="1"
                    link
                >
                    <v-list-item-title>Regions</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-city-variant</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    :href="$router.resolve({ name: 'admin.super.cities' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.cities' }).href, 'system', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="1"
                    link
                >
                    <v-list-item-title>Cities</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-city</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    :href="$router.resolve({ name: 'admin.super.modules' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.modules' }).href, 'system', 1)"
                    active-class="yellow--text text--darken-3"
                    :value="2"
                    dense
                    link
                >
                    <v-list-item-title>Modules</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-view-module</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    :href="$router.resolve({ name: 'admin.super.roles' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.roles' }).href, 'system', 2)"
                    active-class="yellow--text text--darken-3"
                    :value="3"
                    link
                >
                    <v-list-item-title>Roles</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-account-tie</v-icon>
                    </v-list-item-icon>
                </v-list-item>

                <v-list-item
                    :href="$router.resolve({ name: 'admin.super.permissions' }).href"
                    :input-value="active($router.resolve({ name: 'admin.super.permissions' }).href, 'system', 3)"
                    active-class="yellow--text text--darken-3"
                    :value="4"
                    link
                >
                    <v-list-item-title>Permissions</v-list-item-title>
                    <v-list-item-icon>
                        <v-icon>mdi-account-key-outline</v-icon>
                    </v-list-item-icon>
                </v-list-item>
            </v-list-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    name: 'Navigation',
    data: () => ({
        drawer: true,
        system: 0,
        station: 0,
    }),
    methods: {
        /**
         * Activate current route link
         *
         * @param route
         * @param {String} group
         * @param {Number} item
         * @return {boolean}
         */
        active(route, group = '', item) {
            if (route === this.$route.path && 'system' === group) {
                this.system = item;
            }

            return route === this.$route.path;
        },
    },
    computed: {
        mini() {
            return this.$store.state.mini;
        },
    },
    watch: {},
};
</script>

<style scoped></style>
