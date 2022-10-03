<!-- @format -->

<template>
    <v-layout fill-height justify-center align-content-end>
        <v-flex xl7 lg6 md6 sm11 xs12 align-self-center>
            <router-link :to="{ name: 'mobile_index' }">
                <div class="layout column align-center">
                    <img src="/storage/img/register-header-log.svg" alt="NYT TAXI" width="160" height="120" />
                    <h1 class="flex my-4 black--text"> New Yellow Taxi </h1>
                </div>
            </router-link>
            <v-card elevation="0" tile>
                <v-toolbar color="yellow darken-2" dark dense elevation="0">
                    <v-toolbar-title>{{ currentTitle }}</v-toolbar-title>
                    <v-spacer />
                    <v-btn @click="toggleForm" icon color="grey">
                        <v-icon x-large>{{ toggle_icon }}</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-window omit v-model="step">
                    <!--CLIENT LOGIN-->
                    <v-window-item :omit="eager" :value="1">
                        <v-form action="/app_login_by_phone" method="POST" autocomplete="off">
                            <v-card-text>
                                <input name="_token" :value="$csrf" type="hidden" />
                                <v-layout>
                                    <v-text-field
                                        :error-messages="errors.collect('phone')"
                                        append-icon="mdi-cellphone-basic"
                                        autocomplete="off"
                                        color="yellow darken-3"
                                        label="Phone"
                                        name="phone"
                                        placeholder="+7 (999)-999-99-99"
                                        type="tel"
                                        v-mask="mask"
                                        v-model="clientLoginPhone.phone"
                                        v-validate="clientLoginPhone.rules.phone"
                                    >
                                    </v-text-field>
                                    <v-btn
                                        :disabled="errors.any() || !clientLoginPhone.phone"
                                        @click="sendSmsCode(clientLoginPhone.phone)"
                                        class="mt-5 ml-1"
                                        color="warning"
                                        small
                                        tile
                                        type="button"
                                        >отправить код
                                    </v-btn>
                                </v-layout>

                                <v-text-field
                                    :disabled="!clientLoginPhone.phone"
                                    :error-messages="errors.collect('accept_code')"
                                    @input.native="validatePhoneAcceptCode"
                                    append-icon="mdi-numeric"
                                    color="yellow darken-3"
                                    autocomplete="off"
                                    label="Accept Code"
                                    name="accept_code"
                                    type="number"
                                    v-model="clientLoginPhone.acceptCode"
                                    v-validate="clientLoginPhone.rules.accept_code"
                                />
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn
                                    :disabled="errors.any() || !clientLoginPhone.acceptCode || !clientLoginPhone.phone"
                                    color="yellow darken-2"
                                    type="submit"
                                    block
                                    outlined
                                    tile
                                    v-text="'Login'"
                                />
                            </v-card-actions>
                        </v-form>
                    </v-window-item>

                    <!--LOGIN By Phone-->
                    <v-window-item :omit="eager" :value="2">
                        <v-form action="/app_login_by_name" method="POST">
                            <input :value="$csrf" name="_token" type="hidden" />
                            <v-card-text>
                                <v-layout>
                                    <v-text-field
                                        :error-messages="errors.collect('email')"
                                        append-icon="mdi-account-alert"
                                        autocomplete="off"
                                        label="Email"
                                        name="email"
                                        placeholder="enter your Email"
                                        v-model="clientLoginName.email"
                                        v-validate="clientLoginName.rules.email"
                                    >
                                    </v-text-field>
                                </v-layout>

                                <v-text-field
                                    :error-messages="errors.collect('password')"
                                    append-icon="mdi-lock-question"
                                    autocomplete="off"
                                    label="Password"
                                    name="password"
                                    type="password"
                                    v-model="clientLoginName.password"
                                    v-validate="clientLoginName.rules.password"
                                />
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="yellow darken-2" type="submit" tile block outlined> Login </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-window-item>
                </v-window>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script src="./Auth.main.js" />

<style>
.v-application a {
    color: #1976d200 !important;
}
</style>
