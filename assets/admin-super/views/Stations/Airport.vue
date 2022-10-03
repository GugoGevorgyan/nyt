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
                item-key="airport_id"
                class="elevation-1"
                dense
                hide-default-footer
                show-select
                :items-per-page="Number(paginated.per_page)"
                @click:row="editAirport"
                :item-class="rowClasses"
                v-model="paginated.selected"
                selectable-key="airport_id"
                single-select
            >
                <!--table header-->
                <template v-slot:top>
                    <div ref="header">
                        <v-toolbar flat color="grey lighten-5">
                            <v-toolbar-title>Аэропорты</v-toolbar-title>
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
                                :items="airport.cityData"
                                item-text="name"
                                item-value="airport_id"
                                color="yellow darken-3"
                                single-line
                                hide-details
                                class="rounded-0"
                            />
                            <v-spacer />
                            <v-btn outlined tile @click="openCreateDialog"> Новый Аеропорт</v-btn>
                        </v-toolbar>
                    </div>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <div ref="footer">
                        <v-divider class="ma-0" />
                        <v-row no-gutters class="py-1">
                            <v-col cols="12" md="1" class="d-flex justify-center align-center">
                                <v-tooltip right v-if="paginated.selected.length">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn fab small color="error" v-bind="attrs" v-on="on" @click="deletesDialog = true">
                                            <v-icon>mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>удалить отмеченные</span>
                                </v-tooltip>
                            </v-col>
                            <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                            <v-col cols="12" md="6" class="d-flex justify-center align-center">
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
                            <v-col cols="12" md="3" class="d-flex justify-center align-center float-right">
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
            <airport-create @createDialog="createDialog = $event" :paginated="paginated" />
        </v-dialog>

        <v-dialog v-model="editDialog" v-if="editDialog" max-width="1000" width="100%" persistent>
            <airport-edit @editDialog="editDialog = $event" :airport-data="editedAirportData" :paginated="paginated" />
        </v-dialog>

        <v-dialog max-width="400" width="100%" v-model="deletesDialog" v-if="deletesDialog" persistent>
            <airport-delete :model="airport" :selected="paginated.selected" @deleteDialog="deletesDialog = $event" />
        </v-dialog>
    </v-container>
</template>

<script>
import Airport from '../../forms/station/Airport';
import AirportModel from '../../models/stations/Airport';
import MapComponent from '../../components/Stations/MapComponent';
import AirportCreateComponent from '../../components/Stations/Airport/AirportCreateComponent';
import AirportEditComponent from '../../components/Stations/Airport/AirportEditComponent';
import AirportDeleteComponent from '../../components/Stations/Airport/AirportDeleteComponent';

export default {
    name: 'Airport',

    components: {
        'map-form': MapComponent,
        'airport-create': AirportCreateComponent,
        'airport-edit': AirportEditComponent,
        'airport-delete': AirportDeleteComponent,
    },

    data() {
        return {
            createDialog: false,
            deletesDialog: false,
            editDialog: false,
            editedAirportData: [],
            airport: new AirportModel(),
            paginated: new Airport(
                {
                    current_page: Number(this.$route.query['page']),
                    per_page: Number(this.$route.query['per-page']),
                },
                '/admin/super/station/airports/paginate',
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
                    name: 'admin.super.station.airport',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.airports;
                },
            );
        },
        'paginated.per_page': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.airport',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.airports;
                },
            );
        },
        'paginated.search': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.airport',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.airports;
                },
            );
        },
        'paginated.city': function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: 'admin.super.station.airport',
                    query: {
                        page: this.paginated.current_page,
                        'per-page': this.paginated.per_page,
                        search: this.paginated.search,
                        city: this.paginated.city,
                    },
                },
                () => {
                    this.paginated.airports;
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
                return newItem.airport_id === item.airport_id;
            });

            return ~index ? 'pointer' : 'pointer';
        },

        editAirport(airport) {
            this.editDialog = true;
            this.editedAirportData = airport;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 207;
        },
    },

    created() {
        this.airport.cities;
        this.paginated.airports;
        window.addEventListener('resize', this.handleResize);
    },
};
</script>

<style scoped></style>
