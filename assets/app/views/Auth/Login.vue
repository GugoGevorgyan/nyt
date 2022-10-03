<!-- @format -->

<template>
    <div :style="{ height: window.height + 'px' }">
        <navbar />
        <v-tabs background-color="white" color="deep-purple" light fixed-tabs>
            <v-tabs-slider class="mb-5" />

            <v-tabs slider-color="grey darken-3" v-model="tab" background-color="grey lighten-3">
                <v-tab v-for="item in items" :key="item.title" :to="item.url">{{ item.title }}</v-tab>
            </v-tabs>
        </v-tabs>

        <v-container fluid style="height: 100%">
            <router-view
                :send-sms-route="sendSmsRoute"
                :login-client-by-phone="loginClientByPhone"
                :login-client-by-name="loginClientByName"
                :corporate-admin-login="corporateAdminLogin"
                :mask="mask"
            />
        </v-container>
    </div>
</template>
<script lang="js" type="text/babel" src="./Auth.main.js" />

<style type="text/scss" scoped lang="scss">
.login {
    height: 50%;
    width: 100%;
    left: 0;
    content: "";
    z-index: 0;
    position: fixed;
    top: -12px !important;
}
.v-application a {
    color: #242424;
}
</style>
