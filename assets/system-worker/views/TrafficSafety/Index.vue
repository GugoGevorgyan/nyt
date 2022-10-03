<!-- @format -->

<template>
    <v-container fluid>
        <v-card tile elevation="4">
            <v-data-table
                loader-height="2"
                dense
                :fixed-header="true"
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="car_id"
                :calculate-widths="true"
                :height="window.height"
                disable-sort
                :dark="darkMode"
            >
                <!--HEADER-->
                <template v-slot:top>
                    <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'">
                        <v-toolbar-title class="mr-5" v-text="'Дорожная безопасность'" />
                        <v-spacer />
                        <v-text-field
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            color="yellow darken-3"
                            outlined
                            dense
                            hide-details
                            label="Поиск"
                            v-model="paginated.search"
                        />
                        <v-spacer />
                        <v-btn
                            height="100%"
                            :href="$router.resolve({ name: 'traffic_safety_department_create' }).href"
                            depressed
                            small
                            :dark="darkMode"
                            v-text="'Новое транспортное средство'"
                        />
                    </v-toolbar>
                </template>

                <template v-slot:item.year="{ item }">
                    <small>{{ item.year }}</small>
                </template>
                <template v-slot:item.classes="{ item }">
                    <small>{{ commaJoin(item.classes, "class_name") }}</small>
                </template>
                <template v-slot:item.crashes_count="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <v-chip :color="0 < item.crashes_count ? '#E53935' : '#00C853'" text-color="white" x-small>
                            {{ item.crashes_count ? item.crashes_count : "нет дтп" }}
                            <v-icon x-small v-if="0 < item.crashes_count" right color="white">mdi-alert</v-icon>
                        </v-chip>
                        <v-btn icon color="grey darken-2" small @click="(crashesDialog = true), (crashes = item)">
                            <v-icon small>mdi-cogs</v-icon>
                        </v-btn>
                    </div>
                </template>
                <template v-slot:item.insurance_days_left="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <v-chip
                            :color="30 > item.insurance_days_left ? '#E53935' : '#00C853'"
                            text-color="white"
                            x-small
                        >
                            {{ 1 > item.insurance_days_left ? "истек" : item.insurance_days_left + " дня/дней" }}
                        </v-chip>
                        <v-btn icon color="grey darken-2" small @click="(insurance = item), (insuranceDialog = true)">
                            <v-icon small>mdi-cogs</v-icon>
                        </v-btn>
                    </div>
                </template>
                <template v-slot:item.inspection_days_left="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <v-chip
                            :color="30 > item.inspection_days_left ? '#E53935' : '#00C853'"
                            text-color="white"
                            x-small
                        >
                            {{ 1 > item.inspection_days_left ? "истек" : item.inspection_days_left + " дня/дней" }}
                        </v-chip>
                        <v-btn icon color="grey darken-2" small @click="(inspection = item), (inspectionDialog = true)">
                            <v-icon small v-text="'mdi-cogs'" />
                        </v-btn>
                    </div>
                </template>
                <template v-slot:item.status="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-chip x-small :color="item.status.color" outlined v-on="on">
                                    {{ item.status.text }}
                                </v-chip>
                            </template>
                            <span>{{ item.status.description }}</span>
                        </v-tooltip>

                        <v-menu right offset-x transition="scale-transition">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    :loading="statusLoading === item.car_id"
                                    icon
                                    color="grey darken-2"
                                    small
                                    v-on="on"
                                >
                                    <v-icon small>mdi-cogs</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item-group v-model="item.status_id">
                                    <v-list-item
                                        @click="changeStatus(item.car_id, status.car_status_id)"
                                        v-for="status in statuses"
                                        :key="status.car_status_id"
                                        :value="status.car_status_id"
                                    >
                                        <v-list-item-subtitle>
                                            {{ status.text }} ({{ status.description }})
                                        </v-list-item-subtitle>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-menu>
                    </div>
                </template>
                <template v-slot:item.current_driver="{ item }">
                    <div
                        v-if="item.current_driver_id && item.current_driver_info"
                        class="d-flex justify-space-between align-center pa-1"
                    >
                        <small>
                            {{ item.current_driver_info.name }}
                            {{ item.current_driver_info.patronymic }}
                            {{ item.current_driver_info.surname }}
                        </small>
                        <template>
                            <v-menu v-if="item.current_driver_info.photo" offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn small icon color="grey darken-2" v-bind="attrs" v-on="on">
                                        <v-icon small dark>mdi-camera-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-img width="200" :src="item.current_driver_info.photo"></v-img>
                            </v-menu>
                        </template>
                    </div>
                    <v-chip v-else x-small outlined color="error">нет активного водителя</v-chip>
                </template>
                <template v-slot:item.drivers="{ item }">
                    <small v-if="item.drivers.length">{{
                        commaJoin(item.drivers, { driver_info: ["name", "patronymic", "surname"] })
                    }}</small>
                    <v-chip v-else x-small outlined color="error">нет водителей</v-chip>
                </template>
                <template v-slot:item.park="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <template>
                            <small v-if="item.park">{{ item.park.name }}</small>
                            <v-chip v-else x-small outlined color="error">не прикреплен к парку</v-chip>
                        </template>
                        <v-menu bottom offset-x transition="scale-transition" :close-on-content-click="false">
                            <template v-slot:activator="{ on }">
                                <v-btn icon color="grey darken-2" small v-on="on">
                                    <v-icon small>mdi-cogs</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item style="min-height: 0">
                                    <v-list-item-subtitle>На балансе парка:</v-list-item-subtitle>
                                </v-list-item>
                                <v-list-item class="mb-2" style="min-height: 0" v-if="item.park">
                                    <v-list-item-title>
                                        {{ item.park.name }}

                                        <v-tooltip left>
                                            <template v-slot:activator="{ on }">
                                                <v-btn
                                                    :loading="parkDetachLoading"
                                                    small
                                                    icon
                                                    color="error"
                                                    @click="changePark(item.car_id)"
                                                    v-on="on"
                                                >
                                                    <v-icon small>mdi-close</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Снять сбаланса парка</span>
                                        </v-tooltip>
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item class="mb-2" style="min-height: 0" v-else>
                                    <span class="red--text">не прикреплен к парку</span>
                                </v-list-item>
                                <v-list-item style="min-height: 0">
                                    <v-list-item-subtitle>Добавить на баланс парка:</v-list-item-subtitle>
                                </v-list-item>
                                <v-list-item-group class="mt-1" v-if="availableParks(item.park_id).length">
                                    <v-list-item
                                        class="py-1"
                                        style="min-height: 0"
                                        v-for="park in availableParks(item.park_id)"
                                        :key="item.park_id"
                                    >
                                        <v-list-item-title @click="changePark(item.car_id, park.park_id)">
                                            {{ park.name }}
                                            <v-btn
                                                :loading="parkLoading === park.park_id"
                                                small
                                                icon
                                                color="grey darken-2"
                                            >
                                                <v-icon small>mdi-check</v-icon>
                                            </v-btn>
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list-item-group>
                                <v-list-item color="red" style="min-height: 0" v-else>
                                    <span class="red--text">нет доступных парков для прикрепления</span>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                small
                                icon
                                v-on="on"
                                :href="
                                    $router.resolve({
                                        name: 'traffic_safety_department_edit',
                                        params: { car_id: item.car_id },
                                    }).href
                                "
                            >
                                <v-icon small color="primary">mdi-pencil</v-icon>
                            </v-btn>
                        </template>
                        <span>Редактировать информацию</span>
                    </v-tooltip>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>

            <!--Dialogs-->
            <v-dialog persistent v-model="crashesDialog" overlay-opacity="0.7" max-width="800" width="100%">
                <crashes-details
                    :car="crashes"
                    @close="(crashes = undefined), (crashesDialog = false)"
                    @change="updateData()"
                    @addCrash="addCrash($event)"
                    @dellCrash="dellCrash($event)"
                />
            </v-dialog>
            <v-dialog persistent v-model="inspectionDialog" max-width="650" width="100%">
                <inspection-details
                    :inspection="inspection"
                    @close="(inspection = undefined), (inspectionDialog = false)"
                    @change="updateData()"
                />
            </v-dialog>
            <v-dialog persistent v-model="insuranceDialog" max-width="650" width="100%">
                <insurance-details
                    :insurance="insurance"
                    @close="(insurance = undefined), (insuranceDialog = false)"
                    @change="updateData()"
                />
            </v-dialog>
        </v-card>
    </v-container>
</template>
<script>
import TrafficSafetyPagination from "./../../forms/TrafficSafetyPagination";
import InspectionDetails from "./InspectionDetails";
import InsuranceDetails from "./InsuranceDetails";
import CrashesDetails from "./CrashesDetails";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";
export default {
    props: {
        statuses: {
            required: true,
            type: Array,
        },
        parks: {
            required: true,
            type: Array,
        },
    },

    components: {
        InspectionDetails: InspectionDetails,
        InsuranceDetails: InsuranceDetails,
        CrashesDetails: CrashesDetails,
    },

    data() {
        return {
            paginated: new TrafficSafetyPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    search: this.$route.query["search"],
                },
                "traffic-safety/paginate",
            ),

            inspection: undefined,
            inspectionDialog: false,
            insurance: undefined,
            insuranceDialog: false,
            crashes: undefined,
            crashesDialog: false,

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 198,

            parkDetachLoading: false,
            parkLoading: false,
            statusLoading: false,
        };
    },

    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "traffic_safety_department",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCars;
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "traffic_safety_department",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCars;
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "traffic_safety_department",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCars;
                },
            );
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },

    methods: {
        commaJoin(arr, keys) {
            if ("object" === typeof keys) {
                return arr
                    .map(item => {
                        let result = [];
                        Object.keys(keys).forEach(key => {
                            let x = [];
                            keys[key].forEach(value => {
                                x.push(item[key][value]);
                            });
                            result.push(x.join(" "));
                        });
                        return result;
                    })
                    .join(", ");
            } else {
                return keys
                    ? arr
                          .map(item => {
                              return item[keys];
                          })
                          .join(", ")
                    : arr.join(", ");
            }
        },
        availableParks(park_id) {
            return this.parks.filter(item => item.park_id !== park_id);
        },

        changePark(car_id, park_id = null) {
            park_id ? (this.parkLoading = park_id) : (this.parkDetachLoading = true);

            let data = {
                park_id: park_id,
                car_id: car_id,
            };

            axios
                .post(this.url + "traffic-safety/park/update", data)
                .then(response => {
                    this.paginated.getCars;
                    this.parkLoading = false;
                    this.parkDetachLoading = false;
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.parkLoading = false;
                    this.parkDetachLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        changeStatus(car_id, status_id) {
            this.statusLoading = car_id;

            let data = {
                status_id: status_id,
                car_id: car_id,
            };

            axios
                .post(this.url + "traffic-safety/status/update", data)
                .then(response => {
                    this.statusLoading = false;
                    this.paginated.getCars;
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.statusLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        updateData() {
            this.paginated.getCars;
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },
        addCrash(car_id) {
            this.paginated.data.map(function (item) {
                if (item.car_id === car_id) {
                    item.crashes_count++;
                }
            });
        },
        dellCrash(car_id) {
            this.paginated.data.map(function (item) {
                if (item.car_id === car_id) {
                    item.crashes_count--;
                }
            });
        },
    },

    created() {
        this.paginated.getCars;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
