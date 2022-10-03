/** @format */

import Entity from "../../../models/Entity";
import EntityBank from "../../../models/EntityBank";
import Snackbar from "../../../facades/Snackbar";
import DatePicker from "../../../../shared/components/form/DatePicker";

export default {
    name: "EntityForm",

    components: {
        'date-picker': DatePicker
    },

    props: {
        entityTypes: {
            required: true,
            type: Array,
        },
        countries: {
            required: true,
            type: Array,
        },
        entityObj: {
            required: true,
        },
    },

    data: function () {
        return {
            entity: new Entity(this.entityObj || {}),
            loading: false,

            tax_psrn_date_menu: false,
            registration_certificate_date_menu: false,

            bankDialog: false,
            bankDialogLoading: false,
            bankDeleteLoading: false,
            bank: new EntityBank(),
            updateBankIndex: -1,
        };
    },

    watch: {
        "entity.country_id": function () {
            this.entity.getRegions();
        },

        "entity.region_id": function () {
            this.entity.getCities();
        },

        "bank.country_id": function () {
            this.bank.getRegions();
        },

        "bank.region_id": function () {
            this.bank.getCities();
        },
    },

    computed: {
        title() {
            return this.entityObj ? this.entityObj.name : "Новое юридическое лицо";
        },
        allBanks() {
            return this.entityObj ? this.entity.banks : this.entity.new_banks;
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },

    methods: {
        today() {
            let today = new Date();
            return today.toISOString();
        },
        store() {
            this.$validator.validateAll(this.entity.scope).then(valid => {
                if (valid) {
                    this.loading = true;
                    this.entity
                        .store()
                        .then(response => {
                            this.loading = false;
                            Snackbar.info(response.data.message);
                            window.location = process.env.MIX_APP_WORKER_URL + "legal-entity";
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        update() {
            this.$validator.validateAll(this.entity.scope).then(valid => {
                if (valid) {
                    this.loading = true;
                    this.entity
                        .update({ "legal-entity": this.entity.legal_entity_id })
                        .then(response => {
                            this.loading = false;
                            Snackbar.info(response.data.message);
                            window.location = process.env.MIX_APP_WORKER_URL + "legal-entity";
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        /*bank*/
        addBank() {
            this.bank = new EntityBank();
            if (this.entityObj) {
                this.bank.entity_id = this.entityObj.legal_entity_id;
            }
            this.bankDialog = true;
            this.updateBankIndex = -1;
        },
        closeBankDialog() {
            this.bank = new EntityBank();
            this.bankDialog = false;
            this.updateBankIndex = -1;
        },

        // new bank methods
        appendBank() {
            this.bankDialogLoading = true;
            this.$validator.validateAll(this.bank.scope).then(valid => {
                if (valid) {
                    ~this.updateBankIndex
                        ? this.entity.new_banks.splice(this.updateBankIndex, 1, this.bank.data())
                        : this.entity.new_banks.unshift(this.bank.data());

                    this.closeBankDialog();
                    this.bankDialogLoading = false;
                } else {
                    this.bankDialogLoading = false;
                }
            });
        },
        updateBank(item, index) {
            this.updateBankIndex = index;
            this.bank = new EntityBank(item);
            this.bankDialog = true;
        },
        removeBank(index) {
            this.entity.new_banks.splice(index, 1);
        },

        // bank requests
        createBankRequest() {
            this.bankDialogLoading = true;
            this.$validator.validateAll(this.bank.scope).then(valid => {
                if (valid) {
                    this.bank
                        .store()
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.bankDialogLoading = false;
                            this.closeBankDialog();
                            if (response.data.bank) {
                                this.entity.banks.push(response.data.bank);
                            }
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.bankDialogLoading = false;
                        });
                } else {
                    this.bankDialogLoading = false;
                }
            });
        },
        updateBankRequest() {
            this.bankDialogLoading = true;
            this.$validator.validateAll(this.bank.scope).then(valid => {
                if (valid && this.bank.entity_bank_id) {
                    this.bank
                        .update({ "legal-entity/bank": this.bank.entity_bank_id })
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.bankDialogLoading = false;
                            this.closeBankDialog();
                            if (response.data.bank) {
                                let index = this.entity.banks.findIndex(item => {
                                    return item.entity_bank_id === response.data.bank.entity_bank_id;
                                });
                                if (~index) {
                                    this.entity.banks.splice(index, 1, response.data.bank);
                                }
                            }
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.bankDialogLoading = false;
                        });
                } else {
                    this.bankDialogLoading = false;
                }
            });
        },
        removeBankRequest(entity_bank_id) {
            this.bankDeleteLoading = entity_bank_id;
            this.bank
                .delete({ "legal-entity/bank": entity_bank_id })
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.bankDeleteLoading = false;
                    let index = this.entity.banks.findIndex(item => {
                        return item.entity_bank_id === entity_bank_id;
                    });
                    if (~index) {
                        this.entity.banks.splice(index, 1);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.bankDeleteLoading = false;
                });
        },
    },

    created() {
        if (this.entityObj) {
            this.entity.getRegions();
            this.entity.getCities();
        }
    },
};
