<!-- @format -->

<template>
    <v-card class="overflow-hidden" :loading="loading">
        <v-card-title class="elevation-10 subtitle-1">
            Информация тех. осмотра
            <small
                v-if="inspection"
                class="mx-2"
                :style="{ color: inspection.inspection_days_left < 30 ? '#E53935' : '#00C853' }"
            >
                {{
                    inspection.inspection_days_left > 0 ? "осталось дней: " + inspection.inspection_days_left : "истек"
                }}
            </small>
            <v-spacer />
            <template>
                <v-btn v-if="disabled" icon @click="disabled = !disabled">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-icon color="primary" dark v-on="on">mdi-pencil</v-icon>
                        </template>
                        <span>Редактировать информацию</span>
                    </v-tooltip>
                </v-btn>
                <v-btn v-else icon @click="save()" :loading="loading">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-icon color="primary" dark v-on="on">mdi-content-save</v-icon>
                        </template>
                        <span>Сохранить изменения</span>
                    </v-tooltip>
                </v-btn>
            </template>
            <v-btn icon @click="clear()" :disabled="loading">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon color="error" dark v-on="on">mdi-close</v-icon>
                    </template>
                    <span>{{ disabled ? "Закрыть" : "Отменть изменения и закрыть" }}</span>
                </v-tooltip>
            </v-btn>
        </v-card-title>
        <v-card-text>
            <v-container fluid>
                <v-row>
                    <v-col cols="12" md="7">
                        <v-subheader class="pa-0">
                            Дата:
                            <strong class="mx-2" v-if="inspection">
                                с {{ inspection.inspection_date }} по {{ inspection.inspection_expiration_date }}
                            </strong>
                        </v-subheader>
                        <v-divider class="mb-6" />
                        <date-picker
                            :disabled="disabled"
                            v-model="trafficsSafety.inspection_date"
                            label="Дата тех. осмотра"
                            name="inspection_date"
                            :max="prevDay(trafficsSafety.inspection_expiration_date)"
                            :error-messages="errors.collect('inspection_date')"
                            data-vv-as="дата тех. осмотра"
                            v-validate="trafficsSafety.rules.inspection_date"
                        />
                        <date-picker
                            :disabled="disabled"
                            v-model="trafficsSafety.inspection_expiration_date"
                            label="Дата истечения тех. осмотра"
                            name="inspection_expiration_date"
                            :min="nextDay(trafficsSafety.inspection_date)"
                            :error-messages="errors.collect('inspection_expiration_date')"
                            data-vv-as="дата истечения тех. осмотра"
                            v-validate="trafficsSafety.rules.inspection_expiration_date"
                        />
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-subheader class="pa-0">Скан документа</v-subheader>
                        <v-divider class="mb-6" />
                        <v-img
                            :style="{ cursor: trafficsSafety.inspection_scan ? 'pointer' : 'auto' }"
                            contain
                            :src="trafficsSafety.inspection_scan ? trafficsSafety.inspection_scan : lazyImage"
                            max-height="350"
                            @click="
                                trafficsSafety.inspection_scan ? showImgDialog(trafficsSafety.inspection_scan) : null
                            "
                            class="mb-4"
                        >
                        </v-img>
                        <v-file-input
                            :disabled="disabled"
                            @change="previewImage($event, 'inspection_scan')"
                            outlined
                            dense
                            label="Скан"
                            data-vv-as="скан документа"
                            name="inspection_scan_file"
                            v-model="trafficsSafety.inspection_scan_file"
                            :error-messages="errors.collect('inspection_scan_file')"
                            v-validate="
                                !trafficsSafety.inspection_scan ? trafficsSafety.rules.inspection_scan_file : null
                            "
                        >
                        </v-file-input>
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>

        <!--Image dialog-->
        <v-dialog v-model="imgDialog" max-width="600" width="100%">
            <v-card v-if="dialogImgSrc">
                <v-btn absolute dark fab right small color="error" @click="imgDialog = false" class="mt-3 mr-3">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <div style="max-height: 800px; overflow-y: auto" class="">
                    <v-img :src="dialogImgSrc" />
                </div>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import TrafficSafety from "../../models/TrafficSafety";
import Snackbar from "../../facades/Snackbar";
import DatePicker from "../../../shared/components/form/DatePicker";
export default {
    components: { DatePicker },
    props: {
        inspection: {
            required: true,
        },
    },
    data() {
        return {
            loading: false,
            trafficsSafety: new TrafficSafety(this.inspection),
            disabled: true,

            startMenu: false,
            endMenu: false,

            lazyImage: "/storage/img/noimage.png",
            imgDialog: false,
            dialogImgSrc: null,
        };
    },
    methods: {
        showImgDialog(src) {
            this.imgDialog = true;
            this.dialogImgSrc = src;
        },
        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = e => {
                this.trafficsSafety[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event);
            } else {
                this.trafficsSafety[key] = null;
            }

            return true;
        },

        nextDay(date) {
            if (date) {
                let day = new Date(date);
                let nextDay = new Date(day);
                nextDay.setDate(day.getDate() + 1);
                return nextDay.toISOString();
            }
        },

        prevDay(date) {
            if (date) {
                let day = new Date(date);
                let nextDay = new Date(day);
                nextDay.setDate(day.getDate() - 1);
                return nextDay.toISOString();
            }
        },
        showScan() {
            let reader = new FileReader();
            let self = this;
            reader.onload = function (e) {
                self.uploadedSrc = e.target.result;
                self.car.inspection_scan = self.uploaded;
            };
            reader.readAsDataURL(self.uploaded);
        },

        clear() {
            this.$emit("close");
            this.disabled = true;
            this.uploadedSrc = false;
        },

        save() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.loading = true;
                    this.trafficsSafety
                        .updateInspection()
                        .then(response => {
                            this.loading = false;
                            this.clear();
                            this.$emit("change");
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
    },
    watch: {
        inspection: function () {
            this.trafficsSafety = new TrafficSafety(this.inspection || {});
        },
    },
    created() {},
};
</script>
