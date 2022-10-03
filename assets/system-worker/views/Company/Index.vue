<!-- @format -->
<template>
    <v-container fluid>
        <v-data-table
            dense
            :headers="paginated.headers"
            :items="paginated.data"
            class="elevation-4"
            :loading="paginated.loading"
            loader-height="2"
            hide-default-footer
            :height="window.height"
            :fixed-header="true"
            :items-per-page="Number(paginated.per_page)"
            item-key="company_id"
            :calculate-widths="true"
        >
            <!--table header-->
            <template v-slot:top>
                <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'" height="55px">
                    <v-toolbar-title>Компании</v-toolbar-title>
                    <v-divider vertical class="mx-3" />
                    <v-text-field
                        style="max-width: 350px"
                        prepend-inner-icon="mdi-magnify"
                        color="yellow darken-3"
                        v-model="paginated.search"
                        label="Поиск по названию или эл. адресу"
                        class="rounded-1 mr-3"
                        hide-details
                        single-line
                        clearable
                        outlined
                        dense
                    />
                    <v-divider vertical class="mx-3" />
                    <v-spacer />
                    <v-btn
                        v-text="'Добавить компанию'"
                        :href="$router.resolve({ name: 'company_create' }).href"
                        depressed
                        height="100%"
                        class="rounded-1"
                    />
                </v-toolbar>
            </template>

            <template v-slot:item.name="{ item }">
                {{ item.name }}
            </template>

            <template v-slot:item.action="{ item }">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            small
                            color="primary"
                            icon
                            :href="
                                $router.resolve({ name: 'company_edit', params: { company_id: item.company_id } }).href
                            "
                        >
                            <v-icon small>mdi-pencil-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Редактировать информацию компании</span>
                </v-tooltip>

                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" small color="primary" icon @click="showTariffDialog(item)">
                            <v-icon small> mdi-arrow-collapse </v-icon>
                        </v-btn>
                    </template>
                    <span>Тарифы компании</span>
                </v-tooltip>

                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-btn small icon v-on="on" @click="showRemove(item)">
                            <v-icon small color="error">mdi-close</v-icon>
                        </v-btn>
                    </template>
                    <span>Удалить компанию</span>
                </v-tooltip>
            </template>

            <!--table footer-->
            <template v-slot:footer>
                <table-footer :paginated="paginated" />
            </template>
        </v-data-table>

        <!--tariff attach-->
        <v-dialog
            v-model="tariffAttacheDialog"
            v-if="tariffAttacheDialog"
            max-width="500"
            overlay-opacity="0.7"
            width="100%"
            class="d-flex justify-end align-end"
        >
            <tariff-attach :company="companyAttached" @close="tariffAttacheDialog = $event" @updated="updateData()" />
        </v-dialog>

        <!--remove dialog-->
        <v-dialog v-model="removeDialog" persistent max-width="600px" max-height="650px" width="100%">
            <v-card v-if="removeItem">
                <v-card-title class="title">Вы уверены, что хотите удалить компанию?</v-card-title>

                <v-card-text>
                    <v-alert outlined type="error">
                        После удалеиня информация о компании <strong>{{ removeItem.name }}</strong> будет утерена!
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn small @click="closeRemove()" text>отмена</v-btn>
                    <v-btn small :loading="removeLoading" @click="remove()" color="error">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import CompanyPagination from "./../../forms/CompanyPagination";
import AttacheTariff from "./TariffAttachDialog";
import Snackbar from "../../facades/Snackbar";

export default {
    name: "get_company",

    components: { "tariff-attach": AttacheTariff },

    data() {
        return {
            paginated: new CompanyPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    sort_by: this.$route.query["sort-by"],
                    sort_desc: this.setSortDesc(this.$route.query["sort-desc"]),
                    search: this.$route.query["search"],
                },
                "company/paginate",
            ),

            tariffAttacheDialog: false,
            companyAttached: undefined,

            removeDialog: false,
            removeItem: null,
            removeLoading: false,

            window: {
                width: 0,
                height: 0,
                heightDif: 182,
            },
        };
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
    },

    watch: {
        "paginated.current_page": function (newVal, oldVal) {
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.per_page": function (newVal, oldVal) {
            if (Number(newVal) !== Number(oldVal)) {
                this.paginated.current_page = 1;
                this.setQuery();
            }
        },
        "paginated.sort_by": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.sort_desc": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            if (Number(newVal) !== Number(oldVal)) {
                this.setQuery();
            }
        },
        "paginated.search": function (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.paginated.current_page = 1;
                this.setQuery();
            }
        },
    },
    methods: {
        /*paginate*/

        setQuery() {
            this.$router.push(
                {
                    name: "get_company",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        "sort-by": this.paginated.sort_by,
                        "sort-desc": this.paginated.sort_desc,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },

        updateData() {
            this.paginated.getData;
        },

        setSortDesc(sort) {
            return Array.isArray(sort) ? sort : Boolean(sort);
        },

        /*remove*/
        showRemove(item) {
            this.removeItem = item;
            this.removeDialog = true;
        },

        closeRemove() {
            this.removeItem = null;
            this.removeDialog = false;
        },

        remove() {
            if (this.removeItem) {
                this.removeLoading = true;
                this.$http
                    .delete("company/destroy/" + this.removeItem.company_id)
                    .then(response => {
                        Snackbar.info(response.data.message);
                        this.removeLoading = false;
                        this.closeRemove();
                        this.paginated.getData;
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.removeLoading = false;
                    });
            }
        },

        /*window*/

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        showTariffDialog(item) {
            this.companyAttached = item;
            this.tariffAttacheDialog = true;
        },
    },
    created() {
        this.paginated.getData;
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>
