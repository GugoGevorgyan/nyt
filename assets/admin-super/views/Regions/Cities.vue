<!-- @format -->

<template>
    <v-container fluid>
        <v-card elevation="6" tile>
            <v-data-table
                :headers="paginated.headers"
                :items="paginated._payload"
                :items-per-page="paginated.per_page"
                :loading="paginated.loading"
                :loader-height="1"
                fixed-header
                hide-default-footer
                item-key="city_id"
                multi-sort
                dense
                v-model="paginated.selected"
                :height="window.height"
            >
                <template v-slot:top>
                    <v-toolbar flat color="grey lighten-5">
                        <v-row>
                            <v-col cols="12" md="3" sm="3" xl="3" lg="3" xs="3">
                                <v-autocomplete
                                    label="Страна"
                                    :items="countries"
                                    background-color="white"
                                    clearable
                                    multiple
                                    item-value="country_id"
                                    item-text="name"
                                    v-model="paginated.countries"
                                    outlined
                                    hide-details
                                    dense
                                >
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip small v-if="1 > index">
                                            <span>{{ item.name }}</span>
                                        </v-chip>
                                        <span v-if="1 === index" class="grey--text caption">
                                            (+{{ paginated.countries.length - 1 }} других)
                                        </span>
                                        <v-icon color="grey" v-if="1 === index" v-text="'mdi-magnify'" />
                                    </template>
                                </v-autocomplete>
                            </v-col>
                        </v-row>
                    </v-toolbar>
                </template>

                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script>
import CitiesPager from "../../forms/CitiesPager";
import footer from "../../components/TableFooter";

export default {
    name: "Cities",

    props: {
        countries: {
            required: true,
        },
    },

    components: {
        "table-footer": footer,
    },

    data() {
        return {
            paginated: new CitiesPager(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per_page"]),
                    search: this.$route.query["search"],
                    countries: this.$route.query["countries"],
                },
                "cities/pager",
            ),
            window: {
                width: 0,
                height: 0,
                heightDif: 210,
            },
        };
    },

    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.cities",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.cities;
                },
            );
        },
        "paginated.per_page": function () {
            this.$router.push(
                {
                    name: "admin.super.cities",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.cities;
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.cities",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.cities;
                },
            );
        },
        "paginated.countries": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.cities",
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                        countries: this.paginated.countries,
                    },
                },
                () => {
                    this.paginated.cities;
                },
            );
        },
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
    },

    created() {
        this.paginated.cities;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>

<style scoped></style>
