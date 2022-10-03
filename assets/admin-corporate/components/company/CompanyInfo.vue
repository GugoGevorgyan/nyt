<!-- @format -->

<template>
    <v-card tile elevation="4" loader-height="2" :loading="company.loading">
        <v-card-text style="overflow: auto" :height="window.height">
            <v-form data-vv-scope="Company" autocomplete="off">
                <v-row>
                    <v-col cols="12" lg="6">
                        <v-container>
                            <v-subheader>Общая информация</v-subheader>
                            <v-row>
                                <v-col cols="12" lg="6">
                                    <v-text-field
                                        color="yellow darken-3"
                                        dense
                                        label="Название"
                                        name="name"
                                        outlined
                                        v-model="company.name"
                                        :error-messages="errors.collect('name')"
                                        v-validate="company.rules.name"
                                    />
                                </v-col>
                                <v-col cols="12" lg="6">
                                    <v-text-field
                                        color="yellow darken-3"
                                        dense
                                        label="Эл. адрес"
                                        name="email"
                                        outlined
                                        type="email"
                                        v-model="company.email"
                                        :error-messages="errors.collect('email')"
                                        v-validate="company.rules.email"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" lg="12">
                                    <v-textarea
                                        :error-messages="errors.collect('details')"
                                        color="yellow darken-3"
                                        dense
                                        label="Детали"
                                        name="details"
                                        outlined
                                        v-model="company.details"
                                        v-validate="company.rules.details"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" lg="4">
                                    <v-text-field
                                        :error-messages="errors.collect('period')"
                                        color="yellow darken-3"
                                        dense
                                        hint="Период времени"
                                        label="Период"
                                        name="period"
                                        outlined
                                        type="number"
                                        v-model="company.period"
                                        v-validate="company.rules.period"
                                    />
                                </v-col>
                                <v-col cols="12" lg="4">
                                    <v-text-field
                                        :error-messages="errors.collect('frequency')"
                                        color="yellow darken-3"
                                        dense
                                        hint="Количество отчетов в этот период времени"
                                        label="Частота"
                                        name="frequency"
                                        outlined
                                        type="number"
                                        v-model="company.frequency"
                                        v-validate="company.rules.frequency"
                                    />
                                </v-col>
                                <v-col cols="12" lg="4">
                                    <v-text-field
                                        :error-messages="errors.collect('limit')"
                                        color="yellow darken-3"
                                        dense
                                        label="Лимит"
                                        name="limit"
                                        outlined
                                        type="number"
                                        v-model="company.limit"
                                        v-validate="company.rules.limit"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" lg="4">
                                    <v-text-field
                                        :error-messages="errors.collect('code')"
                                        color="yellow darken-3"
                                        dense
                                        hint="Внутренний код компании"
                                        label="Код"
                                        name="code"
                                        outlined
                                        type="number"
                                        v-model="company.code"
                                        v-validate="company.rules.code"
                                    />
                                </v-col>
                                <v-col cols="12" lg="4">
                                    <v-text-field
                                        :error-messages="errors.collect('order_canceled_timeout')"
                                        color="yellow darken-3"
                                        dense
                                        label="Время отемны заказа"
                                        name="order_canceled_timeout"
                                        outlined
                                        type="number"
                                        v-model="company.order_canceled_timeout"
                                        v-validate="company.rules.order_canceled_timeout"
                                    />
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-col>
                    <v-col class="d-flex justify-center" cols="12" lg="1">
                        <v-divider vertical />
                    </v-col>
                    <v-col cols="12" lg="5">
                        <v-container>
                            <v-subheader>Телефонные номера компании</v-subheader>
                            <v-row>
                                <v-col cols="12" lg="8">
                                    <v-text-field
                                        :error-messages="errors.collect('phone')"
                                        color="yellow darken-3"
                                        dense
                                        label="Телефон"
                                        name="phone"
                                        outlined
                                        v-mask="phoneMask"
                                        v-model="company.phone"
                                        v-validate="company.rules.phone"
                                    />
                                </v-col>
                                <v-col cols="12" lg="2">
                                    <v-btn
                                        @click="addPhone"
                                        :disabled="!company.phone"
                                        color="primary"
                                        tile
                                        v-text="'Добавить'"
                                    />
                                </v-col>
                            </v-row>
                            <v-row v-if="company.phones.length" :key="index" v-for="(item, index) in company.phones">
                                <v-col cols="12" lg="8">
                                    <v-text-field
                                        name="phones"
                                        color="yellow darken-3"
                                        dense
                                        label="Дополнительный телефон"
                                        single-line
                                        disabled
                                        v-mask="phoneMask"
                                        v-model="company.phones[index]"
                                        v-validate="company.rules.phone"
                                        :error-messages="errors.collect('phones')"
                                    />
                                </v-col>
                                <v-col cols="12" lg="2">
                                    <v-btn @click="removePhone(index)" color="error" icon>
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-divider />
                            <v-subheader>Контракт компании</v-subheader>
                            <v-row>
                                <v-col cols="12" lg="5">
                                    <div style="width: 220px">
                                        <div
                                            style="overflow: hidden; height: 200px; width: 220px"
                                            class="d-flex justify-center align-center elevation-1 mb-2"
                                        >
                                            <v-img
                                                aspect-ratio="0"
                                                :src="company.contract_scan ? company.contract_scan : lazyImage"
                                                width="200"
                                                height="200"
                                            />
                                        </div>

                                        <a
                                            v-if="company.contract_scan"
                                            :href="company.contract_scan"
                                            download
                                            style="text-decoration: none;"
                                        >
                                            <v-icon v-text="'mdi-download'" />
                                        </a>
                                    </div>
                                </v-col>
                                <v-col cols="12" lg="7">
                                    <v-menu
                                        :close-on-content-click="false"
                                        class="mb-3"
                                        max-width="290px"
                                        min-width="290px"
                                        offset-y
                                        transition="scale-transition"
                                        v-model="startDateMenu"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                disabled
                                                :error-messages="errors.collect('contract_start')"
                                                data-vv-as="Дата начала контракта"
                                                dense
                                                label="Дата начала контракта"
                                                name="contract_start"
                                                outlined
                                                persistent-hint
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-model="company.contract_start"
                                                v-on="on"
                                                v-validate="company.rules.contract_start"
                                            />
                                        </template>
                                        <v-date-picker
                                            @input="startDateMenu = false"
                                            no-title
                                            v-model="company.contract_start"
                                        />
                                    </v-menu>
                                    <v-menu
                                        :close-on-content-click="false"
                                        class="mb-3"
                                        max-width="290px"
                                        min-width="290px"
                                        offset-y
                                        transition="scale-transition"
                                        v-model="endDateMenu"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                disabled
                                                :error-messages="errors.collect('contract_end')"
                                                data-vv-as="Дата окончания контракта"
                                                dense
                                                label="Дата окончания контракта"
                                                name="contract_end"
                                                outlined
                                                persistent-hint
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-model="company.contract_end"
                                                v-on="on"
                                                v-validate="company.rules.contract_end"
                                            />
                                        </template>
                                        <v-date-picker
                                            @input="endDateMenu = false"
                                            no-title
                                            v-model="company.contract_end"
                                        />
                                    </v-menu>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn width="150px" color="secondary" tile v-text="'Save'" @click="update" />
        </v-card-actions>
    </v-card>
</template>

<script>
import Snackbar from "../../facades/Snackbar";
import Company from "../../models/Company";

export default {
    name: "CompanyInfo",

    data() {
        return {
            company: new Company(),

            adminCorporate: undefined,
            companyLoading: false,

            showPassword: false,

            startDateMenu: false,
            endDateMenu: false,
            lazyImage: "/storage/img/noimage.png",

            imgDialog: false,
            dialogImgSrc: null,

            loadingEntities: false,
            entities: [],

            window: {
                width: 0,
                height: window.innerHeight - 250,
            },
        };
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },

    methods: {
        addPhone() {
            this.company.phones.push(this.company.phone);
            this.company.phone = null;
        },

        removePhone(index) {
            this.company.phones.splice(index, 1);
        },

        showImgDialog(src) {
            this.imgDialog = true;
            this.dialogImgSrc = src;
        },

        generatePassword() {
            this.adminCorporate.password = Math.random().toString(36).substring(2, 15);
            this.showPassword = true;
            this.adminCorporate.password_confirmation = this.adminCorporate.password;
        },

        getEntities() {
            this.loadingEntities = true;
            this.$http.get("/admin/corporate/get-company-entities").then(response => {
                if (response.data.entities) {
                    this.entities = response.data.entities;
                }
                this.loadingEntities = false;
            });
        },

        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.company.loading = true;
                    this.companyLoading = true;
                    this.company
                        .update({ company: this.company.company_id })
                        .then(response => {
                            this.companyLoading = false;
                            Snackbar.info(response.data.message);
                            this.company.loading = false;
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.companyLoading = false;
                            Snackbar.error(error.response.data.message);
                            Company.errors(error.response).forEach(error => this.errors.add(error));
                            this.company.loading = false;
                        });
                }
            });
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 250;
        },
    },

    created() {
        this.company.companyInfo;
        this.$store.dispatch('getCompanyPhoneMask').then()
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
