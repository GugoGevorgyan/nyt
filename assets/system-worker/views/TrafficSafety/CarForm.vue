<!-- @format -->

<template>
    <v-card outlined tile elevation="4">
        <v-card-title ref="title" class="grey lighten-5">
            {{ trafficsSafety.car_id ? "Обновление данных автомобиля" : "Регистрация нового автомобиля" }}
        </v-card-title>
        <v-divider />
        <v-card-text :style="{ height: height + 'px' }" style="overflow-y: auto">
            <v-form autocomplete="off">
                <v-layout>
                    <v-flex lg6 md6 sm12 xl6 xs12>
                        <v-container fluid>
                            <v-row>
                                <v-col md="6" sm="12" xs="12">
                                    <v-text-field
                                        outlined
                                        dense
                                        label="VIN код"
                                        data-vv-as="VIN код"
                                        prepend-inner-icon="mdi-numeric"
                                        name="vin_code"
                                        v-model="trafficsSafety.vin_code"
                                        :error-messages="errors.collect('vin_code')"
                                        v-validate="trafficsSafety.rules.vin_code"
                                    />
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Марка"
                                        data-vv-as="марка"
                                        prepend-inner-icon="mdi-bookmark"
                                        name="mark"
                                        v-model="trafficsSafety.mark"
                                        :error-messages="errors.collect('mark')"
                                        v-validate="trafficsSafety.rules.mark"
                                    />
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Лицензионный номер"
                                        data-vv-as="лицензионный номер"
                                        prepend-inner-icon="mdi-numeric"
                                        name="vehicle_licence_number"
                                        v-model="trafficsSafety.vehicle_licence_number"
                                        :error-messages="errors.collect('vehicle_licence_number')"
                                        v-validate="trafficsSafety.rules.vehicle_licence_number"
                                    />

                                    <multi-image
                                        :images="trafficsSafety.images.length ? trafficsSafety.images : trafficsSafety.images_orig"
                                        :error-first-message="errors.first('images')"
                                        :input-rules="trafficsSafety.rules.images"
                                        label="Изображения"
                                        input-name="images"
                                        @change="trafficsSafety.images = $event"
                                        @remove="trafficsSafety.images.splice($event.key, 1)"
                                    />
                                </v-col>
                                <v-col md="6" sm="12" xs="12">
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Номер кузова"
                                        data-vv-as="номер кузова"
                                        prepend-inner-icon="mdi-numeric"
                                        name="body_number"
                                        v-model="trafficsSafety.body_number"
                                        :error-messages="errors.collect('body_number')"
                                        v-validate="trafficsSafety.rules.body_number"
                                    />
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Модель"
                                        data-vv-as="модель"
                                        prepend-inner-icon="mdi-car-sports"
                                        name="model"
                                        v-model="trafficsSafety.model"
                                        :error-messages="errors.collect('model')"
                                        v-validate="trafficsSafety.rules.model"
                                    />
                                    <date-picker
                                        v-model="trafficsSafety.vehicle_licence_date"
                                        label="Дата получения лицензии"
                                        name="vehicle_licence_date"
                                        :max="yesterday()"
                                        :error-messages="errors.collect('vehicle_licence_date')"
                                        data-vv-as="дата получения лицензии"
                                        v-validate="trafficsSafety.rules.vehicle_licence_date"
                                    />
                                    <date-picker
                                        v-model="trafficsSafety.registration_date"
                                        label="Дата регистрации"
                                        name="registration_date"
                                        :max="yesterday()"
                                        :error-messages="errors.collect('registration_date')"
                                        data-vv-as="дата регистрации"
                                        v-validate="trafficsSafety.rules.registration_date"
                                    />
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-flex>
                    <v-flex lg6 md6 sm12 xl6 xs12>
                        <v-container fluid>
                            <v-row>
                                <v-col md="6" sm="12" xs="12">
                                    <v-select
                                        outlined
                                        dense
                                        label="Классы"
                                        data-vv-as="классы"
                                        multiple
                                        prepend-inner-icon="mdi-star"
                                        :items="classes"
                                        item-value="car_class_id"
                                        item-text="class_name"
                                        name="class_ids"
                                        v-model="trafficsSafety.class_ids"
                                        :error-messages="errors.collect('class_ids')"
                                        v-validate="trafficsSafety.rules.class_ids"
                                    />
                                    <v-select
                                        outlined
                                        dense
                                        label="Статус"
                                        data-vv-as="статус"
                                        prepend-inner-icon="mdi-flag"
                                        :items="statuses"
                                        item-text="text"
                                        item-value="car_status_id"
                                        name="status_id"
                                        v-model="trafficsSafety.status_id"
                                        :error-messages="errors.collect('status_id')"
                                        v-validate="trafficsSafety.rules.status_id"
                                    />
                                    <div class="mb-6">
                                        <el-date-picker
                                            class="mb-1"
                                            size="large"
                                            style="width: 100%; border-radius: 3px"
                                            v-model="year"
                                            :picker-options="yearPickerOptions"
                                            label="Дата производства"
                                            placeholder="Дата производства"
                                            ref="yearPicker"
                                            name="year"
                                            format="yyyy"
                                            :error-messages="errors.collect('year')"
                                            data-vv-as="дата производства"
                                            v-validate="trafficsSafety.rules.year"
                                            type="year"
                                        />

                                        <div
                                            v-if="errors.first('year')"
                                            class="v-text-field__details pl-3 pr-3"
                                        >
                                            <div class="v-messages theme--light error--text" role="alert">
                                                <div class="v-messages__wrapper">
                                                    <div
                                                        v-text="errors.first('year')"
                                                        class="v-messages__message"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <v-autocomplete
                                            prepend-inner-icon="mdi-gavel"
                                            :items="entities"
                                            clearable
                                            data-vv-as="юридическое лицо"
                                            color="yellow darken-3"
                                            item-color="yellow darken-3"
                                            item-text="name"
                                            item-value="legal_entity_id"
                                            label="Юридическое лицо"
                                            open-on-clear
                                            name="entity_id"
                                            :error-messages="errors.collect('entity_id')"
                                            v-model="trafficsSafety.entity_id"
                                            v-validate="trafficsSafety.rules.entity_id"
                                            outlined
                                            dense
                                        />
                                        <v-btn
                                            class="mx-2"
                                            fab
                                            small
                                            color="primary"
                                            target="_blank"
                                            :href="
                                                $router.resolve({
                                                    name: 'legal_entity_create',
                                                }).href
                                            "
                                        >
                                            <v-icon v-text="'mdi-plus'" />
                                        </v-btn>
                                    </div>
                                </v-col>
                                <v-col md="6" sm="12" xs="12">
                                    <v-select
                                        outlined
                                        dense
                                        label="Цвет"
                                        data-vv-as="цвет"
                                        prepend-inner-icon="mdi-palette"
                                        name="color"
                                        :items="colors"
                                        v-model="trafficsSafety.color"
                                        :error-messages="errors.collect('color')"
                                        v-validate="trafficsSafety.rules.color"
                                    >
                                        <template v-slot:item="{ item }">
                                            <div
                                                :style="{ 'background-color': item }"
                                                class="elevation-3 mr-2"
                                                style="height: 10px; width: 10px; border-radius: 50%"
                                            />
                                            {{ item }}
                                        </template>
                                        <template v-slot:selection="{ item }">
                                            <div
                                                :style="{ 'background-color': item }"
                                                class="elevation-3 mr-2"
                                                style="height: 10px; width: 10px; border-radius: 50%"
                                            />
                                            {{ item }}
                                        </template>
                                    </v-select>
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Государственный номерной знак"
                                        data-vv-as="государственный номерной знак"
                                        prepend-inner-icon="mdi-numeric"
                                        name="state_license_plate"
                                        v-model="trafficsSafety.state_license_plate"
                                        :error-messages="errors.collect('state_license_plate')"
                                        v-validate="trafficsSafety.rules.state_license_plate"
                                    />
                                    <v-text-field
                                        outlined
                                        dense
                                        label="Пробег"
                                        data-vv-as="пробег"
                                        prepend-inner-icon="mdi-numeric"
                                        name="speedometer"
                                        type="number"
                                        v-model="trafficsSafety.speedometer"
                                        :error-messages="errors.collect('speedometer')"
                                        v-validate="trafficsSafety.rules.speedometer"
                                    />
                                    <v-text-field
                                        outlined
                                        dense
                                        type="number"
                                        label="Гаражный номер"
                                        data-vv-as="гаражный номер"
                                        prepend-inner-icon="mdi-numeric"
                                        name="garage_number"
                                        v-model="trafficsSafety.garage_number"
                                        :error-messages="errors.collect('garage_number')"
                                        v-validate="trafficsSafety.rules.garage_number"
                                    />
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-flex>
                    <v-divider />
                </v-layout>
                <v-row>
                    <v-col cols="12" md="3">
                        <v-subheader
                            v-text="'Технический осмотр'"
                            class="font-weight-black justify-center grey lighten-3"
                        />
                        <v-divider class="mb-2" />
                        <v-row>
                            <v-col cols="12" md="12">
                                <date-picker
                                    v-model="trafficsSafety.inspection_date"
                                    label="Дата тех. осмотра"
                                    name="inspection_date"
                                    :max="prevDay(trafficsSafety.inspection_expiration_date)"
                                    :error-messages="errors.collect('inspection_date')"
                                    data-vv-as="дата тех. осмотра"
                                    v-validate="trafficsSafety.rules.inspection_date"
                                />
                                <date-picker
                                    v-model="trafficsSafety.inspection_expiration_date"
                                    label="Дата истечения тех. осмотра"
                                    name="inspection_expiration_date"
                                    :min="nextDay(trafficsSafety.inspection_date)"
                                    :error-messages="errors.collect('inspection_expiration_date')"
                                    data-vv-as="дата истечения тех. осмотра"
                                    v-validate="trafficsSafety.rules.inspection_expiration_date"
                                />
                            </v-col>
                            <v-row class="ml-1">
                                <v-col cols="12" md="8">
                                    <v-card>
                                        <v-img
                                            :style="{ cursor: trafficsSafety.inspection_scan ? 'pointer' : 'auto' }"
                                            contain
                                            :src="
                                                trafficsSafety.inspection_scan
                                                    ? trafficsSafety.inspection_scan
                                                    : lazyImage
                                            "
                                            max-height="350"
                                            @click="
                                                trafficsSafety.inspection_scan
                                                    ? showImgDialog(trafficsSafety.inspection_scan)
                                                    : null
                                            "
                                        />
                                        <v-card-actions>
                                            <v-file-input
                                                @change="previewImage($event, 'inspection_scan')"
                                                dense
                                                label="Скан документа тех. осмотра"
                                                data-vv-as="скан документа"
                                                name="inspection_scan_file"
                                                v-model="trafficsSafety.inspection_scan_file"
                                                :prepend-icon="null"
                                                prepend-inner-icon="mdi-file-document-box"
                                                :error-messages="errors.collect('inspection_scan_file')"
                                                v-validate="!carObj ? trafficsSafety.rules.inspection_scan_file : null"
                                            />
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-row>
                    </v-col>
                    <v-col cols="12" md="3">
                        <v-subheader v-text="'Страхование'" class="font-weight-black justify-center grey lighten-3" />
                        <v-divider class="mb-2" />
                        <v-row>
                            <v-col cols="12" md="12">
                                <date-picker
                                    v-model="trafficsSafety.insurance_date"
                                    label="Дата страхования"
                                    name="insurance_date"
                                    :max="prevDay(trafficsSafety.insurance_expiration_date)"
                                    :error-messages="errors.collect('insurance_date')"
                                    data-vv-as="дата страхования"
                                    v-validate="trafficsSafety.rules.insurance_date"
                                />
                                <date-picker
                                    v-model="trafficsSafety.insurance_expiration_date"
                                    label="Дата истечения страхования"
                                    name="insurance_expiration_date"
                                    :min="nextDay(trafficsSafety.insurance_date)"
                                    :error-messages="errors.collect('insurance_expiration_date')"
                                    data-vv-as="дата истечения страхования"
                                    v-validate="trafficsSafety.rules.insurance_expiration_date"
                                />
                            </v-col>
                            <v-row class="ml-2">
                                <v-col cols="12" md="8">
                                    <v-card>
                                        <v-img
                                            :style="{ cursor: trafficsSafety.insurance_scan ? 'pointer' : 'auto' }"
                                            contain
                                            :src="
                                                trafficsSafety.insurance_scan
                                                    ? trafficsSafety.insurance_scan
                                                    : lazyImage
                                            "
                                            max-height="350"
                                            @click="
                                                trafficsSafety.insurance_scan
                                                    ? showImgDialog(trafficsSafety.insurance_scan)
                                                    : null
                                            "
                                        />
                                        <v-card-actions>
                                            <v-file-input
                                                @change="previewImage($event, 'insurance_scan')"
                                                dense
                                                label="Скан документа страхования"
                                                data-vv-as="скан документа"
                                                name="insurance_scan_file"
                                                v-model="trafficsSafety.insurance_scan_file"
                                                :prepend-icon="null"
                                                prepend-inner-icon="mdi-file-document-box"
                                                :error-messages="errors.collect('insurance_scan_file')"
                                                v-validate="!carObj ? trafficsSafety.rules.insurance_scan_file : null"
                                            />
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-row>
                    </v-col>
                    <v-divider vertical inset />
                    <v-col cols="12" md="3">
                        <v-subheader v-text="'ПТС и снимки'" class="font-weight-black justify-center grey lighten-3" />
                        <v-divider class="mb-2" />
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-text-field
                                    outlined
                                    dense
                                    label="Номер ПТС"
                                    data-vv-as="Номер ПТС"
                                    prepend-inner-icon="mdi-numeric"
                                    name="pts_number"
                                    color="yellow darken-3"
                                    v-model="trafficsSafety.pts_number"
                                    :error-messages="errors.collect('pts_number')"
                                    v-validate="trafficsSafety.rules.pts_number"
                                />

                                <multi-image
                                    :images="trafficsSafety.pts_file"
                                    :error-first-message="errors.first('pts_file')"
                                    :input-rules="trafficsSafety.rules.pts_file"
                                    label="сканы ПТС"
                                    input-name="pts_file"
                                    @change="trafficsSafety.pts_file = $event"
                                    @remove="trafficsSafety.pts_file.splice($event.key, 1)"
                                >
                                    <v-btn
                                        v-if="update && trafficsSafety.pts_file_orig && !trafficsSafety.pts_file.length"
                                        @click="trafficsSafety.downloadScan(carObj.car_id, 'pts')"
                                    >Скачать PDF</v-btn>
                                </multi-image>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" md="3" sm="4" lg="3" xl="3">
                        <v-subheader v-text="'СТС и снимки '" class="font-weight-black justify-center grey lighten-3" />
                        <v-divider class="mb-2" />
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-text-field
                                    outlined
                                    dense
                                    label="Номер СТС"
                                    data-vv-as="Номер СТС"
                                    prepend-inner-icon="mdi-numeric"
                                    name="sts_number"
                                    color="yellow darken-3"
                                    v-model="trafficsSafety.sts_number"
                                    :error-messages="errors.collect('sts_number')"
                                    v-validate="trafficsSafety.rules.sts_number"
                                />

                                <multi-image
                                    :images="trafficsSafety.sts_file"
                                    :error-first-message="errors.first('sts_file')"
                                    :input-rules="trafficsSafety.rules.sts_file"
                                    label="сканы СТС"
                                    input-name="sts_file"
                                    @change="trafficsSafety.sts_file = $event"
                                    @remove="trafficsSafety.sts_file.splice($event.key, 1)"
                                >
                                    <v-btn
                                        v-if="update && trafficsSafety.sts_file_orig && !trafficsSafety.sts_file.length"
                                        @click="trafficsSafety.downloadScan(carObj.car_id, 'sts')"
                                    >Скачать PDF</v-btn>
                                </multi-image>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
        <v-divider />
        <v-card-actions ref="actions">
            <v-spacer />
            <v-btn :loading="loading" color="primary" @click="submit()">
                {{ trafficsSafety.car_id ? "обновить" : "сохранить" }}
            </v-btn>
        </v-card-actions>

        <!--Image dialog-->
        <v-dialog v-model="imgDialog" max-width="600" width="100%">
            <v-card v-if="dialogImgSrc">
                <v-btn absolute dark fab right small color="error" @click="imgDialog = false" class="mt-3">
                    <v-icon v-text="'mdi-close'" />
                </v-btn>
                <v-img :src="dialogImgSrc" />
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script lang="js" src="./CarForm.main.js" />
