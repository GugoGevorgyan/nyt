<!-- @format -->

<template>
    <v-card color="grey lighten-4" flat height="60px" tile width="100%">
        <v-toolbar class="app-toolbar" dense elevation="2" height="60px" tile>
            <div style="width: 185px; height: 50px">
                <a href="/">
                    <v-img height="100%" src="/storage/img/logos/logo.svg" width="auto" />
                </a>
            </div>
            <v-spacer />
            <v-slide-group>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Как стать партнёром'" />
                </v-slide-item>
                <v-slide-item>
                    <v-btn class="menu-button" color="transparent" height="60px" tile v-text="'Как стать водителем'" />
                </v-slide-item>
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
                        class="menu-button"
                        color="transparent"
                        height="60px"
                        href="tariffs"
                        tile
                        v-text="'Тарифи'"
                    />
                </v-slide-item>
            </v-slide-group>
        </v-toolbar>
    </v-card>
</template>

<script>
import { mapState } from 'vuex';

/** @format */

export default {
    name: 'Navbar',

    computed: {},

    data: () => ({}),
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
