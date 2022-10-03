/** @format */

import Employee from "../../../models/Employee";
import { mapState } from "vuex";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "EmployeeInfo",

    props: {
        employeeObj: {
            required: true,
        },
    },

    data() {
        return {
            carClasses: [],
            carClassesLoading: false,

            employee: new Employee({phoneMask: this.$store.state.phoneMask}),
            employeeLoading: false,
            employeeFieldsDisabled: true,
            employeePhoneDisabled: false,

            checkClientLoading: false,
            existsClient: undefined,
            existsClientDialog: false,
            existsClientCompany: false,
            existsClientRegistered: false,
            classMultiple: true,
            clientSaveOrUpdate: false
        };
    },

    computed: {
        ...mapState(["company"]),

        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },

    watch: {
        employeeObj: function () {
            this.setEmployeeObjValues();
        },

        "employee.phone": function (newVal, oldVal) {
            this.$validator.reset();
            if (oldVal && newVal && oldVal.length > newVal.length + 1 && oldVal.length == this.phoneMask.length) {
                this.employee.phone = ''
                setTimeout(() => {
                    this.employee.phone = newVal;
                },100);
            }
            if (oldVal && this.phoneMask.length < oldVal.length) { this.employeeFieldsDisabled = false; return }
            if (!this.employeeObj && (!oldVal || newVal !== oldVal.replace(/[^0-9]+/g, ""))) {
                if (this.employee.phone && this.employee.phone.length === this.phoneMask.length) {
                    setTimeout(() => this.checkClient(this.employee.phone), 300);
                } else {
                    this.employeeFieldsDisabled = true;
                }
            } else {
                this.employeeFieldsDisabled = false;
            }

            if (!newVal) {
                this.employee.surname = "";
                this.employee.name = "";
                this.employee.patronymic = "";
                this.employee.limit = '';
                this.employee.car_classes = null;
            }
        },
    },

    methods: {
        getCarClasses() {
            let clientId = this.employeeObj ? `/${this.employeeObj.client.client_id}` : "";
            this.$http.get(`get-car-classes/${this.company.company_id}${clientId}`).then(response => {
                if (response.data.car_classes) {
                    if (1 >= response.data.car_classes.length) {
                        this.classMultiple = false;
                    }
                    this.carClasses = response.data.car_classes;
                }
            });
        },

        checkClient(phone) {
            this.checkClientLoading = true;
            this.$http
                .post("check-client", { phone: phone, company_id: this.company.company_id })
                .then(response => {
                    this.checkClientLoading = false;
                    this.clientSaveOrUpdate = false;
                    if (response.data.client || response.data.company) {
                        this.employeeFieldsDisabled = false;
                        if (response.data.registered) {
                            this.setClientFields(
                                response.data.client,
                                phone,
                                response.data.registered.limit,
                                response.data.registered.allow_weekends,
                                response.data.registered.allow_order);
                        } else {
                            this.setClientFields(response.data.client, phone)
                        }
                        this.showExistsClientDialog(response.data);
                    } else {
                        this.employeeFieldsDisabled = false;
                    }
                })
                .catch(error => {
                    this.checkClientLoading = false;
                });
        },
        setClientFields (existsClient, visiblePhone, client_limit, allow_weekends, allow_order) {
            this.existsClient = {
                'client_id': existsClient.client_id,
                'created_at': existsClient.created_at,
                'device': existsClient.device,
                'email': existsClient.email,
                'in_order': existsClient.in_order,
                'logged': existsClient.logged,
                'mean_assessment': existsClient.mean_assessment,
                'name': existsClient.name,
                'online': existsClient.online,
                'only_passenger': existsClient.only_passenger,
                'patronymic': existsClient.patronymic,
                'phone': visiblePhone,
                'surname': existsClient.surname,
                'updated_at': existsClient.updated_at,
                'allow_weekends': allow_weekends ? allow_weekends : '',
                'allow_order': allow_order ? allow_order : '',
                'client_limit': client_limit ? client_limit : '',
            }
        },
        showExistsClientDialog(data) {
            this.existsClientCompany = data.company;
            this.existsClientRegistered = data.registered;
            this.clientSaveOrUpdate = data.registered ? true : false;
            this.existsClientRegistered || this.existsClientCompany
                ? (this.existsClientDialog = true)
                : this.setClientValues();
        },

        closeExistsClientDialog() {
            this.clientSaveOrUpdate = false;
            this.$refs.phoneVisible.reset()
            this.existsClientDialog = false;
            this.employeeFieldsDisabled = true;
            this.existsClient = undefined;
            this.existsClientCompany = false;
            this.existsClientRegistered = false;
        },

        setClientValues() {
            this.employee.client_id = this.existsClient.client_id;
            this.employee.corporate_client_id = this.existsClient.corporate_client_id ?? null;
            this.employee.name = this.existsClient.name;
            this.employee.surname = this.existsClient.surname;
            this.employee.patronymic = this.existsClient.patronymic;
            this.employee.phone = this.existsClient.phone;
            this.employee.limit = this.existsClient.client_limit;
            this.employee.allow_weekends = this.existsClient.allow_weekends;
            this.employee.allow_order = this.existsClient.allow_order;
            this.employee.car_classes = !this.existsClient.car_classes_ids ? this.existsClientRegistered.car_classes_ids.ids : [];

            this.existsClientDialog = false;
            this.employeeFieldsDisabled = false;
            this.employeePhoneDisabled = true;
            this.existsClient = undefined;
            this.existsClientCompany = false;
            this.existsClientRegistered = false;
        },

        setEmployeeObjValues() {
            this.employee = this.employeeObj
                ? new Employee({
                      corporate_client_id: this.employeeObj.corporate_client_id,
                      client_id: this.employeeObj.client.client_id,
                      name: this.employeeObj.name,
                      surname: this.employeeObj.surname,
                      patronymic: this.employeeObj.patronymic,
                      phoneMask: this.phoneMask,
                      phone: this.employeeObj.client.phone,
                      limit: this.employeeObj.limit,
                      available: this.employeeObj.available,
                      allow_weekends: this.employeeObj.allow_weekends,
                      allow_order: this.employeeObj.allow_order,
                      car_classes: this.employeeObj.car_classes_ids.ids,
                  })
                : new Employee();
        },

        resetEmployee() {
            this.employee = new Employee();
            this.employeeFieldsDisabled = true;
            this.employeePhoneDisabled = false;
        },

        createEmployee() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.employeeLoading = true;
                    this.employee
                        .store()
                        .then(response => {
                            this.employeeLoading = false;
                            this.$emit("employeeCreated");
                            this.employee = new Employee();
                            this.employeePhoneDisabled = false;
                        })
                        .catch(error => {
                            this.employeeLoading = false;
                        });
                }
            });
        },

        updateEmployee() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.employeeLoading = true;
                    this.employee
                        .update({ "company/client": this.employee.client_id })
                        .then(response => {
                            this.$emit("employeeUpdated");
                            this.employeeLoading = false;
                            this.employee = new Employee();
                            this.employeePhoneDisabled = false;
                        })
                        .catch(error => {
                            this.employeeLoading = false;
                        });
                }
            });
        },
    },

    created() {
        this.getCarClasses();
        this.$store.dispatch('getCompanyPhoneMask').then()
        if (this.employeeObj) {
            this.setEmployeeObjValues();
        }
    },
};
