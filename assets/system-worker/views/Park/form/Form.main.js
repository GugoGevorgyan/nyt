/** @format */

import Park from "../../../models/Park";
import Snackbar from "../../../facades/Snackbar";

export default {
    name: "ParkForm",

    props: {
        parkData: {
            required: true,
        },
        regions: {
            required: true,
            type: Array,
        },
        cities: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
        managers: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            park: new Park({ ...this.parkData }),

            src: null,
            loading: false,
            region: undefined,

            tax_psrn_date_menu: false,
            registration_certificate_date_menu: false,
        };
    },

    methods: {
        previewImage(event, object, key) {
            let reader = new FileReader();

            reader.onload = e => {
                object[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event);
            } else {
                object[key] = this.lazyImage;
            }

            return true;
        },

        today() {
            let today = new Date();
            return today.toISOString();
        },

        close() {
            this.$emit("close");
        },

        store() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true;
                    this.park
                        .store()
                        .then(response => {
                            this.$emit("refresh");
                            this.close();
                            Snackbar.info(response.data.message);
                            this.loading = false;
                        })
                        .catch(error => {
                            Park.errors(error.response).forEach(error => this.errors.add(error));
                            Snackbar.error(error.response.data.message);
                            this.loading = false;
                        });
                }
            });
        },

        update() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true;
                    this.park
                        .update({ park: this.park.park_id })
                        .then(response => {
                            this.$emit("refresh");
                            Snackbar.info(response.data.message);
                            this.loading = false;
                            this.close();
                        })
                        .catch(error => {
                            Park.errors(error.response).forEach(error => this.errors.add(error));
                            Snackbar.error(error.response.data.message);
                            this.loading = false;
                        });
                }
            });
        },

        setRegion() {
            this.region = this.park.city ? this.park.city.region_id : undefined;
        },
    },

    watch: {
        parkData: function (newParkData) {
            this.src = null;
            this.park = new Park({ ...newParkData });
            this.setRegion();
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },

        selectedSities: function () {
            let selected = this.regions.filter(item => item.region_id === this.region);
            if (0 < selected.length) {
                let region = selected[0];
                return this.cities.filter(item => item.region_id === region.region_id);
            }
        },
    },

    created() {
        this.setRegion();
    },
};
