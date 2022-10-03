/** @format */

import RegionsCities from "./RegionsCities";
import ModuleRoles from "./ModuleRoles";
import FranchiseAdmin from "../../models/FranchiseAdmin";
import EntityForm from "./EntityForm";
import DefaultRateForm from "./DefaultRateForm";
import Snackbar from "../../facades/Snackbar";
import Model from "../../base/Model";
import Franchise from "../../models/Franchise";
import axios from "axios";
import CallCenterPhones from "./CallCenterPhones";

export default {
    name: "FranchiseForm",

    components: { CallCenterPhones, EntityForm, DefaultRateForm, ModuleRoles, RegionsCities },

    props: {
        modules: {
            required: true,
            type: Array,
        },
        countries: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
        entityTypes: {
            required: true,
            type: Array,
        },
        franchiseObj: {
            required: true,
            type: Object,
        },
        driverTypes: {
            required: true,
            type: Array,
        },
    },

    data: function () {
        return {
            franchise: new Franchise(this.franchiseObj),
            lazyImage: "/storage/img/noimage.png",
            loading: false,

            regions: [],
            regionsLoading: false,

            phonesDialog: false,

            preventRegions: [],
            preventModules: [],
            moduleAlert: !!this.franchiseObj.franchise_id,
            regionAlert: !!this.franchiseObj.franchise_id,

            adminDialog: false,
            adminDialogLoading: false,
            admin: new FranchiseAdmin(),
            adminsErr: null,
            updateAdminIndex: -1,
            adminDeleteDialog: false,
            deleteAdmin: null,
            deleteAdminLoading: false,

            entityDialog: false,
            entitiesAll: this.entities,

            defaultRateDialog: false,

            selectedDriverTypeForDefaultRate: null,

            heightDif: 200,
            mask: '',
            visiblePhone: false,
        };
    },

    watch: {
        "franchise.country_id": function () {
            this.franchise.regions_cities = {};
            this.getRegions();
            this.franchise.phone = '';
        },
        "franchise.region_ids": function () {
            for (const [key] of Object.entries(this.franchise.regions_cities)) {
                if (!this.franchise.region_ids.includes(Number(key))) {
                    delete this.franchise.regions_cities[key];
                    this.removePreventRegion(key);
                }
            }
        },
        "franchise.module_ids": function () {
            for (const [key] of Object.entries(this.franchise.module_roles)) {
                if (!this.franchise.module_ids.includes(Number(key))) {
                    delete this.franchise.module_roles[key];
                    this.removePreventModule(key);
                }
            }
        },
        "franchise.name": function () {
            if (this.franchise.name) {
                this.franchise.nameLoading = true;
                this.franchise
                    .checkUniqueName()
                    .then(response => {
                        this.franchise.nameLoading = false;
                        if (!response.data.valid) {
                            this.errors.add({
                                field: "franchise.name",
                                msg: response.data.data.message,
                            });
                        }
                    })
                    .catch(error => {
                        this.franchise.nameLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }
        },
        "admin.change_password": function () {
            if (!this.admin.change_password) {
                this.admin.old_password = null;
                this.admin.password = null;
                this.admin.password_confirmation = null;
            }
        },

        "admin.nickname": function () {
            if (this.admin.nickname) {
                this.admin.nicknameLoading = true;
                this.admin
                    .checkUniqueNickname()
                    .then(response => {
                        this.admin.nicknameLoading = false;
                        if (!response.data.valid) {
                            this.errors.add({
                                field: "franchiseAdmin.nickname",
                                msg: response.data.data.message,
                            });
                        }
                    })
                    .catch(error => {
                        this.admin.nicknameLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }
        },
        "admin.email": function () {
            if (this.admin.email) {
                this.admin.emailLoading = true;
                this.admin
                    .checkUniqueEmail()
                    .then(response => {
                        this.admin.emailLoading = false;
                        if (!response.data.valid) {
                            this.errors.add({
                                field: "franchiseAdmin.email",
                                msg: response.data.data.message,
                            });
                        }
                    })
                    .catch(error => {
                        this.admin.emailLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }
        },

        callCenterPhones: function () {
            if (!this.callCenterPhones) {
                this.franchise.call_center_phones = [];
            }
        },
    },

    computed: {
        admins() {
            return this.franchise.franchise_id ? this.franchise.all_admins : this.franchise.new_admins;
        },

        callCenterPhones() {
            let module = this.modules.find(item => item.name === "call_center");
            return module ? !!this.franchise.module_ids.find(item => item === module.module_id) : false;
        },

        callCenterPhoneErr() {
            return this.callCenterPhones && !this.franchise.call_center_phones.length;
        },

        height() {
            return window.innerHeight - this.heightDif;
        },
    },

    methods: {
        /*window*/
        handleResize() {
            this.height = window.innerHeight - this.heightDif;
        },

        init() {
            let franchiseAddress = new ymaps.SuggestView("franchiseAddress");
            franchiseAddress.events.add("select", e => {
                this.franchise.address = e.get("item").value;
            });
        },

        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = e => {
                this.franchise[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event.target.files[0]);
                this.franchise.file = event.target.files[0];
            } else {
                this.franchise.file = this.lazyImage;
            }

            return true;
        },

        setDefaultModules() {
            this.modules.forEach(module => {
                if (module.default) this.franchise.module_ids.push(module.module_id);
            });
        },

        getModule(module_id) {
            return this.modules.find(item => {
                return item.module_id === module_id;
            });
        },

        updateRegionCities(regObj) {
            this.franchise.regions_cities[regObj.region] = regObj.cities;
        },

        removePreventRegion(region_id) {
            let index = this.preventRegions.findIndex(item => item === Number(region_id));
            if (~index) {
                this.preventRegions.splice(index, 1);
            }
        },

        updateModuleRoles(moduleObj) {
            this.franchise.module_roles[moduleObj.module] = moduleObj.roles;
        },

        removePreventModule(module_id) {
            let index = this.preventModules.findIndex(item => item === Number(module_id));
            if (~index) {
                this.preventModules.splice(index, 1);
            }
        },

        entityCreated(entity) {
            this.entitiesAll.push(entity);
            this.franchise.entity_id = entity.legal_entity_id;
        },

        store() {
            this.$validator.validateAll(this.franchise.scope).then(valid => {
                if (!this.callCenterPhoneErr && this.admins.length && valid) {
                    this.loading = true;
                    this.franchise
                        .store()
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.$router.push({ name: "admin.super.franchises" }).then(r => {});
                            this.loading = false;
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.loading = false;
                            Model.errors(error.response, this.franchise.scope).forEach(err => this.errors.add(err));
                        });
                }
            });
        },

        update() {
            this.$validator.validateAll(this.franchise.scope).then(valid => {
                if (!this.callCenterPhoneErr && this.admins.length && valid) {
                    this.loading = true;
                    this.franchise
                        .update({ franchise: this.franchise.franchise_id })
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.loading = false;
                            this.$router.push({ name: "admin.super.franchises" });
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.loading = false;
                            Model.errors(error.response, this.franchise.scope).forEach(err => this.errors.add(err));
                        });
                }
            });
        },

        /*call center*/
        updateCallCenterPhones(values) {
            this.franchise.call_center_phones = JSON.parse(JSON.stringify(values));
        },
        removeCallCenterPhone(id) {
            let index = this.franchise.call_center_phones.findIndex(item => item.franchise_phone_id === id);
            this.franchise.call_center_phones.splice(index, 1);
        },
        removeCallCenterSubPhone(subPhone) {
            let phoneIndex = this.franchise.call_center_phones.findIndex(
                item => item.franchise_phone_id === subPhone.franchise_phone_id,
            );
            let index = this.franchise.call_center_phones[phoneIndex].sub_phones.findIndex(
                item => item.franchise_sub_phone_id === subPhone.franchise_sub_phone_id,
            );
            this.franchise.call_center_phones[phoneIndex].sub_phones.splice(index, 1);
        },

        /*admin*/
        addAdmin() {
            this.admin = new FranchiseAdmin();
            this.adminDialog = true;
            this.updateAdminIndex = -1;
        },

        closeAdminDialog() {
            this.admin = new FranchiseAdmin();
            this.adminDialog = false;
            this.updateAdminIndex = -1;
        },

        appendAdmin() {
            this.adminDialogLoading = true;
            this.$validator.validateAll(this.admin.scope).then(valid => {
                if (valid) {
                    ~this.updateAdminIndex
                        ? this.franchise.new_admins.splice(this.updateAdminIndex, 1, this.admin.data())
                        : this.franchise.new_admins.unshift(this.admin.data());
                    this.closeAdminDialog();
                    this.adminDialogLoading = false;
                } else {
                    this.adminDialogLoading = false;
                }
            });
        },

        updateAdmin(item, index) {
            this.updateAdminIndex = index;
            this.admin = new FranchiseAdmin(item);
            this.adminDialog = true;
        },

        removeAdmin(index) {
            this.franchise.new_admins.splice(index, 1);
        },

        createAdminRequest() {
            this.$validator.validateAll(this.admin.scope).then(valid => {
                if (valid) {
                    this.adminDialogLoading = true;
                    this.admin.franchise_id = this.franchise.franchise_id;
                    this.admin
                        .store()
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.franchise.all_admins.unshift(response.data.admin);
                            this.adminDialogLoading = false;
                            this.closeAdminDialog();
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.adminDialogLoading = false;
                            Model.errors(error.response, this.admin.scope).forEach(err => this.errors.add(err));
                        });
                }
            });
        },

        updateAdminRequest() {
            this.$validator.validateAll(this.admin.scope).then(valid => {
                if (valid) {
                    this.adminDialogLoading = true;
                    this.admin
                        .update({ "franchise-admin": this.admin.system_worker_id })
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.franchise.all_admins.splice(this.updateAdminIndex, 1, response.data.admin);
                            this.adminDialogLoading = false;
                            this.closeAdminDialog();
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.adminDialogLoading = false;
                            Model.errors(error.response, this.admin.scope).forEach(err => this.errors.add(err));
                        });
                }
            });
        },

        adminDeleteConfirmation(admin) {
            this.deleteAdmin = new FranchiseAdmin(admin);
            this.adminDeleteDialog = true;
        },

        closeAdminDeleteDialog() {
            this.adminDeleteDialog = false;
            this.deleteAdmin = null;
        },

        removeAdminRequest() {
            if (this.deleteAdmin) {
                this.deleteAdminLoading = true;
                this.deleteAdmin
                    .delete({ "franchise-admin": this.deleteAdmin.system_worker_id })
                    .then(response => {
                        Snackbar.info(response.data.message);
                        this.deleteAdminLoading = false;
                        this.removeDeletedAdmin(this.deleteAdmin);
                        this.closeAdminDeleteDialog();
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.deleteAdminLoading = false;
                    });
            }
        },

        removeDeletedAdmin(admin) {
            let index = this.franchise.all_admins.findIndex(item => {
                return item.system_worker_id === admin.system_worker_id;
            });
            this.franchise.all_admins.splice(index, 1);
        },

        /*values*/
        getRegions() {
            if (this.franchise.country_id) {
                this.regionsLoading = true;
                axios
                    .get(`/admin/super/get/regions/${this.franchise.country_id}`)
                    .then(response => {
                        this.regionsLoading = false;
                        this.regions = response.data.regions;
                        this.mask = response.data.phoneMask;
                        this.visiblePhone = true;
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.regionsLoading = false;
                        this.regions = [];
                    });
            } else {
                this.regions = [];
            }
        },

        getSelectedRegion(region_id) {
            return this.regions.find(item => {
                return item.region_id === region_id;
            });
        },

        setCreteValues() {
            this.setDefaultModules();
        },

        setUpdateValues() {
            /*get regions*/
            this.getRegions();

            /*set selected regions*/
            this.franchiseObj.region_city_ids.forEach(item => {
                this.franchise.region_ids.push(item.region_id);
                this.preventRegions.push(item.region_id);
                let city_ids = [];
                item.franchise_cities.forEach(city => {
                    city_ids.push(city.city_id);
                });
                this.franchise.regions_cities[item.region_id] = city_ids;
            });

            /*set selected modules*/
            this.franchiseObj.module_role_ids.forEach(item => {
                this.franchise.module_ids.push(item.module_id);
                this.preventModules.push(item.module_id);
                let role_ids = [];
                item.franchise_roles.forEach(role => {
                    role_ids.push(role.role_id);
                });
                this.franchise.module_roles[item.module_id] = role_ids;
            });

            /*set call center phones*/
            this.franchise.call_center_phones = this.franchiseObj.phones;

            /*set admins*/
            this.franchise.all_admins = this.franchiseObj.admins;
        },

        selectAllModules() {
            this.modules.forEach(module => {
                this.franchise.module_ids.push(module.module_id);
            });
        },

        deleteAllModules() {
            this.franchise.module_ids = [];
        },

        openDefaultRateDialog(i) {
            this.selectedDriverTypeForDefaultRate = i;
            this.defaultRateDialog = true;
        },

        selected_default_rates() {
            return this.selectedDriverTypeForDefaultRate !== null
                ? this.franchise.option[this.driverTypes[this.selectedDriverTypeForDefaultRate].driver_type_id]
                : null;
        },

        defaultRateSubmitted(rates) {
            this.franchise.option[this.driverTypes[this.selectedDriverTypeForDefaultRate].driver_type_id] = rates;
        },

        defaultRatesErr() {
            return Object.keys(this.franchise.option).length < 4;
        },
    },

    created() {
        window.addEventListener("resize", this.handleResize);
        this.franchise.franchise_id ? this.setUpdateValues() : this.setCreteValues();
        ymaps.ready(this.init);
    },
};
