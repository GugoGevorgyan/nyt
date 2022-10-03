<!-- @format -->

<template>
    <v-app-bar color="white" clipped-left app fixed>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-app-bar-nav-icon @click.stop="toggle" v-on="on"></v-app-bar-nav-icon>
            </template>
            <span>Main Menu</span>
        </v-tooltip>

        <v-toolbar-title>
            <a href="/">
                <v-avatar tile height="50" width="50">
                    <v-img src="/storage/img/logos/logo.svg" alt="Nyt" height="40" width="40" contain></v-img>
                </v-avatar>
            </a>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon text large color="yellow darken-3" v-if="auth.check">
            <v-icon color="yellow darken-3">mdi-bell-outline</v-icon>
        </v-btn>

        <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y v-if="auth.check">
            <template v-slot:activator="{ on }">
                <v-btn icon text large color="yellow darken-3" v-on="on">
                    <v-avatar size="32px" color="yellow darken-3">
                        <span class="white--text headline">{{ auth.user.name.charAt(0) }}</span>
                    </v-avatar>
                </v-btn>
            </template>

            <v-list>
                <v-card text>
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>{{ auth.user.name }}</v-list-item-title>
                                <v-list-item-subtitle>{{ auth.user.email }}</v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-form :action="logout" method="post" ref="logout">
                                    <input type="hidden" name="_token" :value="$csrf" />
                                    <v-btn color="primary" text icon @click="$refs.logout.$el.submit()">
                                        <v-icon>mdi-logout-variant</v-icon>
                                    </v-btn>
                                </v-form>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>

                    <v-divider/>

                    <v-list>
                        <v-list-item>
                            <v-list-item-action>
                                <v-switch color="yellow darken-3"></v-switch>
                            </v-list-item-action>
                            <v-list-item-title>Enable messages</v-list-item-title>
                        </v-list-item>

                        <v-list-item>
                            <v-list-item-action>
                                <v-switch color="yellow darken-3"></v-switch>
                            </v-list-item-action>
                            <v-list-item-title>Enable hints</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>

<script>
export default {
    name: 'Toolbar',
    props: {
        logout: {
            required: false,
            type: String,
            default: '/admin/super/logout',
        },
    },
    computed: {
        auth() {
            return this.$store.state.auth;
        },
        mini() {
            return this.$store.state.mini;
        },
    },
    data: () => ({
        menu: false,
        items: [{ title: 'Profile' }, { title: 'Log out' }],
    }),
    methods: {
        toggle() {
            this.$store.commit('TOGGLE', !this.mini);
        },
        active(slug) {
            return this.$route.query.property === slug;
        },
    },
};
</script>

<style scoped></style>
