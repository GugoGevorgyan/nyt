/** @format */

import Driver from "../../../models/Driver";
import Snackbar from "../../../facades/Snackbar";
import DatePicker from "../../../../shared/components/form/DatePicker";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "DriverDialog",

    components: { DatePicker },

    props: {
        candidate: {
            required: true,
        },
        types: {
            required: true,
        },
        graphics: {
            required: true,
        },
    },

    data() {
        return {
            loading: false,
            driver: new Driver({
                phone: this.candidate.driver ? null : this.candidate.phone,
                driver_id: this.candidate.driver ? this.candidate.driver.driver_id : 0,
                candidate_id: this.candidate.driver_candidate_id,
            }),

            showEntities: false,
            entities: [],
            loadingEntities: false,
            freeDaysDisabled: false,

            endDateMenu: false,
            workDateMenu: false,
        };
    },

    watch: {
        candidate: function () {
            if (this.candidate) {
                this.driver = new Driver({
                    phone: this.candidate.driver ? null : this.candidate.phone,
                    driver_id: this.candidate.driver ? this.candidate.driver.driver_id : 0,
                    candidate_id: this.candidate.driver_candidate_id,
                });
            }
        },
        "driver.subtype_id": function () {
            if (this.driver.subtype_id) {
                let show = false;
                this.types.forEach(item => {
                    item.subtypes.forEach(subItem => {
                        if (
                            subItem.driver_subtype_id === this.driver.subtype_id &&
                            ("tenant_individual_entrepreneur" === subItem.value ||
                                "aggregator_individual_entrepreneur" === subItem.value ||
                                "will_tell_individual_entrepreneur" === subItem.value ||
                                "corporate_individual_entrepreneur" === subItem.value)
                        ) {
                            show = true;
                        }
                    });
                });

                if (show) {
                    this.showEntities = true;
                    this.driver.entity_id = null;
                } else {
                    this.showEntities = false;
                    this.driver.entity_id = null;
                }
            } else {
                this.showEntities = false;
                this.driver.entity_id = null;
            }
        },
        "driver.expiration_day": function () {
            let oneDay = 24 * 60 * 60 * 1000;
            let firstDate = new Date();
            let secondDate = new Date(this.driver.expiration_day);
            this.driver.days_count = Math.ceil(Math.abs((firstDate - secondDate) / oneDay)).toString();
        },
        "driver.days_count": function () {
            let date = new Date();
            date.setDate(date.getDate() + Number(this.driver.days_count));
            this.driver.expiration_day = date.toISOString().slice(0, 10);
            this.driver.work_start_day = new Date().toISOString().slice(0, 10);
        },
        "driver.graphic_id": function () {
            let graphic = this.graphics.find(item => this.driver.graphic_id === item.driver_graphic_id);
            if (graphic && !graphic.weekend_days_count) {
                this.driver.free_days_price = 0;
                this.freeDaysDisabled = true;
            } else {
                this.driver.free_days_price = null;
                this.freeDaysDisabled = false;
            }
        },
    },

    computed: {
        getSubtypes() {
            if (this.driver.type_id) {
                return this.types.find(item => {
                    return item.driver_type_id === this.driver.type_id;
                }).subtypes;
            }
        },
        url() {
            return this.$store.state.initUrl;
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },

    methods: {
        workStartDateMin() {
            let dateObj = new Date();
            return dateObj.toISOString();
        },

        endDateMin() {
            let dateObj = new Date();
            dateObj.setDate(dateObj.getDate() + 1);
            return dateObj.toISOString();
        },

        generatePassword() {
            let pass = Math.random().toString(36).substring(2, 15);
            this.driver.password = pass;
        },

        getEntities() {
            this.loadingEntities = true;
            this.$http.get("/app/worker/get/franchise-entities-ie").then(response => {
                if (response.data.entities) {
                    this.entities = response.data.entities;
                }
                this.loadingEntities = false;
            });
        },

        save() {
            this.loading = true;
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.driver
                        .candidateDriverCreate()
                        .then(response => {
                            this.loading = false;
                            this.$emit("close");
                            this.$emit("update");
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.loading = false;
                        });
                } else {
                    this.loading = false;
                }
            });
        },
    },

    created() {
        this.getEntities();
    },
};
