/** @format */

export default {
    name: 'DefaultRateForm',

    props: {
        driverTypes: {
            required: true,
            type: Array
        },
        selectedDriverType: {
            required: true,
            type: Number
        },
        rules: {
            required: true,
            type: Object
        },
        rates: {
            required: true
        }
    },

    computed: {
        driverType() {
            return this.driverTypes[this.selectedDriverType];
        }
    },

    data: function () {
        return {
            loading: false,

            assessment: this.rates ? this.rates.default_assessment : null,
            rating: this.rates ? this.rates.default_rating : null,
            waybill_max_days: this.rates ? this.rates.waybill_max_days : null
        };
    },

    methods: {
        close() {
            this.$emit('close');
        },

        store() {
            this.$validator.validateAll('defaultRate').then(valid => {
                if (valid) {
                    this.loading = true;

                    this.$emit('submit', {
                        default_assessment: this.assessment,
                        default_rating: this.rating,
                        waybill_max_days: this.waybill_max_days
                    });

                    this.close();
                }
            });
        },
    },
};
