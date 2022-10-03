/** @format */

import DatePicker from "../../../../shared/components/form/DatePicker";
import MultiImage from "../../../../shared/components/form/MultiImage";
import axios from 'axios';
import Snackbar from '../../../facades/Snackbar';

// noinspection JSUnusedGlobalSymbols
export default {
    name: "CandidateForm",

    components: {
        'date-picker': DatePicker,
        'multi-image': MultiImage
    },

    props: {
        driverInfo: {
            required: true,
            type: Object,
        },
        driverCandidate: {
            required: true,
            type: Object,
        },
        learnStatuses: {
            required: true,
            type: Array,
        },
        tutors: {
            required: true,
            type: Array,
        },
        licenseTypes: {
            required: true,
            type: Array,
        },
        update: {
            required: true,
            type: Boolean,
        },
        disabledLicense: {
            required: true,
            type: Boolean,
        },
        disabled: {
            required: true,
            type: Boolean,
        },
        url: {
            required: true,
            type: String,
        },
        btnTitle: {
            required: true,
            type: String,
        },
        loading: {
            required: true,
            type: Boolean,
        },
    },

    watch: {
        "driverInfo.email": function () {
            if (this.driverInfo.email && this.driverInfo.email.length >= 3) {
                this.checkEmail();
            }
        }
    },

    data: function () {
        return {
            lazyImage: "/storage/img/noimage.png",
            lazyImageDownlaod: "/storage/img/download.svg",

            imgDialog: false,
            dialogImgSrc: undefined,

            dateStartMenu: false,
            dateEndMenu: false,
            pasIssueMenu: false,
            pasExpireMenu: false,
            birthdayMenu: false,
            licenseDateMenu: false,
            licenseExpiryMenu: false,
            dateLearnStart: false,
            dateLearnEnd: false
        };
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        },
        date_18_years_ago() {
            let date = new Date;
            date.setFullYear(date.getFullYear() - 18);

            return date.toISOString().slice(0,10);
        }
    },

    methods: {
        /*support methods*/
        today() {
            let today = new Date();
            return today.toISOString();
        },
        showImgDialog(src) {
            this.imgDialog = true;
            this.dialogImgSrc = src;
        },
        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = e => {
                this.driverInfo[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event);
            } else {
                this.driverInfo.photo = this.lazyImage;
            }

            return true;
        },
        /*send data*/
        submit() {
            this.$validator
                .validateAll()
                .then(valid => {
                    if (valid) {
                        this.$emit("submit");
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        parseDate(date) {
            if (!date) {
                return null;
            }

            const [month, day, year] = date.split("/");
            return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
        },
        checkEmail() {
            this.emailLoading = true;

            let data = {
                email: this.worker.email,
                table: "system_workers",
                col: "email",
                primary: "system_worker_id",
                primary_value: this.worker.system_worker_id,
            };

            axios
                .post(process.env.MIX_APP_WORKER_URL + "check/unique", data)
                .then(response => {
                    this.emailLoading = false;
                    if (!response.data.valid) {
                        this.errors.add({
                            field: "email",
                            msg: response.data.data.message,
                        });
                    }
                })
                .catch(error => {
                    this.emailLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    }
};
