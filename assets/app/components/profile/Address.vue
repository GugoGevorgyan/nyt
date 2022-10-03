<template>
    <div>
        <v-card>
            <v-card-title>
                <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12>
                            <v-text-field
                                name='name'
                                v-model="address.name"
                                label="Имя*"
                                color="yellow darken-3"
                                data-vv-as="имя"
                                outlined
                                clearable
                                :error-messages="errors.collect('name')"
                                v-validate="address.rules.name"
                            ></v-text-field>
                        </v-flex>

                        <v-flex xs12>
                            <v-text-field
                                id="address"
                                :loading="address.loadingCoordinates"
                                v-model='address.address'
                                name="address"
                                data-vv-as="адрес"
                                label="Адрес*"
                                color="yellow darken-3"
                                prepend-inner-icon="mdi-map-search-outline"
                                outlined
                                clearable
                                :error-messages="errors.collect('address')"
                                v-validate="address.rules.address"
                            ></v-text-field>
                        </v-flex>

                        <v-flex xs12>
                            <v-text-field
                                v-model="address.porch"
                                label="Подъезд"
                                color="yellow darken-3"
                                outlined
                                clearable
                                data-vv-as="подъезд"
                                name="porch"
                                :error-messages="errors.collect('porch')"
                                v-validate="address.rules.porch"
                            ></v-text-field>
                        </v-flex>

                        <v-flex xs12>
                            <v-textarea
                                v-model="address.driver_hint"
                                label="Подскажите водителю, как лучше к вам подойти"
                                color="yellow darken-3"
                                outlined
                                clearable
                                data-vv-as="подсказка"
                                name="driverHint"
                                :error-messages="errors.collect('driverHint')"
                                v-validate="address.rules.driverHint"
                            ></v-textarea>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="returnBack">Отмена</v-btn>
                <v-btn dark color="yellow darken-3" :loading='loading || address.loadingCoordinates' @click="save()">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>

import axios from "axios";
import Address from "../../models/Address";

export default {
    name: 'Address',
    components: {Address},
    props: {
        client_id: {
          required: true
        },
        editingAddress: {
            required: true
        },
        isEditableAddress: {
            required: true
        }
    },
    data() {
        return {
            address: new Address({client_id: this.client_id}),
            loading: false,
            editedIndex: -1,
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "Добавьте адрес" : "Обновите адрес";
        },
    },
    methods: {
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
                        this.loading = true;
                        if (!this.isEditableAddress) {
                            axios
                                .post(`client/add/address`, {
                                    ...this.address,
                                })
                                .then(res => {
                                    this.address = res.data;
                                    this.$emit('add');
                                    this.returnBack();
                                    this.loading = false;
                                    this.$emit('addressListUpdated');
                                });
                        } else {
                            axios
                                .put(`client/update/address`, {
                                    address: this.address,
                                    client_address_id: this.address.client_address_id
                                })
                                .then(res => {
                                    this.address = res.data
                                    this.$emit('update')
                                    this.returnBack();
                                    this.loading = false;
                                    this.$emit('addressListUpdated');
                                });
                        }
                        }
                    });
        },
        returnBack() {
            this.address = new Address({ client_id: this.client_id });
            this.$emit('back');
        }
    },
    watch: {
        editingAddress: function () {
            this.editingAddress
                ? (this.address = new Address(this.editingAddress))
                : (this.address = new Address({ client_id: this.employee.client_id }));
        },
    },

    created() {
        this.editingAddress
            ? (this.address = new Address(this.editingAddress))
            : (this.address = new Address({ client_id: this.client_id }));
        ymaps.ready(this.initSuggest);
    },
};
</script>

<style scoped>

</style>
