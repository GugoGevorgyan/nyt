<!-- @format -->

<template>
    <v-layout :value="!dashboard.session" fluid>
        <v-overlay :value="!dashboard.session" :absolute="true" opacity="0.96" color="white" z-index="99999999">
            <v-card outlined color="white" tile width="470px">
                <v-card-title>
                    <h3 style="color: gray">Session In</h3>
                </v-card-title>
                <v-card-text>
                    <v-layout align-center justify-center align-content-center>
                        <v-flex md12 sm12 xs12 xl12 align-self-center>
                            <v-form autocomplete="off">
                                <v-text-field
                                    class="mb-3"
                                    append-icon="mdi-account"
                                    color="yellow darken-3"
                                    hide-details
                                    label="Nickname"
                                    name="nickname"
                                    solo
                                    autofocus
                                    v-model="dashboardModel.nickname"
                                    v-validate="dashboardModel.rules.nickname"
                                    :error-messages="errors.collect('nickname')"
                                    @keypress="13 === $event.keyCode ? sessionIn() : null"
                                />
                                <v-text-field
                                    class="mb-5"
                                    append-icon="mdi-barcode"
                                    type="password"
                                    color="yellow darken-3"
                                    hide-details
                                    label="Password"
                                    name="password"
                                    solo
                                    v-model="dashboardModel.password"
                                    v-validate="dashboardModel.rules.password"
                                    :error-messages="errors.collect('password')"
                                    @keypress="13 === $event.keyCode ? sessionIn() : null"
                                />
                                <v-spacer />
                                <v-btn outlined tile block color="yellow darken-3" v-text="'In'" @click="sessionIn" />
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-overlay>
    </v-layout>
</template>

<script>
import { mapState, mapMutations } from "vuex";
import Dashboard from "./../../forms/Dashboard";
export default {
    data() {
        return {
            dashboardModel: new Dashboard(),
        };
    },

    computed: {
        ...mapState(["dashboard"]),
    },

    methods: {
        ...mapMutations({ initDashboard: "initDashboard" }),

        sessionIn() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.dashboardModel
                        .sessionInRequest(this.dashboardModel.nickname, this.dashboardModel.password)
                        .then(result => {
                            localStorage.removeItem(this.dashboardModel.tokenKeyName);
                            this.dashboardModel.nickname = "";
                            this.dashboardModel.password = "";
                            this.initDashboard({ session: true });
                        })
                        .catch(error => {});
                }
            });
        },
    },
};
</script>
