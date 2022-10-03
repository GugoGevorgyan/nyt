/** @format */

import Snackbar from "../../facades/Snackbar";
import TrafficSafety from "../../models/TrafficSafety";
import DatePicker from "../../../shared/components/form/DatePicker";
import MultiImage from "../../../shared/components/form/MultiImage";
import moment from "moment";

export default {
    components: {
        'date-picker': DatePicker,
        'multi-image': MultiImage
    },

    props: {
        carObj: {
            required: true,
        },
        statuses: {
            required: true,
            type: Array,
        },
        classes: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
    },

    data() {
        let trafficsSafety = new TrafficSafety(this.carObj || {});

        return {
            passportPtsStep: 1,
            passportPtsStepIcon: "mdi-arrow-right",

            passportStsStep: 1,
            passportStsStepIcon: "mdi-arrow-right",

            lazyImageDownlaod: "/storage/img/download.svg",
            trafficsSafety: trafficsSafety,
            year_menu: false,
            license_date_menu: false,
            registration_date_menu: false,
            inspection_date_menu: false,
            inspection_expiration_date_menu: false,
            inspection_date_max: "",
            inspection_exp_date_min: "",
            insurance_date_menu: false,
            insurance_expiration_date_menu: false,
            insurance_date_max: "",
            insurance_exp_date_min: "",
            loading: false,
            height: 0,
            heightDif: 85,
            colors: [
                "WHITE",
                "SILVER",
                "GRAY",
                "BLACK",
                "RED",
                "MAROON",
                "YELLOW",
                "ORANGE",
                "OLIVE",
                "LIME",
                "GREEN",
                "AQUA",
                "TEAL",
                "BLUE",
                "NAVY",
                "FUCHSIA",
                "PURPLE",
            ],

            lazyImage: "/storage/img/noimage.png",
            imgDialog: false,
            dialogImgSrc: null,

            yearPickerOptions: {
                disabledDate(date){
                    return date > moment().subtract(1, 'days')
                }
            },

            year: trafficsSafety.year
        };
    },

    mounted() {
        this.handleResize();
    },

    methods: {
        togglePtsScan() {
            if (1 < this.passportPtsStep) {
                this.passportPtsStep--;
                this.passportPtsStepIcon = "mdi-arrow-right";
            } else {
                this.passportPtsStep++;
                this.passportPtsStepIcon = "mdi-arrow-left";
            }
        },
        toggleStsScan() {
            if (1 < this.passportStsStep) {
                this.passportStsStep--;
                this.passportStsStepIcon = "mdi-arrow-right";
            } else {
                this.passportStsStep++;
                this.passportStsStepIcon = "mdi-arrow-left";
            }
        },
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
                let prevDay = new Date(day);
                prevDay.setDate(day.getDate() - 1);
                return prevDay.toISOString();
            }
        },

        yesterday() {
            let d = new Date();
            d.setDate(d.getDate() - 1);
            return d.toISOString();
        },

        submit() {
            this.trafficsSafety.year = moment(this.year, "YYYY").year();

            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.loading = true;
                    this.trafficsSafety.car_id ? this.update() : this.create();
                }
            });
        },

        create() {
            this.trafficsSafety
                .store()
                .then(response => {
                    this.loading = false;
                    Snackbar.info(response.data.message);
                    window.location = process.env.MIX_APP_WORKER_URL + "traffic-safety";
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        update() {
            this.trafficsSafety
                .update({ "traffic-safety": this.trafficsSafety.car_id })
                .then(response => {
                    this.loading = false;
                    Snackbar.info(response.data.message);
                    window.location = process.env.MIX_APP_WORKER_URL + "traffic-safety";
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        handleResize() {
            this.height =
                window.innerHeight - this.$refs.title.clientHeight - this.$refs.actions.clientHeight - this.heightDif;
        }
    },

    created() {
        window.addEventListener("resize", this.handleResize);
        console.log(this.carObj);
    },
};
