<!-- @format -->

<template>
    <v-layout fill-height justify-center align-center>
        <v-flex class="d-flex align-center justify-center">
            <v-card elevation="4" width="500px" tile>
                <v-card-text>
                    <div class="layout column align-center">
                        <img src="/storage/img/register-header-log.svg" alt="NYT TAXI" width="160" height="120" />
                        <h1 class="flex my-4 black--text">Новое Желтое Такси</h1>
                    </div>
                    <v-form :action="corporateAdminLogin" method="post" autocomplete="off">
                        <input :value="$csrf" name="_token" type="hidden" />
                        <v-text-field
                            autofocus
                            append-icon="mdi-account"
                            name="email"
                            label="Логин"
                            type="text"
                            v-model="corporateLogin.email"
                            v-validate="corporateLogin.rules.email"
                            :error_messages="errors.collect('email')"
                            color="yellow darken-3"
                        />

                        <v-text-field
                            append-icon="mdi-lock"
                            name="password"
                            label="Пароль"
                            id="password"
                            type="password"
                            v-model="corporateLogin.password"
                            v-validate="corporateLogin.rules.password"
                            :error_messages="errors.collect('password')"
                            color="yellow darken-3"
                        />
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                block
                                tile
                                type="submit"
                                color="primary"
                                v-text="'Войти'"
                                :disabled="errors.any() || !corporateLogin.password || !corporateLogin.email"
                            />
                        </v-card-actions>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import CorporateLogin from "./../../models/CorporateLogin";

export default {
    name: "CorporateLogin",

    props: {
        corporateAdminLogin: {
            required: true,
            String: true,
        },
    },

    data: () => ({
        corporateLogin: new CorporateLogin(),
    }),
};
</script>

<style scoped></style>
