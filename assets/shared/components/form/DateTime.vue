<!-- @format -->

<template>
    <v-dialog v-model="display" :width="dialogWidth" :dark="colorMode">
        <template v-slot:activator="{ on }">
            <v-text-field
                v-if="'input' === selector"
                v-bind="textFieldProps"
                @click="$emit('clickOpen')"
                :disabled="disabled"
                :loading="loading"
                :label="label"
                :value="formattedDatetime"
                v-on="on"
                readonly
            >
                <template v-slot:progress>
                    <slot name="progress">
                        <v-progress-linear color="primary" indeterminate absolute height="2" />
                    </slot>
                </template>
            </v-text-field>

            <v-btn v-else icon v-bind="textFieldProps" v-on="on" @click="$emit('clickOpen')">
                <v-icon color="grey darken-2" v-text="'mdi-timetable'" />
            </v-btn>
        </template>

        <v-card class="border-lg" :dark="colorMode">
            <v-card-text class="px-0 py-0">
                <v-tabs fixed-tabs v-model="activeTab" class="border-lg" right color="grey darken-3">
                    <v-tab key="calendar">
                        <slot name="dateIcon">
                            <v-icon v-text="'mdi-calendar-month'" />
                        </slot>
                    </v-tab>
                    <v-tab key="timer" :disabled="dateSelected">
                        <slot name="timeIcon">
                            <v-icon v-text="'mdi-calendar-clock'" />
                        </slot>
                    </v-tab>
                    <v-tab-item key="calendar">
                        <v-date-picker
                            :dark="colorMode"
                            :header-color="dateHeaderColor"
                            v-model="date"
                            v-bind="datePickerProps"
                            @input="showTimePicker"
                            class="rounded-0"
                            color="grey darken-3"
                            full-width
                            :max="maxDate"
                            :min="minDate"
                        />
                    </v-tab-item>
                    <v-tab-item key="timer">
                        <v-time-picker
                            :dark="colorMode"
                            :header-color="timeHeaderColor"
                            ref="timer"
                            color="grey darken-3"
                            class="v-time-picker-custom rounded-0"
                            v-model="time"
                            v-bind="timePickerProps"
                            format="24hr"
                            full-width
                        />
                    </v-tab-item>
                </v-tabs>
            </v-card-text>
            <v-divider />
            <v-card-actions>
                <v-spacer />
                <slot name="actions" :parent="this">
                    <v-btn color="grey darken-1" text @click.native="clearHandler" v-text="clearText" />
                    <v-btn color="green darken-1" text @click="okHandler" v-text="okText" />
                </slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { format, parse } from 'date-fns';
const DEFAULT_DATE = new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate();
const DEFAULT_TIME = new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds();
const DEFAULT_DATE_FORMAT = 'yyyy-MM-dd';
const DEFAULT_TIME_FORMAT = 'HH:mm:ss';
const DEFAULT_DIALOG_WIDTH = 340;
const DEFAULT_CLEAR_TEXT = 'CLEAR';
const DEFAULT_OK_TEXT = 'OK';

export default {
    name: 'v-datetime-picker',
    model: {
        prop: 'datetime',
        event: 'input',
    },
    props: {
        datetime: {
            type: [Date, String],
            default: null,
        },
        disabled: {
            type: Boolean,
        },
        loading: {
            type: Boolean,
        },
        label: {
            type: String,
            default: '',
        },
        dialogWidth: {
            type: Number,
            default: DEFAULT_DIALOG_WIDTH,
        },
        dateFormat: {
            type: String,
            default: DEFAULT_DATE_FORMAT,
        },
        timeFormat: {
            type: String,
            default: 'HH:mm',
        },
        clearText: {
            type: String,
            default: DEFAULT_CLEAR_TEXT,
        },
        okText: {
            type: String,
            default: DEFAULT_OK_TEXT,
        },
        textFieldProps: {
            type: Object,
        },
        datePickerProps: {
            type: Object,
        },
        timePickerProps: {
            type: Object,
        },
        timeHeaderColor: {
            type: String,
        },
        dateHeaderColor: {
            type: String,
        },
        colorMode: {
            type: Boolean,
            default: false,
        },
        selector: {
            type: String,
            default: 'input',
        },
        maxDate: {
            type: String,
            default: null,
        },
        minDate: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            display: false,
            activeTab: 0,
            date: DEFAULT_DATE,
            time: DEFAULT_TIME,
        };
    },
    mounted() {
        this.init();
    },
    computed: {
        dateTimeFormat() {
            return this.dateFormat + ' ' + this.timeFormat;
        },
        defaultDateTimeFormat() {
            return DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT;
        },
        formattedDatetime() {
            return this.selectedDatetime ? format(this.selectedDatetime, this.dateTimeFormat) : '';
        },
        selectedDatetime() {
            if (this.date && this.time) {
                let datetimeString = this.date + ' ' + this.time;
                if (5 === this.time.length) {
                    datetimeString += ':00';
                }
                return parse(datetimeString, this.defaultDateTimeFormat, new Date());
            } else {
                return null;
            }
        },
        dateSelected() {
            return !this.date;
        },
    },
    methods: {
        init() {
            if (!this.datetime) {
                return;
            }
            let initDateTime;
            if (this.datetime instanceof Date) {
                initDateTime = this.datetime;
            } else if (typeof this.datetime === 'string' || this.datetime instanceof String) {
                initDateTime = parse(this.datetime, this.dateTimeFormat, new Date());
            }
            this.date = format(initDateTime, DEFAULT_DATE_FORMAT);
            this.time = format(initDateTime, DEFAULT_TIME_FORMAT);
        },
        okHandler() {
            this.resetPicker();
            this.$emit('input', this.selectedDatetime);
        },
        clearHandler() {
            this.resetPicker();
            this.date = DEFAULT_DATE;
            this.time = DEFAULT_TIME;
            this.$emit('input', null);
        },
        resetPicker() {
            this.display = false;
            this.activeTab = 0;
            if (this.$refs.timer) {
                this.$refs.timer.selectingHour = true;
            }
        },
        showTimePicker() {
            this.activeTab = 1;
        },
    },
    watch: {
        datetime: function () {
            this.init();
        },
    },
};
</script>

<style lang="scss">
.v-time-picker-title__time .v-picker__title__btn,
.v-time-picker-title__time span {
    align-items: center;
    display: inline-flex;
    height: 53px !important;
    font-size: 70px;
    justify-content: center;
}
</style>
