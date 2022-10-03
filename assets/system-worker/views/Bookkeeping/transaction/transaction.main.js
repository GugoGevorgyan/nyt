/** @format */
import Snackbar from "../../../facades/Snackbar";
import { TRANSACTION } from "../../../plugins/config";

export default {
    name: "transaction",

    props: {
        transactionTypes: {
            required: true,
        },
        drivers: {},
    },

    data() {
        return {
            trTypes: {},
            sendLoader: false,
            TRANSACTION: TRANSACTION,
            inputOutputIcon: "mdi-arrow-left",
            debt: null,
            initialDebt: null,
            debtLoader: false,
            payload: {
                sum: 0,
                input: true,
                type: "",
                side: "",
                comment: "",
            },
        };
    },

    watch: {
        "payload.type": function () {
            if (this.payload.type === TRANSACTION.DEBT && this.payload.side) {
                this.getDriverDebt();
            }
        },
        "payload.side": function () {
            if (this.payload.type === TRANSACTION.DEBT && this.payload.side) {
                this.getDriverDebt();
            }
        },
        "payload.sum": function (val) {
            if (this.payload.type === TRANSACTION.DEBT && this.payload.side && this.debt) {
                this.debt = this.initialDebt - this.payload.sum;
            }
            if (!val) {
                this.debt = this.initialDebt;
            }
        },
    },

    methods: {
        toggleInputOutput() {
            if ("mdi-arrow-right" === this.inputOutputIcon) {
                this.payload.input = true;
                this.inputOutputIcon = "mdi-arrow-left";
            } else {
                this.payload.input = false;
                this.inputOutputIcon = "mdi-arrow-right";
            }
        },

        sendTransaction() {
            this.sendLoader = true;
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.$http
                        .post("all/create_transaction", this.payload)
                        .then(result => {
                            this.sendLoader = false;
                            this.$emit("close");
                            Snackbar.info("CREATED");
                        })
                        .catch(error => {
                            this.sendLoader = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        getDriverDebt() {
            this.debtLoader = true;
            this.$http
                .get(`driver-debt/${this.payload.side}`)
                .then(result => {
                    this.debt = result.data._payload.debt;
                    this.initialDebt = result.data._payload.debt;
                    this.debtLoader = false;
                })
                .catch(error => {
                    this.debtLoader = false;
                });
        },
    },

    created() {
        this.trTypes = this.transactionTypes.filter(
            item =>
                item.type === TRANSACTION.BALANCE || item.type === TRANSACTION.CRASH || item.type === TRANSACTION.DEBT,
        );
    },
};
