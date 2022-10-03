<!-- @format -->

<template>
    <v-layout fill-height justify-center align-center>
        <v-flex class="d-flex align-center justify-center">
            <v-card :loading="load" class="elevation-2 pa-3" tile width="500px">
                <v-card-text>
                    <div class="layout column align-center">
                        <a href="/">
                            <img :src="'/storage/img/logos/logo.svg'" alt="NYT TAXI" width="160" height="140" />
                        </a>
                        <h1 class="flex my-4 text"> New Yellow Taxi System </h1>
                    </div>
                    <v-form action="system-login" method="post" autocomplete="off">
                        <input :value="$csrf" name="_token" type="hidden" />
                        <v-text-field
                            autofocus
                            append-icon="mdi-account"
                            name="nickname"
                            label="Login"
                            type="text"
                            v-model="loginData.nickname"
                            v-validate="loginData.rules.nickname"
                            :error_messages="errors.collect('nickname')"
                        />

                        <v-text-field
                            append-icon="mdi-lock"
                            name="password"
                            label="Password"
                            id="password"
                            type="password"
                            v-model="loginData.password"
                            v-validate="loginData.rules.password"
                            :error_messages="errors.collect('password')"
                        />
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                tile
                                block
                                type="submit"
                                color="primary"
                                :disabled="errors.any() || !loginData.nickname || !loginData.password"
                            >
                                Login
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import Login from "./../../forms/Login";

export default {
    name: "Login",

    props: {
        errs: {},
        oldInputs: {},
        cErrors: {},
    },

    data: () => ({
        loginData: new Login(),
        load: false,
    }),
};
</script>
