<!-- @format -->

<template>
    <div>
        <v-card>
            <v-card-title>Телефонны кол-центра</v-card-title>
            <v-card-text class="pb-0">
                <v-form :data-vv-scope="scope" autocomplete="off">
                    <v-layout row wrap>
                        <v-flex xs12>
                            <div class="mb-4 d-flex align-center justify-center">
                                <span class="title black--text font-weight-light">Номера</span>
                                <v-btn color="primary" fab @click="newPhone()" x-small class="ml-4">
                                    <v-icon small>mdi-plus</v-icon>
                                </v-btn>
                            </div>
                            <div class=" pa-1" style="height: 600px; overflow-y: auto">
                                <div v-for="(item, index) in phones" :key="index" class="pa-4 mb-4" style="border: 1px dashed gray">
                                    <div class="d-flex">
                                        <v-text-field
                                            dense
                                            :error-messages="errors.collect(`${scope}.phone${index}`)"
                                            label="Номер"
                                            :name="`phone${index}`"
                                            outlined
                                            prepend-inner-icon="mdi-phone-outline"
                                            v-model="phones[index].number"
                                            v-validate="'required|string|min:3|max:36'"
                                            data-vv-as="телефон"
                                            v-mask="'+#(###)-###-##-##'"
                                        />
                                        <v-btn
                                            color="error"
                                            class="ml-2 mt-1"
                                            icon
                                            v-if="phones.length > 1"
                                            @click="
                                                phones[index].franchise_phone_id ? removePhoneConfirm(phones[index]) : removePhone(index)
                                            "
                                        >
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn>
                                    </div>
                                    <div class="mb-4 d-flex align-center justify-center">
                                        <span class="black--text font-weight-light">Дополнительные номера</span>
                                        <v-btn color="primary" fab @click="newSubPhone(index)" x-small class="ml-4">
                                            <v-icon small>mdi-plus</v-icon>
                                        </v-btn>
                                    </div>
                                    <v-layout wrap justify-end v-for="(subPhone, subIndex) in phones[index].sub_phones" :key="subIndex">
                                        <v-flex xs3 class="px-1">
                                            <v-text-field
                                                dense
                                                :error-messages="errors.collect(`${scope}.${index}subPhone${subIndex}.number`)"
                                                label="Дополнительный номер"
                                                :name="`${index}subPhone${subIndex}.number`"
                                                outlined
                                                prepend-inner-icon="mdi-phone-outline"
                                                v-mask="'####'"
                                                v-model="phones[index].sub_phones[subIndex].number"
                                                v-validate="'required|string|min:3|max:36'"
                                                data-vv-as="телефон"
                                                hint="####"
                                            />
                                        </v-flex>
                                        <v-flex xs8 class="px-1">
                                            <v-text-field
                                                :name="`${scope}.${index}subPhone${subIndex}.password`"
                                                dense
                                                :error-messages="errors.collect(`${scope}.${index}subPhone${subIndex}.password`)"
                                                label="Пароль"
                                                outlined
                                                prepend-inner-icon="mdi-lock-outline"
                                                ref="password"
                                                v-model="phones[index].sub_phones[subIndex].password"
                                                data-vv-as="пароль"
                                                v-validate="'required|string|min:8|max:36'"
                                            />
                                        </v-flex>
                                        <v-flex xs1 class="px-1">
                                            <v-btn
                                                color="error"
                                                class="ml-2 mt-1"
                                                icon
                                                v-if="phones[index].sub_phones.length > 1"
                                                @click="
                                                    phones[index].sub_phones[subIndex].franchise_sub_phone_id
                                                        ? removeSubPhoneConfirm(phones[index].sub_phones[subIndex])
                                                        : removeSubPhone(index, subIndex)
                                                "
                                            >
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </div>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-card-text>
            <v-card-actions class="justify-end">
                <v-btn color="error" text small @click="closePhonesDialog()">отмена</v-btn>
                <v-btn color="primary" small @click="updatePhones()">принять</v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="removePhoneDialog" width="600" persistent>
            <v-card v-if="removePhoneObj" :loading="removePhoneLoading">
                <v-card-title>Удаление номера</v-card-title>
                <v-card-text>
                    <p>
                        Номер: <strong>{{ removePhoneObj.number }}</strong> уже зарегистрирован в базе данных. Вы уверены, что хотите
                        удалить его?
                    </p>
                    <v-alert dense type="error">
                        <small>При удалении франшиза перестанет получать звонки по данному номеру</small>
                    </v-alert>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="error" text small @click="removePhoneClose()">нет</v-btn>
                    <v-btn color="primary" :loading="removePhoneLoading" small @click="removePhoneRequest()">да</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="removeSubPhoneDialog" width="600" persistent>
            <v-card v-if="removeSubPhoneObj" :loading="removeSubPhoneLoading">
                <v-card-title>Удаление дополнительного номера</v-card-title>
                <v-card-text>
                    <p>
                        Дополнитеьный номер: <strong>{{ removeSubPhoneObj.number }}</strong> уже зарегистрирован в базе данных. Вы уверены,
                        что хотите удалить его?
                    </p>
                    <v-alert dense type="error">
                        <small>При удалении работник перестанет получать звонки по данному номеру</small>
                    </v-alert>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="error" text small @click="removeSubPhoneClose()">нет</v-btn>
                    <v-btn color="primary" :loading="removeSubPhoneLoading" small @click="removeSubPhoneRequest()">да</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Snackbar from '../../facades/Snackbar';
import FranchisePhone from '../../models/FranchisePhone';
import FranchiseSubPhone from '../../models/FranchiseSubPhone';
export default {
    props: {
        franchise: {
            required: true,
        },
        discharge: {
            required: true,
            type: Boolean,
        },
    },
    data: function () {
        return {
            franchiseValues: JSON.parse(JSON.stringify(this.franchise)),
            phones: [],
            scope: 'franchiseCallCenter',

            removePhoneObj: null,
            removePhoneDialog: false,
            removePhoneLoading: false,
            removeSubPhoneObj: null,
            removeSubPhoneDialog: false,
            removeSubPhoneLoading: false,
        };
    },
    watch: {
        discharge: function () {
            if (this.discharge) {
                this.phones = [];
                this.newPhone();
            }
        },
    },
    methods: {
        newPhone() {
            this.phones.push({
                franchise_phone_id: null,
                number: null,
                sub_phones: [
                    {
                        franchise_sub_phone_id: null,
                        number: null,
                        password: null,
                    },
                ],
            });
        },

        newSubPhone(index) {
            this.phones[index].sub_phones.push({
                franchise_sub_phone_id: null,
                number: null,
                password: null,
            });
        },

        setPhonesDefault() {
            if (this.franchiseValues.call_center_phones.length) {
                this.phones = this.franchiseValues.call_center_phones;
            } else {
                this.phones = [];
                this.newPhone();
            }
        },

        /*emit*/
        closePhonesDialog() {
            this.$emit('close');
        },

        updatePhones() {
            this.$validator.validateAll(this.scope).then(valid => {
                if (valid) {
                    this.$emit('update', this.phones);
                    this.$emit('close');
                }
            });
        },

        removePhone(index) {
            this.phones.splice(index, 1);
        },

        removePhoneConfirm(item) {
            this.removePhoneObj = item;
            this.removePhoneDialog = true;
        },

        removePhoneClose() {
            this.removePhoneObj = null;
            this.removePhoneDialog = false;
        },

        removePhoneRequest() {
            if (this.removePhoneObj) {
                this.removePhoneLoading = true;
                let phone = new FranchisePhone(this.removePhoneObj);
                phone
                    .delete({ 'franchise-phone': this.removePhoneObj.franchise_phone_id })
                    .then(response => {
                        Snackbar.info(response.data.message);
                        this.removePhoneLoading = false;
                        this.removePhoneCallBack();
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.removePhoneLoading = false;
                    });
            }
        },

        removePhoneCallBack() {
            let index = this.phones.findIndex(item => item.franchise_phone_id === this.removePhoneObj.franchise_phone_id);
            this.phones.splice(index, 1);
            this.$emit('delete', this.removePhoneObj.franchise_phone_id);
            this.removePhoneClose();
        },

        removeSubPhoneConfirm(item) {
            this.removeSubPhoneObj = item;
            this.removeSubPhoneDialog = true;
        },

        removeSubPhoneRequest() {
            if (this.removeSubPhoneObj) {
                this.removeSubPhoneLoading = true;
                let subPhone = new FranchiseSubPhone(this.removeSubPhoneObj);
                subPhone
                    .delete({ 'franchise-sub-phone': this.removeSubPhoneObj.franchise_sub_phone_id })
                    .then(response => {
                        Snackbar.info(response.data.message);
                        this.removeSubPhoneLoading = false;
                        this.removeSubPhoneCallBack();
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.removeSubPhoneLoading = false;
                    });
            }
        },

        removeSubPhoneCallBack() {
            let phoneIndex = this.phones.findIndex(item => item.franchise_phone_id === this.removeSubPhoneObj.franchise_phone_id);
            let index = this.phones[phoneIndex].sub_phones.findIndex(
                item => item.franchise_sub_phone_id === this.removeSubPhoneObj.franchise_sub_phone_id,
            );
            this.phones[phoneIndex].sub_phones.splice(index, 1);

            this.$emit('deleteSub', this.removeSubPhoneObj);
            this.removeSubPhoneClose();
        },

        removeSubPhoneClose() {
            this.removeSubPhoneObj = null;
            this.removeSubPhoneDialog = false;
        },

        removeSubPhone(index, subIndex) {
            this.phones[index].sub_phones.splice(subIndex, 1);
        },
    },
    created() {
        this.setPhonesDefault();
    },
};
</script>
