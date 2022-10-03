/** @format */

import Entity from '../../models/Entity';
import EntityBank from '../../models/EntityBank';
import Snackbar from '../../facades/Snackbar';
import Model from '../../base/Model';

export default {
    name: 'EntityForm',
    props: {
        entityTypes: {
            required: true,
            type: Array,
        },
        countries: {
            required: true,
            type: Array,
        },
    },
    data: function () {
        return {
            entity: new Entity(),
            loading: false,

            tax_psrn_date_menu: false,
            registration_certificate_date_menu: false,

            bankDialog: false,
            bankDialogLoading: false,
            bank: new EntityBank(),
            updateBankIndex: -1,
        };
    },
    watch: {
        'entity.country_id': function () {
            this.entity.getRegions();
        },

        'entity.region_id': function () {
            this.entity.getCities();
        },

        'bank.country_id': function () {
            this.bank.getRegions();
        },

        'bank.region_id': function () {
            this.bank.getCities();
        },
    },
    methods: {
        today() {
            let today = new Date();
            return today.toISOString();
        },

        close() {
            this.entity = new Entity();
            this.$emit('close');
        },

        store() {
            this.$validator.validateAll(this.entity.scope).then(valid => {
                if (valid) {
                    this.loading = true;
                    this.entity
                        .store()
                        .then(response => {
                            this.$emit('entityCreated', response.data.entity);
                            this.close();
                            this.loading = false;
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Model.errors(error.response, this.entity.scope).forEach(err => this.errors.add(err));
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        /*bank*/

        addBank() {
            this.bank = new EntityBank();
            this.bankDialog = true;
            this.updateBankIndex = -1;
        },

        closeBankDialog() {
            this.bank = new EntityBank();
            this.bankDialog = false;
            this.updateBankIndex = -1;
        },

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
    },
};
