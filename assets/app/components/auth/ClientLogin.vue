<!-- @format -->

<template>
    <v-layout fill-height justify-center align-center>
        <v-flex class="d-flex align-center justify-center">
            <v-card elevation="4" width="500px" tile>
                <v-toolbar color="yellow darken-2" light rounded>
                    <v-toolbar-title>{{ currentTitle }}</v-toolbar-title>
                    <v-spacer />
                    <v-btn @click="toggleForm" icon>
                        <v-icon x-large color="grey darken-3" v-text="toggle_icon" />
                    </v-btn>
                </v-toolbar>

                <v-window omit v-model="step">
                    <!--CLIENT LOGIN-->
                    <v-window-item :omit="eager" :value="1">
                        <v-form :action="loginClientByPhone" method="POST" autocomplete="off">
                            <input name="_token" :value="$csrf" type="hidden" />
                            <v-card-text>
                                <v-layout>
                                    <v-text-field
                                        :error-messages="errors.collect('phone')"
                                        autofocus
                                        append-icon="mdi-cellphone-basic"
                                        autocomplete="off"
                                        color="yellow darken-3"
                                        label="Телефон"
                                        name="phone"
                                        :placeholder="mask"
                                        type="tel"
                                        v-mask="mask"
                                        v-model="clientLoginPhone.phone"
                                        v-validate="clientLoginPhone.rules.phone"
                                        @keypress="$event.keyCode === 13 ? sendSmsCode(clientLoginPhone.phone) : null"
                                    />
                                    <v-btn
                                        :disabled="!clientLoginPhone.phone"
                                        @click="sendSmsCode(clientLoginPhone.phone)"
                                        class="mt-5 ml-1"
                                        color="warning"
                                        small
                                        tile
                                        depressed
                                        :loading="loadingSendAcceptCode"
                                        type="button"
                                        v-text="'отправить код'"
                                    />
                                </v-layout>

                                <v-text-field
                                    style="max-width: 325px"
                                    id="accept-code-input"
                                    :disabled="!clientLoginPhone.phone"
                                    :error-messages="errors.collect('accept_code')"
                                    @input.native="validatePhoneAcceptCode"
                                    type="number"
                                    append-icon="mdi-numeric"
                                    color="yellow darken-3"
                                    autocomplete="off"
                                    label="Код подтверждения"
                                    name="accept_code"
                                    v-model="clientLoginPhone.acceptCode"
                                    v-validate="clientLoginPhone.rules.accept_code"
                                />
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn
                                    :disabled="
                                        errors.any() ||
                                        !clientLoginPhone.acceptCode ||
                                        !clientLoginPhone.phone ||
                                        clientLoginPhone.acceptCode.length < 6
                                    "
                                    color="primary"
                                    type="submit"
                                    tile
                                    v-text="'Войти'"
                                />
                            </v-card-actions>
                        </v-form>
                    </v-window-item>

                    <!--LOGIN By Phone-->
                    <v-window-item :omit="eager" :value="2">
                        <v-form :action="loginClientByName" method="POST" autcomplete="off">
                            <input :value="$csrf" name="_token" type="hidden" />
                            <v-card-text>
                                <v-layout>
                                    <v-text-field
                                        :error-messages="errors.collect('email')"
                                        append-icon="mdi-account-alert"
                                        autocomplete="off"
                                        label="Электронное почта"
                                        name="email"
                                        placeholder="enter your Email"
                                        v-model="clientLoginName.email"
                                        v-validate="clientLoginName.rules.email"
                                    />
                                </v-layout>

                                <v-text-field
                                    :error-messages="errors.collect('password')"
                                    append-icon="mdi-lock-question"
                                    autocomplete="off"
                                    label="Пароль"
                                    name="password"
                                    type="password"
                                    v-model="clientLoginName.password"
                                    v-validate="clientLoginName.rules.password"
                                />
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn :disabled="errors.any()" color="primary" type="submit" tile v-text="'Войти'" />
                            </v-card-actions>
                        </v-form>
                    </v-window-item>
                </v-window>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import clientLoginByPhone from "../../models/clientLoginByPhone";
import clientLoginByName from "../../models/clientLoginByName";
import Snackbar from "../../facades/Snackbar";
import Form from "../../models/Form";

export default {
    name: "ClientLogin",

    props: {
        loginClientByPhone: {
            required: true,
        },
        loginClientByName: {
            required: true,
        },
        sendSmsRoute: {
            required: true,
        },
        mask: {
            required: true,
        },
    },

    data: () => ({
        toggle_icon: "mdi-toggle-switch",
        eager: true,
        step: 1,
        clientLoginPhone: new clientLoginByPhone(),
        clientLoginName: new clientLoginByName(),
        loadingSendAcceptCode: false,
    }),

    computed: {
        currentTitle() {
            switch (this.step) {
                case 1:
                    return "Войти по номеру телефона";
                case 2:
                    return "Войти по электронной почте";
            }
        },

        isCompleteByName() {
            return this.clientLoginName.name && this.clientLoginName.password;
        },
    },

    methods: {
        toggleForm() {
            if (1 < this.step) {
                this.step--;
                this.toggle_icon = "mdi-toggle-switch";
            } else {
                this.step++;
                this.toggle_icon = "mdi-toggle-switch-off";
            }
        },

        sendSmsCode(phone) {
            this.loadingSendAcceptCode = true;
            this.$validator.validate("phone").then(valid => {
                if (valid) {
                    this.clientLoginPhone.acceptCode = "";
                    this.clientLoginPhone.sendSmsAcceptCode
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.loadingSendAcceptCode = false;
                        })
                        .catch(error => {
                            Form.errors(error.response).forEach(err => this.errors.add(err));
                            this.loadingSendAcceptCode = false;
                        });
                }
            });
        },

        validatePhoneAcceptCode() {
            if (6 === this.clientLoginPhone.acceptCode.length && this.clientLoginPhone.phone) {
                this.$validator.validate("accept_code").then(valid => {
                    if (valid) {
                        this.clientLoginPhone.sendPhoneAcceptCode
                            .then(response => {
                                Snackbar.info(response.data.message);
                            })
                            .catch(error => {
                                Form.errors(error.response).forEach(err => this.errors.add(err));
                            });
                    }
                });
            }
        },
    },
};
</script>
<style>
#accept-code-input {
    text-align: center !important;
}
</style>
