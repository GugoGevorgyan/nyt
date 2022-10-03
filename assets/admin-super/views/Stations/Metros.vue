<!-- @format -->

<template>
    <v-container fluid>
        <v-card elevation="6" tile>
            <v-data-table
                loader-height="2"
                :loading="paginated.loading"
                :headers="paginated.headers"
                :calculate-widths="true"
                :items="paginated._payload"
                :fixed-header="true"
                :height="window.height"
                item-key="metro_id"
                class="elevation-1"
                dense
                :items-per-page="Number(paginated.per_page)"
                hide-default-footer
                :item-class="rowClasses"
                @click:row="editMetro"
            >
                <!--table header-->
                <template v-slot:top>
                    <div ref="header">
                        <v-toolbar flat color="grey lighten-5">
                            <v-toolbar-title>Метро</v-toolbar-title>
                            <v-divider class="mx-3" inset vertical />
                            <v-text-field
                                v-model="paginated.search"
                                prepend-inner-icon="mdi-magnify"
                                color="yellow darken-3"
                                label="Поиск (Имя или Город)"
                                hide-details
                                single-line
                                clearable
                                class="rounded-0"
                            />
                            <v-divider class="mx-3" inset vertical />
                            <v-autocomplete
                                prepend-inner-icon="mdi-city-variant-outline"
                                label="Города"
                                v-model="paginated.city"
                                clearable
                                :items="metro.cityData"
                                item-text="name"
                                item-value="metro_id"
                                color="yellow darken-3"
                                single-line
                                hide-details
                                class="rounded-0"
                            />
                            <v-spacer />
                            <v-btn outlined tile @click="openCreateDialog">Новое метро</v-btn>
                        </v-toolbar>
                    </div>
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
                                    {{ Number(paginated.total) ? `${paginated.from}-${paginated.to} из ${paginated.total}` : 'Нет данных' }}
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
        </v-card>

        <v-dialog v-model="createDialog" v-if="createDialog" max-width="1000" width="100%" persistent>
            <metro-create @createDialog="createDialog = $event" :paginated="paginated" />
        </v-dialog>

        <v-dialog v-model="editDialog" v-if="editDialog" max-width="1000" width="100%" persistent>
            <metro-edit @editDialog="editDialog = $event" :metro-data="editedMetroData" :paginated="paginated" />
        </v-dialog>
    </v-container>
</template>

<script>
import MetroModel from '../../models/stations/Metro';
import MapComponent from '../../components/Stations/MapComponent';
import MetroCreateComponent from '../../components/Stations/Metro/MetroCreateComponent';
import MetroEditComponent from '../../components/Stations/Metro/MetroEditComponent';
import Metro from '../../forms/station/Metro'

export default {
    name: 'Metros',

    components: { 'map-form': MapComponent, 'metro-create': MetroCreateComponent, 'metro-edit': MetroEditComponent },

    data() {
        return {
            editedMetroData: [],
            createDialog: false,
            editDialog: false,
            metro: new MetroModel(),
            paginated: new Metro(
                {
                    current_page: Number(this.$route.query['page']),
                    per_page: Number(this.$route.query['per-page']),
                },
                '/admin/super/station/metros/paginate',
            ),
            window: {
                width: 0,
                height: window.innerHeight - 207,
            },
        };
    },

    watch: {
        'paginated.current_page': function () {
            this.$router.push(
                {
                    name: 'admin.super.station.metro',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.metros;
                },
            );
        },
        'paginated.per_page': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.metro',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.metros;
                },
            );
        },
        'paginated.search': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.metro',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.metros;
                },
            );
        },
        'paginated.city': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.metro',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.metros;
                },
            );
        },
    },

    methods: {
        openCreateDialog() {
            this.createDialog = true;
        },

        rowClasses(item) {
            let index = this.paginated._payload.findIndex(newItem => {
                return newItem.metro_id === item.metro_id;
            });

            return ~index ? 'pointer' : 'pointer';
        },

        editMetro(metro) {
            this.editDialog = true;
            this.editedMetroData = metro;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 207;
        },
    },

    created() {
        this.metro.cities;
        this.paginated.metros;
        window.addEventListener('resize', this.handleResize);
    },
};
</script>

<style scoped></style>
