/** @format */

import Snackbar from "../../../facades/Snackbar";

export default {
    props: {
        client: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            loading: false,

            existsDialog: false,
            existsLoading: false,
            existsClient: null,
        };
    },
    watch: {
        "client.phone": function () {
            if (
                !this.client.client_id &&
                this.client.phone &&
                this.client.phone.replace(/[^0-9]+/g, "").length > 6 &&
                this.client.phone.replace(/[^0-9]+/g, "").length <= 11 &&
                this.client.phone.replace(/[^0-9]+/g, "") !== this.lastReqPhone
            ) {
                this.checkClient(this.client.phone.replace(/[^0-9]+/g, ""));
            }
        },
    },
    computed: {
        disabled() {
            return (
                this.existsLoading ||
                this.loading ||
                !this.client.phone ||
                this.client.phone.length < 6 ||
                !!this.existsClient
            );
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                localStorage.setItem("color_mode", mode);
                this.$store.state.dark = mode;
            },
        },
    },
    methods: {
        setClientValues(values) {
            let obj = {
                client: values.client,
                companies: values.companies,
                orders: values.orders,
            };
            this.$emit("updateClientObj", obj);
        },

        checkClient(phone) {
            this.existsLoading = true;
            this.$http
                .post("call-center/check-client-exists", { phone: phone })
                .then(response => {
                    this.existsLoading = false;
                    if (response.data.exists) {
                        this.existsClient = response.data;
                        this.showExistsDialog();
                        this.lastReqPhone = phone;
                    } else {
                        this.existsClient = null;
                        this.lastReqPhone = null;
                    }
                })
                .catch(error => {
                    this.existsLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        showExistsDialog() {
            this.existsDialog = true;
        },

        closeExistsDialog() {
            this.existsDialog = false;
        },

        setExistsClient() {
            this.setClientValues(this.existsClient);
            this.closeExistsDialog();
        },

        createClient() {
            this.$validator.validateAll("client").then(valid => {
                if (valid) {
                    this.loading = true;
                    this.client
                        .store()
                        .then(response => {
                            this.loading = false;
                            let values = {
                                client: response.data.client,
                                orders: [],
                                companies: [],
                            };
                            this.setClientValues(values);
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        updateClient() {
            this.$validator.validateAll("client").then(valid => {
                if (valid) {
                    this.loading = true;
                    this.client
                        .update({ "call-center/client": this.client.client_id })
                        .then(response => {
                            this.loading = false;
                            this.$emit("clientUpdated", response.data.client);
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
    },
};
