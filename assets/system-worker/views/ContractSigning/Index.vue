<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <v-layout row>
            <v-flex md12 xl12 xs12>
                <v-card outlined>
                    <v-data-table
                        class="elevation-4"
                        loader-height="2"
                        dense
                        :fixed-header="true"
                        :headers="paginated.headers"
                        :items="paginated.data"
                        :items-per-page="Number(paginated.per_page)"
                        :loading="paginated.loading"
                        hide-default-footer
                        item-key="driver_id"
                        :calculate-widths="true"
                        :height="window.height"
                        disable-sort
                    >
                        <!--HEADER-->
                        <template v-slot:top>
                            <v-toolbar flat color="grey lighten-3">
                                <v-toolbar-title class="mr-5">Подписание контракта</v-toolbar-title>
                                <v-spacer/>
                                <v-text-field
                                    append-icon="mdi-magnify"
                                    clearable
                                    color="yellow darken-3"
                                    hide-details
                                    label="Поиск"
                                    single-line
                                    outlined
                                    dense
                                    v-model="paginated.search"
                                />
                                <v-spacer/>
                            </v-toolbar>
                        </template>

                        <!--CONTRACTS-->
                        <template v-slot:item.contracts="{ item }">
                            <v-chip v-if="item.contracts && item.contracts.length" small outlined color="success">
                                Количество ранних контрактов водителя: {{ item.contracts.length }}
                            </v-chip>
                            <v-chip small v-else outlined color="error"> Водитель раннее не имел контрактов </v-chip>
                        </template>

                        <!-- Contract file-->
                        <template v-slot:item.print="{ item }">
                            <v-btn small icon @click="showContractDialog(item)">
                                <v-icon small color="primary">mdi-printer-check</v-icon>
                            </v-btn>
                        </template>

                        <!--table footer-->
                        <template v-slot:footer>
                            <div ref="footer">
                                <v-divider class="ma-0" />
                                <v-row no-gutters class="py-1">
                                    <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                                    <v-col cols="12" md="8" class="d-flex justify-center align-center">
                                        <v-tooltip left>
                                            <template v-slot:activator="{ on, attrs }">
                                                <div v-bind="attrs" v-on="on">
                                                    <v-pagination
                                                        :length="paginated.last_page"
                                                        :total-visible="7"
                                                        circle
                                                        color="yellow darken-3"
                                                        v-model="paginated.current_page"
                                                    />
                                                </div>
                                            </template>
                                            <span>
                                                {{
                                                    Number(paginated.total)
                                                        ? `${paginated.from}-${paginated.to} из ${paginated.total}`
                                                        : "Нет данных"
                                                }}
                                            </span>
                                        </v-tooltip>
                                    </v-col>
                                    <v-col cols="12" md="2" class="d-flex justify-center align-center">
                                        <v-menu offset-y max-width="100">
                                            <template v-slot:activator="{ on: menu, attrs }">
                                                <v-tooltip left>
                                                    <template v-slot:activator="{ on: tooltip }">
                                                        <v-btn
                                                            fab
                                                            small
                                                            dark
                                                            color="yellow darken-3"
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="{ ...tooltip, ...menu }"
                                                        >
                                                            {{ paginated.per_page }}
                                                        </v-btn>
                                                    </template>
                                                    <span>строк на странице</span>
                                                </v-tooltip>
                                            </template>
                                            <v-list>
                                                <v-list-item
                                                    :disabled="paginated.per_page === item"
                                                    color="yellow darken-3"
                                                    v-for="(item, index) in paginated.perPages"
                                                    :key="index"
                                                    @click="paginated.per_page = item"
                                                >
                                                    <v-list-item-title>{{ item }}</v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </div>
                        </template>
                    </v-data-table>

                    <v-dialog persistent v-model="contractDialog" max-width="600" width="100%">
                        <v-card v-if="contractDriver">
                            <v-card-title>Распечатка контракта</v-card-title>
                            <v-divider />
                            <v-card-text>
                                <p class="my-3 title text-center"
                                    >Создать и распечатать {{ contractDriver.type.type }} контракт с
                                    {{ contractDriver.driver_info.name }} {{ contractDriver.driver_info.patronymic }}
                                    {{ contractDriver.driver_info.surname }}
                                </p>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" lg="6">
                                            <v-menu
                                                v-model="endDateMenu"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="290px"
                                                class="mb-3"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        dense
                                                        outlined
                                                        v-model="contract.expiration_day"
                                                        label="Дата окончания контракта"
                                                        persistent-hint
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        v-on="on"
                                                        name="expiration_day"
                                                        :error-messages="errors.collect('expiration_day')"
                                                        v-validate="contract.rules.expiration_day"
                                                        data-vv-as="contract expiration day"
                                                    />
                                                </template>
                                                <v-date-picker
                                                    v-model="contract.expiration_day"
                                                    no-title
                                                    @input="endDateMenu = false"
                                                    :min="endDateMin()"
                                                />
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="12" lg="6">
                                            <v-menu
                                                v-model="workDateMenu"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="290px"
                                                class="mb-3"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        dense
                                                        outlined
                                                        :disabled="!contract.expiration_day"
                                                        v-model="contract.work_start_day"
                                                        label="Первый день работы"
                                                        persistent-hint
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        v-on="on"
                                                        name="work_start_day"
                                                        :error-messages="errors.collect('work_start_day')"
                                                        v-validate="contract.rules.work_start_day"
                                                        data-vv-as="день начала работы"
                                                    />
                                                </template>
                                                <v-date-picker
                                                    v-model="contract.work_start_day"
                                                    no-title
                                                    @input="workDateMenu = false"
                                                    :max="contract.expiration_day"
                                                    :min="workStartDateMin()"
                                                />
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn
                                    :disabled="disableButtons"
                                    color="error darken-1"
                                    text
                                    @click="closeContractDialog()"
                                    >Отмена</v-btn
                                >
                                <v-btn
                                    :disabled="disableButtons"
                                    :loading="contractLoading"
                                    color="primary darken-1"
                                    text
                                    @click="getContractFile()"
                                >
                                    {{ printedContract ? "Распечатать снова" : "Распечатать" }}
                                </v-btn>
                                <v-btn
                                    :disabled="disableButtons"
                                    :loading="signLoading"
                                    v-if="printedContract"
                                    color="primary darken-1"
                                    @click="signContract()"
                                >
                                    Подписать контракт
                                    <v-icon>mdi-check</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
import ContractSigningPagination from "../../forms/ContractSigningPagination";
import ContractSigning from "../../models/ContractSigning";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";

export default {
    data() {
        return {
            paginated: new ContractSigningPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    search: this.$route.query["search"],
                },
                "contract-signing/paginate",
            ),

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 198,

            contractDriver: undefined,
            contractDialog: false,
            contractLoading: false,
            printedContract: false,

            signLoading: false,
            disableButtons: false,

            contract: new ContractSigning(),

            endDateMenu: false,
            workDateMenu: false,
        };
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "contract_signing",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getDrivers;
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "contract_signing",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getDrivers;
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "contract_signing",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getDrivers;
                },
            );
        },
    },
    methods: {
        workStartDateMin() {
            let dateObj = new Date();
            return dateObj.toISOString();
        },

        endDateMin() {
            let dateObj = new Date();
            dateObj.setDate(dateObj.getDate() + 1);
            return dateObj.toISOString();
        },

        showContractDialog(item) {
            this.contractDialog = true;
            this.contractDriver = item;
            this.contract = new ContractSigning({ driver_id: item.driver_id });
        },

        closeContractDialog() {
            this.contractDialog = false;
            this.contractDriver = undefined;
            this.contract = new ContractSigning();
            this.printedContract = undefined;
        },

        getContractFile() {
            this.disableButtons = true;
            this.contractLoading = true;
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.contract
                        .getContractFile()
                        .then(response => {
                            this.contractLoading = false;
                            this.disableButtons = false;
                            let w = window.open("/" + response.data.file);
                            w.print();
                            this.printedContract = new ContractSigning({ ...response.data.contract });
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.disableButtons = false;
                            this.printedContract = false;
                            this.contractLoading = false;
                        });
                }
            });
        },

        signContract() {
            this.disableButtons = true;
            this.signLoading = true;
            this.printedContract
                .signContract()
                .then(response => {
                    this.signLoading = false;
                    this.disableButtons = false;
                    Snackbar.info(response.data.message);
                    this.closeContractDialog();
                    this.paginated.getDrivers;
                })
                .catch(error => {
                    this.disableButtons = false;
                    this.signLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },
    },
    created() {
        this.paginated.getDrivers;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
