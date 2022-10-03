/** @format */

import clientLoginByPhone from "../../models/ClientLoginByPhone";
import clientLoginByName from "../../models/ClientLoginByName";
import Snackbar from "../../facades/Snackbar";
import Form from "../../base/Form";
import { mapState } from "vuex";

export default {
    name: "ClientLogin",

    props: {
        mask: { required: true },
    },

    data: () => ({
        toggle_icon: "mdi-toggle-switch",
        eager: true,
        step: 1,
        clientLoginPhone: new clientLoginByPhone(),
        clientLoginName: new clientLoginByName(),
    }),

    computed: {
        ...mapState(["client"]),

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
            this.$validator.validate("phone").then(valid => {
                if (valid) {
                    this.clientLoginPhone.sendSmsAcceptCode
                        .then(response => {
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            Form.errors(error.response).forEach(err => this.errors.add(err));
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

    created() {
        if ("undefined" === typeof this.client.before_auth_client_id && this.client.client_id) {
            this.$router.push({ name: "mobile_index" });
        }
    },
};
