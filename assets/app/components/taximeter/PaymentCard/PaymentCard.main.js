/** @format */
import { PAYMENT_TYPE } from "../../../plugins/config";
import Snackbar from "../../../facades/Snackbar";

export default {
    name: "PaymentCard",

    data() {
        return {
            loading: false,
            PAYMENT_TYPE: PAYMENT_TYPE,
            numberInValid: true,
            model: {
                ccNumber: "",
                ccExpiration: "",
                ccCvc: "",
            },
        };
    },

    computed: {
        paymentCard: {
            get() {
                return this.$store.state.order.payment_type;
            },
            set(val) {
                this.$store.state.order.payment_type = val;
            },
        },

        paymentDialog: {
            get() {
                return this.$store.state.app.paymentDialog;
            },
            set(val) {
                this.$store.state.app.paymentDialog = val;
            },
        },

        paymentType: {
            get() {
                return this.$store.state.order.payment_type;
            },
            set(val) {
                this.$store.state.order.payment_type = val;
            },
        },
    },

    watch: {
        "model.ccNumber": function (val) {
            this.numberInValid = 19 > val.length;
        },
    },

    methods: {
        addCC() {
            this.$validator.validateAll().then(valid => {
                if (!valid) {
                    return;
                }

                this.$http
                    .post("add_card", {
                        cc_number: this.model.ccNumber,
                        cc_expiration: this.model.ccExpiration,
                        cc_cvc: this.model.ccCvc,
                    })
                    .then(response => {
                        let form = document.createElement("form");
                        let element1 = document.createElement("input");
                        let element2 = document.createElement("input");
                        let element3 = document.createElement("input");

                        form.method = "POST";
                        form.target = "_blank";
                        form.action = response.data.redirect;

                        element1.value = response.data.pareq;
                        element1.name = "PaReq";
                        form.appendChild(element1);

                        element2.value = response.data.md;
                        element2.name = "MD";
                        form.appendChild(element2);
                        // 5543735484626654    22/01    852
                        element3.value = `${response.data.term_url}&url=${encodeURIComponent(response.data.redirect_url)}`;
                        element3.name = "TermUrl";
                        form.appendChild(element3);

                        document.body.appendChild(form);

                        form.submit();
                    })
                    .catch(error => {
                        this.loading = false;
                        Snackbar.error("INVALID");
                    });
            });
            this.loading = true;
        },
    },

    created() {
        if (false === this.paymentDialog) {
            this.paymentDialog = true;
        }
    },
};
