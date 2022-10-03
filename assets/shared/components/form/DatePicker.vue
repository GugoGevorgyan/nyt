<!-- @format -->

<template>
    <v-menu
        :close-on-content-click="false"
        :nudge-right="40"
        :min-width="pickerWidth"
        offset-y
        transition="scale-transition"
        v-model="picker"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                :color="color"
                outlined
                dense
                :prepend-inner-icon="inputIcon"
                :name="name"
                :label="label"
                v-model="inputValue"
                v-mask="mask"
                v-validate="inputRules"
                :error-messages="errorMessages"
                :data-vv-as="dataVvAs"
                :disabled="disabled"
            >
                <template v-slot:append style="margin-top: 0;">
                    <v-btn
                        depressed
                        style="margin-top: -6.2px; right: -10.6px;"
                        :disabled="disabled"
                        @click="picker = !picker"
                        :color="color"
                    >
                        <v-icon color="primary" v-text="pickerIcon" large />
                    </v-btn>
                </template>
            </v-text-field>
        </template>
        <v-date-picker :color="color" @input="picker = false" no-title :max="max" :min="min" v-model="pickerValue" />
    </v-menu>
</template>

<script>
export default {
    inject: ['$validator'],

    props: {
        min: {
            default: null,
            required: false,
            type: String,
        },
        max: {
            default: null,
            required: false,
            type: String,
        },
        disabled: {
            default: false,
            required: false,
            type: Boolean,
        },
        value: {
            default: null,
            type: String,
        },
        pickerWidth: {
            default: 290,
            type: [String, Number],
        },
        pickerIcon: {
            default: 'mdi-calendar',
            type: String,
        },
        inputIcon: {
            default: null,
            type: String,
        },
        name: {
            default: null,
            type: String,
        },
        scope: {
            default: null,
            type: String,
        },
        color: {
            default: null,
            type: String,
        },
        label: {
            default: null,
            type: String,
        },
        errorMessages: {
            default: null,
            type: [Array, Object],
        },
        dataVvAs: {
            default: null,
            type: [Array, String],
        },
        rules: {
            default: '',
            type: [String, Object],
        },
        mask: {
            default: '##.##.####',
            type: String,
        },
        format: {
            default: 'dd.mm.yyyy',
            type: String,
        },
    },

    data() {
        return {
            picker: false,
            inputValue: null,
            pickerValue: null,
            mainRules: 'date_format:',
        };
    },

    computed: {
        inputRules() {
            return this.rules ? `${this.mainRules}${this.format}` + '|' + this.rules : `${this.mainRules}${this.format}`;
        },
    },

    watch: {
        inputValue() {
            this.setInputValue();
        },
        pickerValue() {
            this.setPickerValue();
        },
        value() {
            this.getValue();
        },
    },

    methods: {
        setInputValue() {
            if (this.inputValue) {
                let arr = this.inputValue.split('.');
                if (3 === arr.length && arr[2] && 4 === arr[2].length) {
                    arr.reverse();
                    let format = arr.join('-');
                    if (!isNaN(Date.parse(format))) {
                        this.$emit('input', format);
                    }
                }
            } else {
                this.$emit('input', null);
            }
        },

        setPickerValue() {
            this.$emit('input', this.pickerValue);
        },

        getValue() {
            this.pickerValue = this.value;
            if (this.value) {
                let arr = this.value.split('-');
                arr.reverse();
                this.inputValue = arr.join('.');
            }
        },
    },

    created() {
        this.getValue();
    },
};
</script>

<style scoped></style>
