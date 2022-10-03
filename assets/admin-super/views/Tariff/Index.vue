<!-- @format -->

<template>
    <v-container fluid>
        <v-snackbar
            v-model="snackbar.display"
            :top='true'
            :right='true'
            :color="snackbar.success ? 'green' : 'red'"
        >
            {{ snackbar.snackbarText }}
        </v-snackbar>
        <v-flex class="mt-1">
            <v-data-table
                loader-height="2"
                :loading="paginated.loading"
                :headers="paginated.headers"
                :calculate-widths="true"
                :items="paginated.data"
                :fixed-header="true"
                :height="window.height"
                item-key="tariff_id"
                class="elevation-4 rounded-1"
                dense
                :items-per-page="Number(paginated.per_page)"
                hide-default-footer
            >
                <!--table header-->
                <template v-slot:top>
                    <div ref="header">
                        <v-toolbar flat color="grey lighten-5" height="55">
                            <v-toolbar-title>Tарифы</v-toolbar-title>
                            <v-divider class="mx-3" inset vertical />
                            <v-spacer />
                            <v-text-field
                                v-model="paginated.search"
                                prepend-inner-icon="mdi-magnify"
                                color="primary"
                                background-color="white"
                                label="Поиск"
                                hide-details
                                single-line
                                clearable
                                outlined
                                dense
                            />
                            <v-spacer />
                            <v-btn :href="`tariff/create`" color="primary" outlined dark v-text="' Новый тариф '" />
                        </v-toolbar>
                    </div>
                    <v-divider />
                </template>

                <!--table content-->
                <template v-slot:item.action="{ item }">
                    <v-btn :href="`tariff/edit/` + item.tariff_id" icon small color="primary">
                        <v-icon small> mdi-pencil-outline </v-icon>
                    </v-btn>
                    <v-btn @click="showCopy(item)" icon small color="success">
                        <v-icon small> mdi-content-copy </v-icon>
                    </v-btn>
                    <v-btn @click="showDelete(item)" icon small color="error">
                        <v-icon small> mdi-delete </v-icon>
                    </v-btn>
                </template>

                <template v-slot:item.status="{ item }">
                    <v-icon
                        small
                        :color="item.status ? 'green' : 'red'"
                        v-text="item.status ? 'mdi-check-outline' : 'mdi-close-outline'"
                    />
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated"/>
                </template>
            </v-data-table>
        </v-flex>

        <!--delete dialog-->
        <v-dialog persistent v-model="deleteDialog" max-width="500px" width="100%">
            <v-card v-if="deleteItem">
                <v-card-title>
                    <span class="headline">Вы уверены?</span>
                </v-card-title>
                <v-divider/>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-subheader
                                    >Удалить тариф։ <strong>{{ deleteItem.name }}</strong></v-subheader
                                >
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="error" @click="closeDelete()">отмена</v-btn>
                    <v-btn :loading="deleteLoading" color="primary" @click="deleteTariff()">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--copy dialog-->
        <v-dialog persistent v-model="copyTariffDialog" max-width="700px" width="100%">
            <v-card>
                <v-card-title>
                    <span class="headline">Копировать тарифф: {{ copyTariff.old_name }}</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    :error-messages="errors.collect('name')"
                                    color="yellow darken-3"
                                    label="Название"
                                    name="name"
                                    outlined
                                    dense
                                    v-model="copyTariff.name"
                                    v-validate="copyTariff.rules.name"
                                    data-vv-as="название"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select
                                    :loading="carClassesLoading"
                                    :eager="true"
                                    :error-messages="errors.collect('car_class_id')"
                                    :items="carClasses"
                                    color="yellow darken-3"
                                    item-color="yellow darken-3"
                                    item-text="class_name"
                                    item-value="car_class_id"
                                    outlined
                                    dense
                                    name="car_class_id"
                                    persistent-hint
                                    placeholder="Выберите класс автомобилей"
                                    label="Класс автомобилей"
                                    v-model="copyTariff.car_class_id"
                                    v-validate="copyTariff.rules.car_class_id"
                                    data-vv-as="класс автомобилей"
                                />
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-if="copyTariff.typeFields().includes('price_min')"
                                    type="number"
                                    :error-messages="errors.collect('price_min')"
                                    color="yellow darken-3"
                                    label="Цена минуты"
                                    name="price_min"
                                    outlined
                                    dense
                                    v-model="copyTariff.price_min"
                                    v-validate="copyTariff.rules.price_min"
                                    data-vv-as="цена минуты"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-if="copyTariff.typeFields().includes('price_km')"
                                    type="number"
                                    :error-messages="errors.collect('price_km')"
                                    color="yellow darken-3"
                                    label="Цена киломентра"
                                    name="price_km"
                                    outlined
                                    dense
                                    v-model="copyTariff.price_km"
                                    v-validate="copyTariff.rules.price_km"
                                    data-vv-as="цена километра"
                                />
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="error" @click="closeCopy()">отмена</v-btn>
                    <v-btn :loading="copyTariffLoading" color="primary" @click="makeCopy()">копировать</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import Tariff from "../../models/Tariff";
import CopyTariff from "../../models/CopyTariff";
import FranchisesPagination from "../../forms/FranchisesPagination";
import TariffsPagination from "../../forms/TariffsPagination";
import footer from "../../components/TableFooter";

export default {
    name: "Tariff",
    data() {
        return {
            paginated: new TariffsPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                },
                "/admin/super/tariff/paginate",
            ),

            tariff: new Tariff(),
            snackbar: {
                display: false,
                success: false,
                snackbarText: ''
            },

            carClasses: [],
            carClassesLoading: false,

            deleteDialog: false,
            deleteItem: null,
            deleteLoading: false,

            copyTariffDialog: false,
            copyTariff: new CopyTariff(),
            copyTariffLoading: false,

            window: {
                width: 0,
                height: window.innerHeight - 205,
                heightDiff: 205,
            },
        };
    },
    components: {
        "table-footer": footer,
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.tariff.index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getTariffs();
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.tariff.index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getTariffs();
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.tariff.index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getTariffs();
                },
            );
        },
    },

    methods: {
        /*delete*/
        showDelete(item) {
            this.deleteItem = item;
            this.deleteDialog = true;
        },
        closeDelete() {
            this.deleteItem = null;
            this.deleteDialog = false;
        },
        deleteTariff() {
            this.deleteLoading = true;
            axios
                .delete("/admin/super/tariff/destroy/" + this.deleteItem.tariff_id)
                .then(response => {
                    this.deleteLoading = false;
                    this.paginated.getTariffs();
                    this.closeDelete();
                    this.snackbar.success = true;
                    this.snackbar.display = true;
                    this.snackbar.snackbarText = response.data.message;
                })
                .catch(error => {
                    this.deleteLoading = false;
                    this.snackbar.success = false;
                    this.snackbar.display = true;
                    this.snackbar.snackbarText = error.response.data.message;
                });
        },

        /*copy*/
        showCopy(item) {
            this.getCarClasses();
            this.copyTariff = new CopyTariff(item);
            this.copyTariffDialog = true;
        },
        closeCopy() {
            this.copyTariffDialog = false;
            this.copyTariff = new CopyTariff();
        },
        makeCopy() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.copyTariffLoading = true;
                    axios
                        .post(`/admin/super/tariff/copy/` + this.copyTariff.tariff_id, this.copyTariff.createFormData())
                        .then(response => {
                            this.copyTariffLoading = false;
                            this.paginated.getTariffs();
                            this.closeCopy();
                            this.snackbar.success = true;
                            this.snackbar.display = true;
                            this.snackbar.snackbarText = response.data.message;
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                let errors = error.response.data.errors;

                                for (let key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        this.errors.add({
                                            field: key,
                                            msg: errors[key][0],
                                        });
                                    }
                                }
                            }

                            this.copyTariffLoading = false;
                            this.snackbar.success = false;
                            this.snackbar.display = true;
                            this.snackbar.snackbarText = error.response.data.message;
                        });
                }
            });
        },

        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDiff;
        },

        /*get data*/
        getCarClasses() {
            this.carClassesLoading = true;
            axios
                .get(`/admin/super/get/car-classes`)
                .then(response => {
                    this.carClassesLoading = false;
                    this.carClasses = response.data;
                })
                .catch(error => {
                    this.carClassesLoading = false;
                    this.snackbar.success = false;
                    this.snackbar.display = true;
                    this.snackbar.snackbarText = error.response.data.message;
                });
        },
    },

    created() {
        this.paginated.getTariffs();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
<style scoped>
html {
    overflow: hidden;
}
</style>
