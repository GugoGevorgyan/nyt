<!-- @format -->

<template>
    <v-card class="border-lg" elevation="4">
        <v-card-title>
            <v-btn icon color="error" @click="returnBack()">
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
            <v-spacer/>
            {{ editingAddress ? 'Редактировать аддресс ' + editingAddress.name : 'Новый адрес' }}
            <v-spacer></v-spacer>
            <v-btn v-if="editingAddress" :loading="addressDeleteLoading" icon color="error" @click="remove()">
                <v-icon>mdi-delete</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider/>
        <v-card-text>
            <div class="b-scroll" style="overflow-y: auto; height: 475px">
                <v-form class='mt-1'>
                    <v-text-field
                        v-model="address.name"
                        label="Имя*"
                        color="yellow darken-3"
                        outlined
                        data-vv-as="имя"
                        name="name"
                        :error-messages="errors.collect('name')"
                        v-validate="address.rules.name"
                        dense
                    />
                    <v-text-field
                        :loading="address.loadingCoordinates"
                        :append-icon="address.coordinates ? 'mdi-map-marker' : null"
                        id="address"
                        v-model="address.address"
                        label="Адрес*"
                        color="yellow darken-3"
                        outlined
                        data-vv-as="адрес"
                        name="address"
                        :error-messages="errors.collect('address')"
                        v-validate="address.rules.address"
                        dense
                    />
                    <v-text-field
                        v-model="address.porch"
                        label="Подъезд"
                        color="yellow darken-3"
                        outlined
                        data-vv-as="подъезд"
                        name="porch"
                        :error-messages="errors.collect('porch')"
                        v-validate="address.rules.porch"
                        dense
                    />
                    <v-textarea
                        v-model="address.driverHint"
                        label="Подсказка водителю, как лучше всего подойти к вам"
                        color="yellow darken-3"
                        outlined
                        data-vv-as="подсказка"
                        name="driverHint"
                        :error-messages="errors.collect('driverHint')"
                        v-validate="address.rules.driverHint"
                        dense
                    />
                    <small>*отмеченные поля обязательны</small>
                </v-form>
            </div>
        </v-card-text>
        <v-divider/>
        <v-card-actions>
            <v-spacer/>
            <v-btn tile text color="error" @click="returnBack()"> Отмена </v-btn>
            <v-btn tile :loading="addressLoading" color="primary" @click="editingAddress ? update() : save()">
                {{ editingAddress ? 'Обновить' : 'Сохранить' }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import Address from '../../models/Address';

export default {
    name: 'NewAddress',
    props: {
        employee: {
            required: true,
            type: Object,
        },
        editingAddress: {
            required: true,
        },
    },
    data() {
        return {
            address: undefined,
            addressLoading: false,
            addressDeleteLoading: false,
        };
    },
    computed: {
        ...mapState(['company', 'editedAddressIndex']),
    },
    methods: {
        ...mapMutations(['decrementStep', 'updateNamespace', 'updatePorch', 'updateHint']),

        initSuggest() {
            let self = this;
            let addressView = new ymaps.SuggestView('address');
            addressView.events.add('select', e => {
                self.address.address = e.get('item').value;
                self.address.value = self.address.address;
                self.address.getAddressCoords();
            });
        },

        save() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.addressLoading = true;
                    this.address
                        .store()
                        .then((response) => {
                            this.addressLoading = false;
                            this.address = response.data.address
                            this.$emit('add', { ...this.address });
                            this.returnBack();
                        })
                        .catch(() => {
                            this.addressLoading = false;
                        });
                }
            });
        },

        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.addressLoading = true;
                    this.address
                        .update({ 'company/client/address': this.address.client_address_id })
                        .then(response => {
                            this.addressLoading = false;
                            this.$emit('update', response.data.address);
                            this.returnBack();
                        })
                        .catch(() => {
                            this.addressLoading = false;
                        });
                }
            });
        },

        remove() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.addressDeleteLoading = true;
                    this.address
                        .delete({ 'company/client/address': this.address.client_address_id })
                        .then(response => {
                            this.addressDeleteLoading = false;
                            this.$emit('delete', {
                                client_id: this.address.client_id,
                                client_address_id: this.address.client_address_id,
                            });
                            this.returnBack();
                        })
                        .catch(() => {
                            this.addressDeleteLoading = false;
                        });
                }
            });
        },

        returnBack() {
            this.address = new Address({ client_id: this.employee.client_id })
            this.$emit('back');
        },

        deleteEmployeeAddress() {
            confirm(`Are you sure you want to delete this address?`) &&
                this.$http
                    .delete(
                        `http://nyt.loc/admin/corporate/company/address-delete/${this.employee.client_id}/${this.address.client_address_id}`,
                    )
                    .then(res => {
                        this.deleteAddress();
                    })
                    .catch(err => {
                        console.error(err);
                    });
        },
    },

    watch: {
        'employee.client_id': function () {
            this.address = new Address({ client_id: this.employee.client_id });
        },

        editingAddress: function () {
            this.editingAddress
                ? (this.address = new Address(this.editingAddress))
                : (this.address = new Address({ client_id: this.employee.client_id }));
        },
    },

    created() {
        this.editingAddress
            ? (this.address = new Address(this.editingAddress))
            : (this.address = new Address({ client_id: this.employee.client_id }));
            ymaps.ready(this.initSuggest);
    },
};
</script>
