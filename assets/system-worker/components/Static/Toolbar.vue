<!-- @format -->

<template>
    <div>
        <v-app-bar
            app
            height="55px"
            clipped-left
            fixed
            elevation="1"
            :color="darkMode ? 'black' : 'secondary'"
            :dark="darkMode"
            tile
        >
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-app-bar-nav-icon v-hotkey="toggleNav" @click.stop="toggle" v-on="on" />
                </template>
                <span>Main Menu (alt+1)</span>
            </v-tooltip>

            <v-toolbar-title>
                <a href="/" target="_blank" class="ml-5">
                    <v-avatar height="80" tile width="160">
                        <v-img alt="Nyt" contain height="80" width="160" src="/storage/img/logos/logo.png" />
                    </v-avatar>
                </a>
            </v-toolbar-title>
            <v-spacer />

            <v-row>
                <v-col col="12" md="3" xs="3" lg="3" offset="9">
                    <v-text-field
                        class="mr-3"
                        @click="searchDialog = true"
                        label="ctrl + k"
                        placeholder="Search"
                        filled
                        single-line
                        rounded
                        dense
                        hide-details
                        prepend-inner-icon="mdi-search-web"
                    />
                </v-col>
            </v-row>
            <v-divider vertical />
            <div class="mr-2">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-on="on"
                            class="mx-1"
                            @click="toggleFullScreen"
                            color="grey darken-3"
                            icon
                            small
                            v-if="auth.check"
                        >
                            <v-icon
                                :dark="!darkMode"
                                :color="darkMode ? 'white' : 'black'"
                                v-text="fullScreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen'"
                            />
                        </v-btn>
                    </template>
                    <span>{{ fullScreen ? "Выход из полноэкранного режима" : "Во весь экран" }}</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-on="on" class="mx-1" color="grey darken-3" icon small @click="toggleDarkMode">
                            <v-icon
                                v-text="'mdi-theme-light-dark'"
                                :dark="!darkMode"
                                :color="darkMode ? 'white' : 'black'"
                            />
                        </v-btn>
                    </template>
                    <span>Color mode</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            disabled
                            v-on="on"
                            class="mx-1"
                            @click="toggleMsgContent"
                            color="yellow darken-3"
                            icon
                            small
                            v-if="auth.check"
                        >
                            <v-icon :dark="!darkMode" :color="darkMode ? 'white' : 'black'">mdi-chat-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Чат</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn disabled v-on="on" class="mx-1" color="yellow darken-3" icon small v-if="auth.check">
                            <v-icon
                                :dark="!darkMode"
                                :color="darkMode ? 'white' : 'black'"
                                v-text="'mdi-bell-outline'"
                            />
                        </v-btn>
                    </template>
                    <span>Оповещения</span>
                </v-tooltip>
            </div>

            <v-divider class="mr-5" vertical />

            <v-menu
                :close-on-content-click="false"
                :nudge-width="180"
                :nudge-bottom="12.9"
                offset-y
                v-if="auth.check"
                v-model="menu"
            >
                <template v-slot:activator="{ on }">
                    <v-btn small icon v-on="on">
                        <v-avatar @click="!!fullScreen" color="yellow darken-3" size="32px">
                            <img v-if="auth.user.photo" :src="auth.user.photo" alt="John" />
                            <span v-else class="white--text headline">{{ auth.user.name.charAt(0) }}</span>
                        </v-avatar>
                    </v-btn>
                </template>

                <v-card text class="rounded-sm">
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ auth.user.name }} {{ auth.user.patronymic }} {{ auth.user.surname }}
                                </v-list-item-title>
                                <v-list-item-subtitle>{{ auth.user.email }}</v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-form :action="logout" method="post" ref="logout">
                                    <input :value="$csrf" name="_token" type="hidden" />
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                @click="$refs.logout.$el.submit()"
                                                color="yellow darken-3"
                                                icon
                                                v-bind="attrs"
                                                v-on="on"
                                            >
                                                <v-icon
                                                    :dark="!darkMode"
                                                    color="yellow darken-3"
                                                    v-text="'mdi-logout-variant'"
                                                />
                                            </v-btn>
                                        </template>
                                        <span>Выйти из системы</span>
                                    </v-tooltip>
                                </v-form>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>

                    <v-divider />

                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content
                                class="pointer"
                                @click="!enableMessages ? (enableMessages = true) : (enableMessages = false)"
                            >
                                Прием сообщений
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-switch dense v-model="enableMessages" color="yellow darken-3" />
                            </v-list-item-action>
                        </v-list-item>
                        <v-divider />
                        <v-list-item @click="sessionStop">
                            <v-list-item-content> Отдых </v-list-item-content>
                            <v-list-item-action>
                                <v-icon v-text="'mdi-food'" color="grey darken-3" />
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-menu>
            <template>
                <v-btn v-hotkey="keymaps" v-show="false" x-small />
                <v-btn v-hotkey="searchKey" v-show="false" x-small />
            </template>
        </v-app-bar>

        <v-dialog v-model="searchDialog" width="500" overlay-opacity="0.7" content-class="search-dialog">
            <search v-if="searchDialog" />
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
import Dashboard from "./../../forms/Dashboard";

export default {
    name: "Toolbar",

    props: {
        logout: {
            required: false,
            type: String,
            default: process.env.MIX_APP_WORKER_URL + "system-logout",
        },
    },

    data() {
        return {
            menu: false,
            drawer: false,
            items: [{ title: "Profile" }, { title: "Log out" }],
            dashboardModel: new Dashboard(),

            enableMessages: true,
            fullScreen: false,
            searchDialog: false,
        };
    },

    computed: {
        ...mapState({ messenger: "messenger" }),

        auth() {
            return this.$store.state.auth;
        },

        mini() {
            return this.$store.state.mini;
        },

        keymaps() {
            return {
                "ctrl+alt+l": this.sessionStop,
            };
        },

        searchKey() {
            return {
                "ctrl+k": this.startSearch,
            };
        },

        toggleNav() {
            return {
                "alt+1": this.toggle,
            };
        },

        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                let appColor = document.getElementById("app");
                if (appColor) {
                    appColor.style.backgroundColor = mode ? "#303030" : "#eee";
                }

                localStorage.setItem("color_mode", mode);
                this.$store.state.dark = mode;
            },
        },
    },

    methods: {
        ...mapMutations({ initMessenger: "initMessenger", initDashboard: "initDashboard" }),

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
        },

        toggle() {
            this.$store.commit("TOGGLE", !this.mini);
        },

        active(slug) {
            return this.$route.query.property === slug;
        },

        toggleMsgContent() {
            this.initMessenger({ toggleMsgContent: true });
            this.showWorkers();
        },

        showWorkers() {
            this.initMessenger({ toggleChatContentLoader: true });

            this.$http.get("get-rooms").then(response => {
                this.initMessenger({ chatWorkers: response.data });
                this.initMessenger({ toggleChatContentLoader: false });
            });

            this.drawer = !this.drawer;
        },

        sessionStop() {
            this.dashboardModel
                .stopSession()
                .then(result => {
                    localStorage.setItem("yui_sess_key", result.data._payload.keyValue);
                    this.initDashboard({ session: false });
                })
                .catch(error => {});
        },

        toggleFullScreen() {
            this.$helps.toggleFullScreen();
        },

        startSearch(event) {
            this.searchDialog = true;
        },
    },
};
</script>
<style>
.v-toolbar__content {
    border-bottom: solid #4a6572 2px !important;
}
</style>
