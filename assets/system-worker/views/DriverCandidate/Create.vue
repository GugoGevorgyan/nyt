<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <!--Driver info-->
        <v-card v-if="driver" class="mb-3 top-styling" tile elevation="4">
            <v-card-title class="grey lighten-5">Информация водителя</v-card-title>
            <v-divider />
            <v-card-text>
                <v-row>
                    <v-col md="4" sm="12" xs="12">
                        <v-subheader>Профиль</v-subheader>
                        <v-divider />
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                    <tr>
                                        <td>Никнейм</td>
                                        <td>{{ driver.nickname }}</td>
                                    </tr>
                                    <tr>
                                        <td>Телефон</td>
                                        <td>{{ driver.phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Эл. адрес</td>
                                        <td>{{ driver.email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Тип</td>
                                        <td>{{ driver.type.type }}</td>
                                    </tr>
                                    <tr>
                                        <td>Очки</td>
                                        <td>{{ driver.point }}</td>
                                    </tr>
                                    <tr>
                                        <td>Борт</td>
                                        <td>
                                            <v-chip
                                                @click="showCarDialog(driver.car)"
                                                v-if="driver.car"
                                                color="orange"
                                                small
                                                outlined
                                                >{{ driver.car.garage_number }}</v-chip
                                            >
                                            <v-chip v-else color="error" small outlined>No car</v-chip>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Зарегистрирован</td>
                                        <td>{{ dateFormat(driver.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                    <v-col md="8" sm="12" xs="12">
                        <v-subheader>Контракты</v-subheader>
                        <v-divider />
                        <v-data-table
                            height="300"
                            :headers="driverInfoHeaders"
                            :items="driver.contracts"
                            :items-per-page="5"
                        >
                            <template v-slot:item.car="{ item }">
                                <v-chip @click="showCarDialog(item.car)" color="orange" small outlined>{{
                                    item.car.garage_number
                                }}</v-chip>
                            </template>
                            <template v-slot:item.active="{ item }">
                                <v-icon v-if="item.active" color="success">mdi-check</v-icon>
                                <v-icon v-else color="error">mdi-close</v-icon>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!--Candidate form-->
        <v-card>
            <v-card-title>
                {{ title }}
                <v-btn class="mx-2" v-if="approveLicense" @click="clearLicenseValues()" color="error">Сбросить</v-btn>
            </v-card-title>
            <v-divider class="mb-4 mt-0" />
            <v-card-text class="pt-0">
                <candidate-form
                    :driver-candidate="driverCandidate"
                    :driver-info="driverInfo"
                    :learn-statuses="learnStatuses"
                    :tutors="tutors"
                    :license-types="licenseTypes"
                    :update="false"
                    :disabled-license="disabledLicense"
                    :disabled="disabled"
                    :url="url"
                    :btn-title="btnTitle"
                    :loading="loading"
                    @licenseInput="licenseInput()"
                    @submit="
                        save(
                            approveLicense ? 'update/' + checkCandidate.driver_candidate_id : 'store',
                            checkCandidate ? 'put' : 'post',
                        )
                    "
                />
            </v-card-text>
        </v-card>

        <!--License dialog-->
        <v-dialog v-model="licenseDialog" max-width="400" width="100%">
            <v-card v-if="checkCandidate">
                <v-card-title>Кандидат в водители: {{ checkCandidate.info.license_code }}</v-card-title>
                <v-card-text>
                    <v-alert outlined type="info">
                        Кандидат в водители с номером водителского удостоверения
                        <strong>{{ checkCandidate.info.license_code }}</strong> уже был зарегистрирован в системе
                    </v-alert>
                    <v-alert outlined v-if="checkCandidate.deleted_at" type="error">
                        Удален <strong>{{ dateFormat(checkCandidate.deleted_at) }}</strong>
                    </v-alert>
                </v-card-text>
                <v-card-actions class="d-flex justify-end">
                    <v-btn text color="error" @click="closeLicenseDialog()">Отмена</v-btn>
                    <v-btn text color="primary" @click="setCandidateValues()">
                        {{ checkCandidate.deleted_at ? "Восстановить и обновить" : "Обновить" }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--Show car dialog-->
        <v-dialog v-model="carDialog" max-width="400" width="100%">
            <v-card v-if="showCar">
                <v-card-title class="justify-space-between">
                    Гаражный номер автомобиля: {{ showCar.garage_number }}
                    <v-btn icon color="error" @click="carDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content>Парк:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.park.name }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Гражный номер:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.garage_number }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Класс:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.car_class.class_name
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Модель:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.model }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Марка:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.mark }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Федеральный номер:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.state_license_plate
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>VIN код:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.vin_code }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Год выпуска:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.year }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Пробег:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.speedometer }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Статус:</v-list-item-content>
                            <v-list-item-content class="d-block">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip :color="showCar.status.color" small outlined>
                                            {{ showCar.status.text }}
                                        </v-chip>
                                    </template>
                                    <span>{{ showCar.status.description }}</span>
                                </v-tooltip>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Дата техосмотора:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.inspection_date }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Дата окончания техосмотора:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.inspection_expiration_date
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Дата страхования:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.insurance_date }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Дата окончания страхования:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.insurance_expiration_date
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Зарегистрирован:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.created_at }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import Candidate from "./../../models/Candidate";
import Snackbar from "./../../facades/Snackbar";
import DriverInfo from "./../../models/DriverInfo";
import MultiModel from "./../../base/MultiModel";
import CandidateForm from "./form/CandidateForm";
import axios from "axios";

export default {
    name: "Create",
    components: { CandidateForm },
    props: {
        tutors: {
            required: true,
            Array: true,
        },
        licenseTypes: {
            required: true,
        },
        learnStatuses: {
            required: true,
            Array: true,
        },
    },

    data: () => ({
        driverCandidate: new Candidate({
            learn_status: "waiting",
        }),
        driverInfo: new DriverInfo(),
        driver: undefined,
        form: Object,
        loading: false,

        approveLicense: false,
        disabled: true,
        disabledLicense: false,
        licenseDialog: false,
        checkCandidate: undefined,

        title: "Новый кандидат",
        btnTitle: "Создать",
        carDialog: false,
        showCar: undefined,
        driverInfoHeaders: [
            { text: "Тип", value: "type.type" },
            { text: "Расписание", value: "graphic.name" },
            { text: "Борт", value: "car" },
            { text: "Дата подписания контракта", value: "signing_day" },
            { text: "Дата окончания контракта", value: "expiration_day" },
            { text: "Длителность", value: "duration" },
            { text: "Активен", value: "active" },
        ],
    }),
    computed: {
        url() {
            return this.$store.state.initUrl;
        },
    },

    methods: {
        dateFormat(date) {
            return date ? new Date(date).toISOString().slice(0, 10) : "";
        },

        showCarDialog(car) {
            this.showCar = car;
            this.carDialog = true;
        },

        licenseInput() {
            if (
                this.driverInfo.license_code &&
                5 < this.driverInfo.license_code.length &&
                17 > this.driverInfo.license_code.length
            ) {
                this.checkDriverLicense(this.driverInfo.license_code);
            } else {
                this.disabled = true;
            }
        },

        checkDriverLicense(license) {
            let formData = new FormData();
            formData.append("license", license);
            axios
                .post("check-license", formData)
                .then(response => {
                    if (response.data.candidate) {
                        this.checkCandidate = { ...response.data.candidate };
                        this.licenseDialog = true;
                    } else {
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },

        setDriverLicenseTypeIds(info) {
            info.license_types.forEach(item => {
                this.driverInfo.license_type_ids.push(item.driver_license_type_id);
            });
        },

        setCandidateValues() {
            this.driverInfo = new DriverInfo({ ...this.checkCandidate.info });
            this.driverInfo.birthday = this.dateFormat(this.driverInfo.birthday);
            this.setDriverLicenseTypeIds(this.checkCandidate.info);
            this.driver = this.checkCandidate.driver;
            this.driverCandidate = new Candidate({ ...this.checkCandidate });
            this.licenseDialog = false;
            this.disabled = false;
            this.disabledLicense = true;
            this.approveLicense = true;
            this.title = this.driverCandidate.deleted_at
                ? "Восстановление и обновление информации кандидата: " + this.checkCandidate.info.license_code
                : "Обновитление информации кандидата: " + this.checkCandidate.info.license_code;
            this.btnTitle = this.driverCandidate.deleted_at ? "Восстановить и обновить" : "Обновить";
        },

        closeLicenseDialog() {
            this.licenseDialog = false;
            this.clearLicenseValues();
        },

        clearLicenseValues() {
            this.disabled = true;
            this.checkCandidate = undefined;
            this.driverInfo = new DriverInfo();
            this.driverCandidate = new Candidate();
            this.title = "Создать нового кандидата";
            this.approveLicense = false;
            this.disabledLicense = false;
            this.driver = undefined;
            this.btnTitle = "Создать";
        },

        save(route, method = "post") {
            this.loading = true;
            this.form = new MultiModel([this.driverCandidate, this.driverInfo], true);
            this.form
                .send(this.url + "driver-candidates/" + route, method)
                .then(response => {
                    this.loading = false;
                    Snackbar.info(response.data.message);
                    window.location = this.url + "driver-candidates";
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                    Candidate.errors(error.response).forEach(error => this.errors.add(error));
                    DriverInfo.errors(error.response).forEach(error => this.errors.add(error));
                });
        },
    },
};
</script>
