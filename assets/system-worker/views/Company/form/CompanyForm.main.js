/** @format */

import Company from "../../../models/Company";
import Snackbar from "../../../facades/Snackbar";
import MultiModel from "../../../base/MultiModel";
import AdminCorporate from "../../../models/AdminCorporate";
import axios from "axios";
import DatePicker from "../../../../shared/components/form/DatePicker";

export default {
    components: {
        'date-picker': DatePicker
    },

    props: {
        companyObj: {
            required: true,
        },
        adminsCount: {
            required: true,
        },
        phoneMask:  {}
    },

    data() {
        return {
            company: undefined,
            adminCorporate: undefined,
            companyLoading: false,
            codeLoading: false,
            emailLoading: false,

            startDateMenu: false,
            endDateMenu: false,
            lazyImage: "/storage/img/noimage.png",

            imgDialog: false,
            dialogImgSrc: null,

            loadingEntities: false,
            entities: [],

            window: {
                width: 0,
                height: window.innerHeight,
            },

            adminDialog: false,

            timer_code: null,
            code_cancel_token_source: axios.CancelToken.source(),

            timer_admin_corporate_email: null,
            admin_corporate_email_cancel_token_source: axios.CancelToken.source()
        };
    },

    watch: {
        companyObj: function () {
            this.setCompanyValues();
        },
        "company.code": function () {
            this.code_cancel_token_source.cancel();
            this.code_cancel_token_source = axios.CancelToken.source();

            clearTimeout(this.timer_code);

            this.timer_code = setTimeout(() => {
                this.codeLoading = true;

                let data = {
                    code: this.company.code,
                    table: "companies",
                    col: "code",
                    primary: "company_id",
                    primary_value: this.company.company_id,
                };

                axios
                    .post(this.url + "check/unique", data, {
                        cancelToken: this.code_cancel_token_source.token
                    })
                    .then(response => {
                        this.codeLoading = false;
                        if (!response.data.valid) {
                            this.errors.add({
                                field: "company.code",
                                msg: response.data.data.message,
                            });
                        }
                    })
                    .catch(error => {
                        this.codeLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }, 500);
        },
        "adminCorporate.email": function () {
            this.admin_corporate_email_cancel_token_source.cancel();
            this.admin_corporate_email_cancel_token_source = axios.CancelToken.source();

            clearTimeout(this.timer_admin_corporate_email);

            this.timer_admin_corporate_email = setTimeout(() => {
                this.emailLoading = true;

                let data = {
                    email: this.adminCorporate.email,
                    table: "admin_corporates",
                    col: "email",
                    primary: "admin_corporate_id",
                    primary_value: this.adminCorporate.admin_corporate_id,
                };

                axios
                    .post(this.url + "check/unique", data, {
                        cancelToken: this.admin_corporate_email_cancel_token_source.token
                    })
                    .then(response => {
                        this.emailLoading = false;
                        if (!response.data.valid) {
                            this.errors.add({
                                field: "adminCorporate.email",
                                msg: response.data.data.message,
                            });
                        }
                    })
                    .catch(error => {
                        this.emailLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }, 500);
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        contentHeight() {
            return this.window.height - (this.companyObj ? 470 : 210);
        },
    },

    methods: {
        setCompanyValues() {
            this.company = new Company(this.companyObj ? { ...this.companyObj } : {});
            this.adminCorporate = new AdminCorporate();
        },
        addPhone() {
            this.company.additional_phones.push(null);
        },
        removePhone(index) {
            this.company.additional_phones.splice(index, 1);
        },
        showImgDialog(src) {
            this.imgDialog = true;
            this.dialogImgSrc = src;
        },
        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = event => {
                this.company[key] = event.target.result;
            };

            if (event) {
                reader.readAsDataURL(event);
            } else {
                this.company[key] = this.lazyImage;
            }

            return true;
        },
        generatePassword() {
            this.adminCorporate.password = Math.random().toString(36).substring(2, 15);
        },
        getEntities() {
            this.loadingEntities = true;
            axios.get(this.url + "get/franchise-entities-not-ie").then(response => {
                if (response.data.entities) {
                    this.entities = response.data.entities;
                }
                this.loadingEntities = false;
            });
        },
        create() {
            this.$validator.validateAll("company").then(validCompany => {
                if (validCompany) {
                    this.companyLoading = true;
                    this.form = new MultiModel(
                        [
                            this.company,
                            ... this.company.admin_added ? [this.adminCorporate] : []
                        ],
                        true
                    );
                    this.form
                        .send(`${this.url}company/store`, "post")
                        .then(response => {
                            this.companyLoading = false;
                            Snackbar.info(response.data.message);
                            window.location = `${this.url}company`;
                        })
                        .catch(error => {
                            this.companyLoading = false;
                            Snackbar.error(error.response.data.message);
                            Company.errors(error.response).forEach(error => this.errors.add(error));
                        });
                }
            });
        },
        update() {
            this.$validator.validateAll("company").then(valid => {
                if (valid) {
                    this.companyLoading = true;
                    this.form = new MultiModel(
                        [
                            this.company,
                            ... this.company.admin_added ? [this.adminCorporate] : []
                        ],
                        true
                    );
                    this.form
                        .send(`${this.url}company/update/` + this.company.company_id, "put")
                        .then(response => {
                            this.companyLoading = false;
                            Snackbar.info(response.data.message);
                            window.location = `${this.url}company`;
                        })
                        .catch(error => {
                            this.companyLoading = false;
                            Snackbar.error(error.response.data.message);
                            Company.errors(error.response).forEach(error => this.errors.add(error));
                        });
                }
            });
        },

        cancelAdmin() {
            this.adminDialog = false;
            this.adminCorporate = new AdminCorporate();
            this.company.admin_added = false;
        },
        acceptAdmin() {
            this.$validator.validateAll("adminCorporate").then(validAdmin => {
                if (validAdmin) {
                    this.adminDialog = false;
                    this.company.admin_added = true;
                }
            });
        },
    },

    created() {
        this.$store.state.phoneMask = this.phoneMask
        this.setCompanyValues();
        this.getEntities();
    },
};
