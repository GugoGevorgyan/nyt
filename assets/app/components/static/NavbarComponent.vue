<!-- @format -->

<template>
    <v-card class="secondary" color="secondary" flat height="60px" tile width="100%">
        <v-toolbar dense elevation="2" height="60px" tile class="app-toolbar">
            <a href="/" class="ml-5">
                <v-avatar height="80" tile width="160">
                    <v-img alt="Nyt" contain height="80" width="160" src="/storage/img/logos/logo.png" />
                </v-avatar>
            </a>
            <v-spacer />
            <v-slide-group>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Мой таксопарк'" />
                </v-slide-item>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Мобильное приложение'" />
                </v-slide-item>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Поддержка'" />
                </v-slide-item>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Такси для бизнеса'" />
                </v-slide-item>
                <v-slide-item>
                    <v-btn
                        href="tariffs"
                        class="menu-button"
                        color="transparent"
                        height="60px"
                        tile
                        v-text="'Тарифи'"
                    />
                </v-slide-item>
                <v-slide-item>
                    <v-menu offset-y open-on-hover>
                        <template v-slot:activator="{ on }">
                            <v-btn tile class="menu-button" color="transparent" dark height="60px" v-on="on">
                                <v-badge :content="notification.count" :value="notification.show" color="green" overlap>
                                    Станьте
                                    <v-icon small v-text="'mdi-arrow-down'" />
                                </v-badge>
                            </v-btn>
                        </template>
                        <v-list height="60px" tile>
                            <v-list-item href="/"> Партнёром </v-list-item>
                        </v-list>
                        <v-list height="60px" tile>
                            <v-list-item href="/"> Водителем </v-list-item>
                        </v-list>
                    </v-menu>
                </v-slide-item>
                <v-slide-item v-if="'undefined' !== this.client.client_id && this.client.client_id && hasViewProfile">
                    <v-menu offset-y>
                        <template v-slot:activator="{ on }">
                            <v-btn tile class="menu-button" color="transparent" dark height="60px" v-on="on">
                                <v-badge :content="notification.count" :value="notification.show" color="green" overlap>
                                    Профиль
                                    <v-icon v-text="'mdi-clipboard-arrow-down'" />
                                </v-badge>
                            </v-btn>
                        </template>
                        <v-list height="60px" tile>
                            <v-list-item href="/profile"> Профиль </v-list-item>
                        </v-list>
                    </v-menu>
                </v-slide-item>
                <v-slide-item v-if="!this.client.client_id && hasViewProfile">
                    <v-btn
                        class="menu-button"
                        href="/login-client"
                        color="transparent"
                        height="60px"
                        tile
                        v-text="'Войти в аккаунт'"
                    />
                </v-slide-item>
            </v-slide-group>
        </v-toolbar>
    </v-card>
</template>

<script>
/** @format */
import { mapState } from "vuex";

export default {
    name: "Navbar",

    data() {
        return {
            hasViewProfile: true,
        };
    },

    computed: {
        ...mapState(["notification", "client"]),
    },

    created() {
        this.hasViewProfile = !(
            window.location.pathname.includes("profile") || window.location.pathname.includes("login")
        );
    },
};
</script>

<style scoped>
.menu-button {
    box-shadow: none !important;
    font-size: 0.8em !important;
    color: gray !important;
}

.app-toolbar {
    border-bottom: solid #f9a825 2px !important;
}
</style>
