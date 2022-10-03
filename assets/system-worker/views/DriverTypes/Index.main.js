/** @format */

import Snackbar from "../../facades/Snackbar";

// noinspection JSUnusedGlobalSymbols
export default {
    props: {
        types: {
            required: true,
            type: Array,
        },
        options: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            type: undefined,
            dialog: false,
            loading: false,
        };
    },

    computed: {
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },

    methods: {
        showDialog(item) {
            this.dialog = true;

            this.type = {
                type: item.type,
                driver_type_id: item.driver_type_id,
                options: this.getOptionsIds(item.franchise_options),
                options_value: item.percent,
            };
        },

        closeDialog() {
            this.dialog = false;
            this.type = undefined;
        },

        setOptions(options) {
            this.types.map(type => {
                if (type.driver_type_id === this.type.driver_type_id) {
                    type.franchise_options = options;
                    type.percent = options[0].pivot.percent_value;
                }
            });
            this.closeDialog();
        },

        save() {
            this.loading = true;
            this.$http
                .post("driver-types/update", this.type)
                .then(response => {
                    this.loading = false;
                    Snackbar.info(response.data.message);
                    this.setOptions(response.data.franchise_options);
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        getOptionsIds(options) {
            let ids = [];
            options.forEach(option => {
                ids.push(option.driver_type_optional_id);
            });
            return ids;
        },
    },

    created() {
        for (let item of this.types) {
            let percent = item.franchise_options.find(element => {
                if (element.driver_type_id === item.driver_type_id && element.valued) {
                    return element;
                }
            });

            item.percent = percent ? percent.pivot.percent_value : "";
        }
    },
};

