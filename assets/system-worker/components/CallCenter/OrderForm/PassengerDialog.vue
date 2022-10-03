<!-- @format -->

<template>
    <div>
        <v-dialog v-model="dialog" max-width="500" width="100%" persistent>
            <v-card class="border-lg">
                <v-card-text class="pt-4">
                    <v-btn absolute top right small icon color="grey" @click="$emit('close')">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <div class="display-1 font-weight-light text-center">Данные пасажира</div>
                    <v-divider class="mb-3" />
                    <v-form data-vv-scope="passenger">
                        <v-text-field
                            class="mb-4"
                            data-vv-as="телефон"
                            outlined
                            dense
                            :loading="loading"
                            v-mask="'+#(###)-###-##-##'"
                            background-color="grey lighten-3"
                            :error-messages="errors.collect('passenger.phone')"
                            v-model="passengerForm.phone"
                            color="yellow darken-3"
                            label="Телефон"
                            name="phone"
                            v-validate="passenger.rules.phone"
                            hide-details
                        />
                        <v-text-field
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="фамилия"
                            outlined
                            background-color="grey lighten-3"
                            dense
                            :error-messages="errors.collect('passenger.surname')"
                            v-model="passengerForm.surname"
                            color="yellow darken-3"
                            label="Фамилия"
                            name="surname"
                            v-validate="passenger.rules.surname"
                            hide-details
                        />
                        <v-text-field
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="имя"
                            background-color="grey lighten-3"
                            outlined
                            dense
                            :error-messages="errors.collect('passenger.name')"
                            v-model="passengerForm.name"
                            color="yellow darken-3"
                            label="Имя"
                            name="name"
                            v-validate="passenger.rules.name"
                            hide-details
                        />
                        <v-text-field
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="отчество"
                            outlined
                            background-color="grey lighten-3"
                            dense
                            :error-messages="errors.collect('passenger.patronymic')"
                            v-model="passengerForm.patronymic"
                            color="yellow darken-3"
                            label="Отчество"
                            name="patronymic"
                            v-validate="passenger.rules.patronymic"
                            hide-details
                        />
                        <div class="d-flex justify-center">
                            <v-btn rounded small :disabled="disabled" color="primary" @click="acceptPassenger()">
                                принять
                            </v-btn>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog persistent v-model="existsDialog" max-width="400" width="100%">
            <v-card v-if="existsPassenger">
                <v-card-title>Информация о {{ existsPassenger.phone }}</v-card-title>
                <v-card-text>
                    <v-alert border="left" colored-border type="info" elevation="2">
                        Клиент с номером {{ existsPassenger.phone }} уже существует!
                    </v-alert>
                </v-card-text>
                <v-card-actions class="d-flex justify-end">
                    <v-btn text color="error" @click="closeExists()">Отмена</v-btn>
                    <v-btn text color="primary" @click="setExists()"> Загрузить информацию </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";
import Snackbar from "../../../facades/Snackbar";

export default {
    props: {
        dialog: {
            required: true,
            type: Boolean,
        },
        passenger: {
            required: true,
            type: Object,
        },
        order: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            loading: false,
            passengerForm: {
                phone: null,
                name: null,
                surname: null,
                patronymic: null,
            },
            existsPassenger: null,

            existsDialog: false,
            lastReqPhone: null,
        };
    },
    watch: {
        dialog() {
            this.dialog ? this.showPassenger() : this.closePassenger();
        },
        "passengerForm.phone": function () {
            if (
                this.passengerForm.phone &&
                this.passengerForm.phone.replace(/[^0-9]+/g, "").length > 6 &&
                this.passengerForm.phone.replace(/[^0-9]+/g, "").length <= 11 &&
                this.passengerForm.phone.replace(/[^0-9]+/g, "") !== this.lastReqPhone
            ) {
                this.checkPassenger(this.passengerForm.phone.replace(/[^0-9]+/g, ""));
            }
        },
    },
    computed: {
        disabled() {
            return (
                this.loading ||
                !this.passengerForm.phone ||
                this.passengerForm.phone.length < 6 ||
                !!this.existsPassenger
            );
        },
    },
    methods: {
        checkPassenger(phone) {
            this.passenger.client_id = null;
            this.loading = true;
            axios
                .post("call-center/check-passenger-exists", { phone: phone })
                .then(response => {
                    this.loading = false;
                    if (response.data.exists) {
                        this.existsPassenger = response.data.passenger;
                        this.existsDialog = true;
                        this.lastReqPhone = phone;
                    } else {
                        this.existsPassenger = null;
                        this.lastReqPhone = null;
                    }
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        setExists() {
            this.passengerForm = {
                name: this.existsPassenger.name,
                surname: this.existsPassenger.surname,
                patronymic: this.existsPassenger.patronymic,
                phone: this.existsPassenger.phone,
            };
            this.passenger.client_id = this.existsPassenger.client_id;
            this.existsPassenger = null;
            this.existsDialog = false;
        },
        closeExists() {
            this.existsDialog = false;
            this.canceledPhone = this.existsPassenger.phone;
        },

        showPassenger() {
            this.passengerForm = {
                name: this.passenger.name,
                surname: this.passenger.surname,
                patronymic: this.passenger.patronymic,
                phone: this.passenger.phone,
            };
        },
        closePassenger() {
            this.passengerForm = {
                name: null,
                surname: null,
                patronymic: null,
                phone: null,
            };
        },
        acceptPassenger() {
            this.$validator.validateAll("passenger").then(valid => {
                if (valid) {
                    this.order.is_passenger = true;
                    this.passenger.name = this.passengerForm.name;
                    this.passenger.surname = this.passengerForm.surname;
                    this.passenger.patronymic = this.passengerForm.patronymic;
                    this.passenger.phone = this.passengerForm.phone;
                    this.$emit("close");
                }
            });
        },
    },
};
</script>
