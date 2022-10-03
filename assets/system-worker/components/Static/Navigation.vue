<!-- @format -->

<template>
    <v-navigation-drawer
        app
        :expand-on-hover="mini"
        v-model="drawer"
        :mini-variant="mini"
        color="quaternary"
        :dark="true"
        clipped
        fixed
        overflow
        permanent
        class="elevation-0"
    >
        <v-list dense nav>
            <div v-for="menu in menus" :key="menu.menu_id">
                <!--       MULTI LINE         -->
                <v-list-group
                    v-if="menu.child.length"
                    :prepend-icon="menu.icon"
                    color="yellow darken-3"
                    v-model="menu.menu_id === active_menu_id ? true : false"
                    no-action
                >
                    <template v-slot:activator>
                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-list-item-content v-on="on" v-if="!menu.route_id">
                                    <v-list-item-title v-text="menu.title" />
                                </v-list-item-content>
                            </template>
                            <span v-text="menu.description" />
                        </v-tooltip>
                    </template>
                    <v-list-item
                        dense
                        v-for="(child, index) in menu.child"
                        :key="child.menu_id"
                        :href="$router.resolve({ name: child.route.name }).href"
                        :input-value="active($router.resolve({ name: child.route.name }).href, 'childes', menu.menu_id)"
                        active-class="white--text secondary"
                        :value="menu.menu_id"
                        link
                    >
                        <v-list-item-title v-text="child.title" />
                        <v-list-item-icon>
                            <v-icon v-text="child.icon" />
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>
                <!--       MULTI LINE END         -->

                <!--       SINGLE LINE         -->
                <v-list-item
                    v-if="!menu.child.length && !menu.parent && menu.route_id"
                    :href="$router.resolve({ name: menu.route.name }).href"
                    :input-value="active($router.resolve({ name: menu.route.name }).href)"
                    active-class="secondary"
                    link
                >
                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-list-item-icon v-on="on">
                                <v-icon v-text="menu.icon" />
                            </v-list-item-icon>
                            <v-list-item-content v-on="on">
                                <v-list-item-title v-text="menu.title" />
                            </v-list-item-content>
                        </template>
                        <span v-text="menu.description" />
                    </v-tooltip>
                </v-list-item>
                <!--       SINGLE LINE END         -->
            </div>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    name: "Navigation",

    data: () => ({
        drawer: true,
        active_menu_id: null,
    }),

    methods: {
        active(route, group = "", item) {
            if (route === this.$route.path && "childes" === group) {
                this.active_menu_id  = item;
            }

            return route === this.$route.path;
        },
    },

    computed: {
        mini: {
            get() {
                return this.$store.state.mini;
            },
            set(val) {
                this.$store.state.mini = val;
            },
        },

        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },

        currentRouteName() {
            return this.$route.name;
        },

        menus() {
            let menu = this.$store.state.menu;

            if (menu) {
                return this.$dash(menu).sortBy("title").value();
            }

            return menu;
        },
    },
};
</script>

<style>
a:hover {
    text-decoration: none !important;
}
</style>
