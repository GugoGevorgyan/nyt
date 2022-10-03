<!-- @format -->

<template>
    <v-card v-if="car" elevation="4" class="border">
        <v-card-title class="grey lighten-5">
            ДТП борта {{ car.garage_number }}
            <v-spacer />
            <v-btn :disabled="0 === tab" icon @click="tab = 0">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-icon color="primary" v-on="on">mdi-format-list-bulleted</v-icon>
                    </template>
                    <span>Список ДТП</span>
                </v-tooltip>
            </v-btn>
            <v-btn :disabled="1 === tab" icon @click="tab = 1">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-icon color="primary" v-on="on">mdi-plus</v-icon>
                    </template>
                    <span>Создать ДТП</span>
                </v-tooltip>
            </v-btn>
            <v-btn icon @click="clear()">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-icon color="error" v-on="on">mdi-close</v-icon>
                    </template>
                    <span>Закрыть</span>
                </v-tooltip>
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-tabs-items v-model="tab">
            <!--     VIEW       -->
            <v-tab-item eager class="py-1">
                <v-card-text style="height: 550px; overflow-y: auto">
                    <div v-if="paginated.loading" class="d-flex justify-center align-center" style="height: 100%">
                        <v-progress-circular :size="40" indeterminate color="yellow darken-3" />
                    </div>
                    <template v-else>
                        <v-expansion-panels v-if="paginated.data.length" focusable class="mb-6">
                            <v-expansion-panel v-for="crash in paginated.data" :key="crash.car_crash_id">
                                <v-expansion-panel-header expand-icon="mdi-menu-down">
                                    <v-row align="center">
                                        <v-col class="shrink">
                                            <v-icon :color="crash.our_fault ? '#E53935' : '#00C853'"
                                                >mdi-alert-outline</v-icon
                                            >
                                        </v-col>
                                        <v-col class="grow"> {{ crash.dateTime }}, {{ crash.address }} </v-col>
                                    </v-row>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content class="px-0">
                                    <v-card flat>
                                        <v-card-text>
                                            <v-row class="mb-4" align="center">
                                                <div class="subtitle-1">
                                                    {{ crash.our_fault ? "Вина водителя" : "Вина участника" }}
                                                </div>
                                                <div class="flex-grow-1"></div>
                                                <v-btn
                                                    color="error"
                                                    :loading="deleteLoading === crash.car_crash_id"
                                                    icon
                                                    @click="deleteCrash(crash.car_crash_id)"
                                                >
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on }">
                                                            <v-icon dark v-on="on">mdi-close</v-icon>
                                                        </template>
                                                        <span>Удалить это ДТП</span>
                                                    </v-tooltip>
                                                </v-btn>
                                            </v-row>
                                            <p>
                                                <span class="subtitle-2">Водитель:</span>
                                                <small v-if="crash.driver"
                                                    >{{ crash.driver.driver_info.name }}
                                                    {{ crash.driver.driver_info.patronymic }}
                                                    {{ crash.driver.driver_info.surname }}</small
                                                >
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Описание:</span>
                                                <small>{{ crash.description }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Информация об инспекторе:</span>
                                                <small v-if="crash.inspector_info">{{ crash.inspector_info }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Номер акт-а:</span>
                                                <small v-if="crash.act">{{ crash.act }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Сумма акт-а:</span>
                                                <small v-if="crash.act_sum">{{ crash.act_sum }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Информация об участнике:</span>
                                                <small v-if="crash.participant_info">{{
                                                    crash.participant_info
                                                }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Адрес:</span>
                                                <small v-if="crash.address">{{ crash.address }}</small>
                                            </p>
                                            <p>
                                                <span class="subtitle-2">Дата:</span>
                                                <small v-if="crash.dateTime">{{ crash.dateTime }}</small>
                                            </p>
                                            <div v-if="crash.images.length" class="subtitle-2 mb-2">Фотографии ДТП</div>
                                            <v-sheet v-if="crash.images.length">
                                                <v-slide-group v-model="activeImage" active-class="success" show-arrows>
                                                    <v-slide-item v-for="(image, i) in crash.images" :key="i">
                                                        <v-img
                                                            :src="image.name"
                                                            aspect-ratio="1"
                                                            class="grey lighten-2"
                                                            width="150"
                                                            height="100"
                                                        ></v-img>
                                                    </v-slide-item>
                                                </v-slide-group>
                                            </v-sheet>
                                        </v-card-text>
                                    </v-card>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                        <div v-else style="height: 100%" class="d-flex justify-center align-center subtitle-1"
                            >нет ДТП</div
                        >
                    </template>
                </v-card-text>
                <div style="height: 50px" class="d-flex justify-center align-center">
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
                </div>
            </v-tab-item>

            <!--      CRUD      -->
            <v-tab-item eager class="py-1">
                <v-card-text style="height: 600px; overflow-y: auto" class="py-4">
                    <v-form ref="crashForm">
                        <v-container>
                            <v-row>
                                <v-col md="6" xs="12">
                                    <v-text-field
                                        outlined
                                        dense
                                        id="suggest"
                                        append-icon="mdi-map-marker"
                                        name="Address"
                                        label="Адрес"
                                        v-model="crash.address"
                                        :error-messages="errors.collect('Address')"
                                        v-validate="crash.rules.address"
                                    ></v-text-field>
                                </v-col>
                                <v-col md="6" xs="12">
                                    <v-select
                                        outlined
                                        dense
                                        append-icon="mdi-account"
                                        :items="car.drivers"
                                        item-text="nickname"
                                        item-value="driver_id"
                                        label="Водитель"
                                        name="Driver"
                                        v-model="crash.driver_id"
                                        :error-messages="errors.collect('Driver')"
                                        v-validate="crash.rules.driver_id"
                                    >
                                        <template v-slot:selection="data">
                                            {{ data.item.driver_info.name }} {{ data.item.driver_info.patronymic }}
                                            {{ data.item.driver_info.surname }}
                                        </template>
                                        <template v-slot:item="data">
                                            {{ data.item.driver_info.name }} {{ data.item.driver_info.patronymic }}
                                            {{ data.item.driver_info.surname }}
                                        </template>
                                    </v-select>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col md="12" xs="12">
                                    <v-textarea
                                        outlined
                                        dense
                                        label="Описание"
                                        append-icon="mdi-clipboard-text"
                                        name="Description"
                                        v-model="crash.description"
                                        :error-messages="errors.collect('Description')"
                                        v-validate="crash.rules.description"
                                    >
                                    </v-textarea>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col md="6" xs="12">
                                    <date-picker
                                        v-model="crash.date"
                                        label="Дата ДТП"
                                        name="accident_date"
                                        :max="new Date().toJSON()"
                                        :error-messages="errors.collect('accident_date')"
                                        data-vv-as="дата ДТП"
                                        :rules="crash.rules.date"
                                    />
                                </v-col>
                                <v-col md="6" xs="12">
                                    <v-menu
                                        v-model="time_menu"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                outlined
                                                dense
                                                v-model="crash.time"
                                                label="Время ДТП"
                                                append-icon="mdi-clock-outline"
                                                v-on="on"
                                                name="Accident Time"
                                                :error-messages="errors.collect('Accident Time')"
                                                v-validate="crash.rules.time"
                                            />
                                        </template>
                                        <v-time-picker
                                            no-title
                                            format="24hr"
                                            v-model="crash.time"
                                            :allowed-minutes="allowedMinutes"
                                        />
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col md="6" xs="12">
                                    <v-textarea
                                        outlined
                                        dense
                                        append-icon="mdi-clipboard-text"
                                        name="Inspector Info"
                                        label="Информация об инспекторе"
                                        v-model="crash.inspector_info"
                                        :error-messages="errors.collect('Inspector Info')"
                                        v-validate="crash.rules.inspector_info"
                                    />
                                </v-col>
                                <v-col md="6" xs="12">
                                    <v-textarea
                                        outlined
                                        dense
                                        append-icon="mdi-clipboard-text"
                                        name="participant_info"
                                        label="Информация об участнике"
                                        v-model="crash.participant_info"
                                        :error-messages="errors.collect('Participant Info')"
                                        v-validate="crash.rules.participant_info"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col md="12" xs="12">
                                    <v-sheet class="mx-auto mb-6" max-width="750" v-if="crash.images">
                                        <v-slide-group v-model="activeImage" active-class="success" show-arrows>
                                            <v-slide-item
                                                v-for="(image, i) in crash.images"
                                                :key="i"
                                                v-slot:default="{ active, toggle }"
                                            >
                                                <v-img
                                                    :src="showImage(image)"
                                                    aspect-ratio="1"
                                                    class="grey lighten-2"
                                                    width="150"
                                                    height="100"
                                                />
                                            </v-slide-item>
                                        </v-slide-group>
                                    </v-sheet>
                                    <v-file-input
                                        outlined
                                        dense
                                        prepend-icon="mdi-camera"
                                        show-size
                                        counter
                                        multiple
                                        label="Фотографии ДТП"
                                        v-model="crash.images"
                                        name="Accident Photos"
                                        :error-messages="errors.collect('Accident Photos')"
                                        v-validate="crash.rules.images"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col xs="6" sm="6" md="6">
                                    <v-text-field
                                        outlined
                                        dense
                                        append-icon="mdi-numeric"
                                        name="Act"
                                        label="Номер акта"
                                        v-model="crash.act"
                                        :error-messages="errors.collect('Act')"
                                        v-validate="crash.rules.act"
                                    />
                                </v-col>
                                <v-col xs="6" sm="6" md="6">
                                    <v-text-field
                                        outlined
                                        dense
                                        type="text"
                                        append-icon="mdi-numeric"
                                        name="ActSum"
                                        label="Сумма"
                                        v-model="crash.act_sum"
                                        v-mask="'######.##'"
                                        :error-messages="errors.collect('ActSum')"
                                        v-validate="crash.rules.act_sum"
                                    />
                                </v-col>
                                <v-col xs="4" sm="4" md="4">
                                    <v-switch
                                        name="Our Fault"
                                        v-model="crash.our_fault"
                                        label="Вина водителя"
                                        :error-messages="errors.collect('Our Fault')"
                                        v-validate="crash.rules.our_fault"
                                    />
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-divider />
                <v-card-actions>
                    <v-spacer />
                    <v-btn :loading="createLoading" color="primary" @click="create()">сохранить ДТП</v-btn>
                </v-card-actions>
            </v-tab-item>
        </v-tabs-items>
    </v-card>
</template>

<script>
import Crash from "../../models/Crash";
import Snackbar from "../../facades/Snackbar";
import CarCrashPagination from "../../forms/CarCrashPagination";
import DatePicker from "../../../shared/components/form/DatePicker";

export default {
    components: { DatePicker },

    props: {
        car: {
            required: true,
        },
    },

    data() {
        return {
            tab: 0,

            selected: false,
            view: "crashList",
            crash: new Crash({
                car_id: this.car.car_id,
            }),
            date_menu: false,
            time_menu: false,
            activeImage: null,
            createLoading: false,
            deleteLoading: false,
            allowedMinutes: v => 10 <= v && 50 >= v,

            paginated: new CarCrashPagination(
                {
                    current_page: 1,
                    per_page: 10,
                },
                "traffic-safety/crashes/" + this.car.car_id,
            ),
        };
    },
    methods: {
        init() {
            let suggestView = new ymaps.SuggestView("suggest");
            suggestView.events.add("select", e => {
                this.crash.address = e.get("item").value;
            });
        },

        clear() {
            this.$emit("close");
        },
        showImage(file) {
            return URL.createObjectURL(file);
        },
        create() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.createLoading = true;
                    this.crash
                        .store()
                        .then(response => {
                            this.createLoading = false;
                            this.tab = 0;
                            this.paginated.getCrashes;
                            this.$emit("addCrash", this.car.car_id);
                            this.$refs.crashForm.reset();
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.createLoading = false;
                        });
                }
            });
        },

        deleteCrash(delId) {
            this.deleteLoading = delId;
            let crash = new Crash();
            crash
                .delete({ crash: delId })
                .then(response => {
                    this.deleteLoading = false;
                    this.paginated.getCrashes;
                    this.$emit("dellCrash", this.car.car_id);
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.deleteLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },
    watch: {
        tab: function () {
            if (1 === this.tab) {
                ymaps.ready(this.init);
            }
        },
        car: function () {
            if (this.car) {
                this.paginated = new CarCrashPagination(
                    {
                        current_page: 1,
                        per_page: 10,
                    },
                    "traffic-safety/crashes/" + this.car.car_id,
                );
                this.paginated.getCrashes;
                this.crash = new Crash({
                    car_id: this.car.car_id,
                });
            }

            this.tab = 0;
        },
        "crash.date": function () {
            this.crash.dateTime = this.crash.date + " " + this.crash.time + ":00";
        },
        "crash.time": function () {
            this.crash.dateTime = this.crash.date + " " + this.crash.time + ":00";
        },

        "paginated.current_page": function () {
            this.paginated.getCrashes;
        },
        "paginated.per_page": function () {
            this.paginated.getCrashes;
        },
    },
    created() {
        this.paginated.getCrashes;
    },
};
</script>
