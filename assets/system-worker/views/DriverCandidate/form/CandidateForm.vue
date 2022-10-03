<!-- @format -->

<template>
    <div>
        <!--Form-->
        <form autocomplete="off">
            <v-container fluid>
                <v-row>
                    <!--License info-->
                    <v-col md="6" sm="12" xs="12">
                        <v-subheader
                            class="font-weight-black grey lighten-4 justify-center"
                            v-text="'Водителское удостоверение'"
                        />
                        <v-divider class="mb-4 mt-0" />
                        <v-row>
                            <v-col md="4" xs="12">
                                <v-text-field
                                    v-model="driverInfo.license_code"
                                    v-validate="driverInfo.rules.license_code"
                                    :disabled="disabledLicense"
                                    :error-messages="errors.collect('license_code')"
                                    color="yellow darken-3"
                                    data-vv-as="номер удостоверения"
                                    dense
                                    label="Номер удостоверения"
                                    name="license_code"
                                    outlined
                                    type="number"
                                    @input="$emit('licenseInput')"
                                />
                                <v-select
                                    v-model="driverInfo.license_type_ids"
                                    v-validate="driverInfo.rules.license_type"
                                    :disabled="disabled"
                                    :error-messages="errors.collect('license_type')"
                                    :items="licenseTypes"
                                    color="yellow darken-3"
                                    data-vv-as="категории транспортных средств"
                                    dense
                                    item-text="type"
                                    item-value="driver_license_type_id"
                                    label="Категории транспортных средств"
                                    multiple
                                    name="license_type"
                                    outlined
                                />

                                <date-picker
                                    prepend-inner-icon="mdi-calendar"
                                    v-model="driverInfo.license_date"
                                    v-validate="driverInfo.rules.license_date"
                                    label="Дата начала контракта"
                                    name="license_date"
                                    :error-messages="errors.collect('license_date')"
                                    data-vv-as="дата начала контракта"
                                    @blur="date = parseDate(driverInfo.license_date)"
                                />

                                <date-picker
                                    prepend-inner-icon="mdi-calendar"
                                    v-model="driverInfo.license_expiry"
                                    v-validate="driverInfo.rules.license_expiry"
                                    label="Дата окончания срока действительности"
                                    name="license_expiry"
                                    :error-messages="errors.collect('license_expiry')"
                                    data-vv-as="дата окончания срока действительности"
                                    @blur="date = parseDate(driverInfo.license_expiry)"
                                />
                            </v-col>
                            <v-col md="4" xs="12">
                                <v-card>
                                    <v-img
                                        :src="driverInfo.license_qr_code ? driverInfo.license_qr_code : lazyImage"
                                        contain
                                        max-height="300"
                                        @click="
                                            driverInfo.license_qr_code
                                                ? showImgDialog(driverInfo.license_qr_code)
                                                : null
                                        "
                                    />
                                    <v-card-actions class="pa-0 ma-0 mb-0">
                                        <v-file-input
                                            v-model="driverInfo.license_qr_code_file"
                                            v-validate="driverInfo.rules.license_qr_code_file"
                                            :clearable="false"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('license_qr_code')"
                                            :prepend-icon="null"
                                            append-icon="mdi-camera"
                                            class="mb-0"
                                            color="yellow darken-3"
                                            data-vv-as="QR код удостоверения"
                                            dense
                                            label="QR код удостоверения"
                                            name="license_qr_code"
                                            type="file"
                                            @change="previewImage($event, 'license_qr_code')"
                                        />
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                            <v-col md="4" xs="12">
                                <v-card>
                                    <v-img
                                        :src="driverInfo.license_scan ? driverInfo.license_scan : lazyImage"
                                        contain
                                        max-height="300"
                                        @click="driverInfo.license_scan ? showImgDialog(driverInfo.license_scan) : null"
                                    >
                                    </v-img>
                                    <v-card-actions class="pa-0 ma-0 mb-0">
                                        <v-file-input
                                            v-model="driverInfo.license_scan_file"
                                            v-validate="driverInfo.rules.license_scan_file"
                                            :clearable="false"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('license_scan')"
                                            :prepend-icon="null"
                                            append-icon="mdi-camera"
                                            color="yellow darken-3"
                                            data-vv-as="Скан удостоверения"
                                            dense
                                            label="Скан удостоверения"
                                            name="license_scan_file"
                                            type="file"
                                            @change="previewImage($event, 'license_scan')"
                                        />
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                            <v-divider vertical />
                        </v-row>
                    </v-col>

                    <!--Personal info-->
                    <v-col md="6" sm="12" xs="12">
                        <v-subheader
                            class="font-weight-black grey lighten-4 justify-center"
                            v-text="'Персональная информация'"
                        />
                        <v-divider class="mb-4 mt-0" />
                        <v-row>
                            <v-col md="8" xs="12">
                                <v-row>
                                    <v-col md="6" xs="12">
                                        <v-text-field
                                            v-model="driverInfo.surname"
                                            v-validate="driverInfo.rules.surname"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('surname')"
                                            color="yellow darken-3"
                                            data-vv-as="фамилия"
                                            dense
                                            label="Фамилия"
                                            name="surname"
                                            outlined
                                        />
                                        <v-text-field
                                            v-model="driverInfo.name"
                                            v-validate="driverInfo.rules.name"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('name')"
                                            color="yellow darken-3"
                                            data-vv-as="имя"
                                            data-vv-validate-on="input"
                                            dense
                                            item-value="driverCandidate.name"
                                            label="Имя"
                                            name="name"
                                            outlined
                                        />
                                        <v-text-field
                                            v-model="driverInfo.patronymic"
                                            v-validate="driverInfo.rules.patronymic"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('patronymic')"
                                            color="yellow darken-3"
                                            data-vv-as="отчество"
                                            dense
                                            label="Отчество"
                                            name="patronymic"
                                            outlined
                                        />
                                        <v-text-field
                                            v-model="driverCandidate.phone"
                                            v-mask="phoneMask"
                                            v-validate="driverCandidate.rules.phone"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('phone')"
                                            append-icon="mdi-phone"
                                            color="yellow darken-3"
                                            data-vv-as="телефон"
                                            data-vv-validate-on="input"
                                            dense
                                            item-value="driverCandidate.phone"
                                            label="Телефон"
                                            name="phone"
                                            outlined
                                        />
                                    </v-col>
                                    <v-col md="6" xs="12">
                                        <v-text-field
                                            v-model="driverInfo.email"
                                            v-validate="driverInfo.rules.email"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('email')"
                                            color="yellow darken-3"
                                            data-vv-as="эл. адрес"
                                            dense
                                            label="Эл. адрес"
                                            name="email"
                                            outlined
                                        />

                                        <date-picker
                                            prepend-inner-icon="mdi-calendar"
                                            v-model="driverInfo.birthday"
                                            v-validate="driverInfo.rules.birthday"
                                            label="Дата рождения"
                                            name="birthday"
                                            :max="date_18_years_ago"
                                            :error-messages="errors.collect('birthday')"
                                            data-vv-as="дата тех. осмотра"
                                            @blur="date = parseDate(driverInfo.birthday)"
                                        />

                                        <v-text-field
                                            v-model="driverInfo.experience"
                                            v-validate="driverInfo.rules.experience"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('experience')"
                                            color="yellow darken-3"
                                            data-vv-as="опыт"
                                            data-vv-validate-on="input"
                                            dense
                                            label="Опыт в годах"
                                            name="experience"
                                            outlined
                                            type="number"
                                        />

                                        <v-text-field
                                            v-model="driverInfo.id_kis_art"
                                            v-validate="driverInfo.rules.id_kis_art"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('id_kis_art')"
                                            color="yellow darken-3"
                                            data-vv-as="ид кис арт"
                                            data-vv-validate-on="input"
                                            dense
                                            label="ИД КИС АРТ"
                                            name="id_kis_art"
                                            outlined
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col md="4" xs="12">
                                <v-card>
                                    <v-img
                                        :src="driverInfo.photo ? driverInfo.photo : lazyImage"
                                        contain
                                        max-height="300"
                                        @click="driverInfo.photo ? showImgDialog(driverInfo.photo) : null"
                                    >
                                    </v-img>
                                    <v-card-actions class="pa-0 ma-0 mb-0">
                                        <v-file-input
                                            v-model="driverInfo.photo_file"
                                            v-validate="update ? null : driverInfo.rules.photo_file"
                                            :value="driverInfo.photo"
                                            :clearable="false"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('photo_file')"
                                            :prepend-icon="null"
                                            append-icon="mdi-camera"
                                            class="ma-0 pa-0"
                                            color="yellow darken-3"
                                            data-vv-as="фотография"
                                            dense
                                            label="Фотография"
                                            name="photo_file"
                                            @change="previewImage($event, 'photo')"
                                        />
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>

                <v-row>
                    <!--Passport info-->
                    <v-col md="8" sm="12" xs="12">
                        <v-subheader
                            class="font-weight-black grey lighten-4 justify-center"
                            v-text="'Паспортные данные'"
                        />
                        <v-divider class="mb-4 mt-0" />
                        <v-row>
                            <v-col md="9" xs="12">
                                <v-row>
                                    <v-col md="6" xs="12">
                                        <v-text-field
                                            v-model="driverInfo.citizen"
                                            v-validate="driverInfo.rules.citizen"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('citizen')"
                                            color="yellow darken-3"
                                            data-vv-as="гражданство"
                                            dense
                                            label="Гражданство"
                                            name="citizen"
                                            outlined
                                        />
                                        <v-text-field
                                            v-model="driverInfo.passport_serial"
                                            v-validate="driverInfo.rules.passport_serial"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('passport_serial')"
                                            color="yellow darken-3"
                                            data-vv-as="серия"
                                            dense
                                            label="Серия"
                                            name="passport_serial"
                                            outlined
                                        />
                                        <v-text-field
                                            v-model="driverInfo.passport_number"
                                            v-validate="driverInfo.rules.passport_number"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('passport_number')"
                                            color="yellow darken-3"
                                            data-vv-as="номер"
                                            dense
                                            label="Номер"
                                            name="passport_number"
                                            outlined
                                        />
                                    </v-col>
                                    <v-col md="6" xs="12">
                                        <v-text-field
                                            v-model="driverInfo.passport_issued_by"
                                            v-validate="driverInfo.rules.passport_issued_by"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('passport_issued_by')"
                                            color="yellow darken-3"
                                            data-vv-as="кем выдан"
                                            dense
                                            label="Кем выдан"
                                            name="passport_issued_by"
                                            outlined
                                        />

                                        <date-picker
                                            prepend-inner-icon="mdi-calendar"
                                            v-model="driverInfo.passport_when_issued"
                                            v-validate="driverInfo.rules.passport_when_issued"
                                            label="Дата выдачи"
                                            name="passport_when_issued"
                                            :error-messages="errors.collect('passport_when_issued')"
                                            data-vv-as="дата выдачи"
                                            @blur="date = parseDate(driverInfo.passport_when_issued)"
                                        />

                                        <v-text-field
                                            v-model="driverInfo.address"
                                            v-validate="driverInfo.rules.address"
                                            :disabled="disabled"
                                            :error-messages="errors.collect('address')"
                                            color="yellow darken-3"
                                            data-vv-as="адрес"
                                            dense
                                            label="Адрес"
                                            name="address"
                                            outlined
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>

                            <v-col md="3" xs="12">
                                <multi-image
                                    :disabled="disabled"
                                    :images="driverInfo.passport_scan"
                                    :error-first-message="errors.first('passport_scan')"
                                    :input-rules="driverInfo.rules.passport_scan"
                                    label="Скан пасспорта"
                                    input-name="passport_scan"
                                    @change="driverInfo.passport_scan = $event"
                                    @remove="driverInfo.passport_scan.splice($event.key, 1)"
                                >
                                    <v-btn
                                        v-if="update && driverInfo.passport_scan_orig && !driverInfo.passport_scan.length"
                                        @click="driverInfo.downloadPspScan(driverInfo.driver_info_id)"
                                    >Скачать PDF</v-btn>
                                </multi-image>
                            </v-col>

                            <v-divider vertical />
                        </v-row>
                    </v-col>

                    <!--Study info-->
                    <v-col md="4" sm="12" xs="12">
                        <v-subheader class="font-weight-black grey lighten-4 justify-center" v-text="'Обучение'" />
                        <v-divider class="mb-4 mt-0" />
                        <v-select
                            v-model="driverCandidate.tutor_id"
                            v-validate="driverCandidate.rules.tutor_id"
                            :disabled="disabled"
                            :error-messages="errors.collect('tutor')"
                            :items="tutors"
                            :persistent-hint="true"
                            color="yellow darken-3"
                            data-vv-as="репетитор"
                            dense
                            item-text="name"
                            item-value="system_worker_id"
                            label="Репетитор"
                            name="tutor"
                            outlined
                        >
                            <template v-slot:selection="data">
                                {{ data.item.name }} {{ data.item.patronymic }} {{ data.item.surname }}
                            </template>
                            <template v-slot:item="data">
                                {{ data.item.name }} {{ data.item.patronymic }} {{ data.item.surname }}
                            </template>
                        </v-select>
                        <v-select
                            v-model="driverCandidate.learn_status_id"
                            v-validate="driverCandidate.rules.learn_status"
                            :disabled="disabled"
                            :error-messages="errors.collect('learn_status')"
                            :items="learnStatuses"
                            color="yellow darken-3"
                            data-vv-as="стадия обучения"
                            dense
                            item-text="name"
                            item-value="learn_status_id"
                            label="Стадия обучения"
                            name="learn_status"
                            outlined
                        />

                        <date-picker
                            prepend-inner-icon="mdi-calendar"
                            v-model="driverCandidate.learn_start"
                            v-validate="driverCandidate.rules.learn_start"
                            label="Дата начала обучения"
                            name="learn_start"
                            :error-messages="errors.collect('learn_start')"
                            data-vv-as="дата начала обучения"
                            @blur="date = parseDate(driverCandidate.learn_start)"
                        />

                        <date-picker
                            prepend-inner-icon="mdi-calendar"
                            v-model="driverCandidate.learn_end"
                            v-validate="driverCandidate.rules.learn_end"
                            label="Дата окончания обучения"
                            name="learn_end"
                            :error-messages="errors.collect('learn_end')"
                            data-vv-as="дата окончания обучения"
                            @blur="date = parseDate(driverCandidate.learn_end)"
                        />
                    </v-col>
                </v-row>
            </v-container>
            <v-divider class="mb-4 mt-0" />
            <div class="d-flex justify-end">
                <v-btn :loading="loading" class="yellow darken-3" tile depressed @click="submit()">
                    {{ btnTitle }}
                </v-btn>
            </div>
        </form>

        <!--Image dialog-->
        <v-dialog v-model="imgDialog" max-width="600" width="100%">
            <v-card v-if="dialogImgSrc">
                <v-btn absolute class="mt-3" color="error" dark fab right small @click="imgDialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-img :src="dialogImgSrc" />
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="js" src="./CandidateForm.main.js"></script>
