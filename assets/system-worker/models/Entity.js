/** @format */

import Model from "../base/Model";
import axios from "axios";
import store from "../store";

export default class Entity extends Model {
    /**
     * @type {string}
     */
    scope = "entity";

    formData = true;

    regionsSelectable = [];

    regionsLoading = false;

    citiesSelectable = [];

    citiesLoading = false;

    rules = {
        name: "required|max:100|min:3",
        type_id: "integer",
        country_id: "required|integer",
        region_id: "required|integer",
        city_id: "required|integer",
        zip_code: "integer",
        address: "max:100|min:3",
        phone: "length:" + store.state.phoneMask.length,
        email: "email",
        tax_inn: "",
        tax_kpp: "",
        tax_psrn: "",
        tax_psrn_serial: "",
        tax_psrn_issued_by: "",
        tax_psrn_date: "",
        director_name: "max:100|min:3",
        director_surname: "max:100|min:3",
        director_patronymic: "max:100|min:3",
        aucneb: "",
        arceo: "",
        arcfo: "",
        arclf: "",
        registration_certificate_number: "",
        registration_certificate_date: "",
        new_banks: "",
    };

    hidden = ["regionsSelectable", "regionsLoading", "citiesSelectable", "citiesLoading", "banks"];

    constructor(entity = {}) {
        super("legal-entity", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "store",
            update: "update",
        });

        this.legal_entity_id = entity.legal_entity_id || null;
        this.name = entity.name || null;
        this.type_id = entity.type_id || null;
        this.country_id = entity.country_id || null;
        this.region_id = entity.region_id || null;
        this.city_id = entity.city_id || null;
        this.zip_code = entity.zip_code || null;
        this.address = entity.address || null;
        this.phone = entity.phone || null;
        this.email = entity.email || null;
        this.tax_inn = entity.tax_inn || null;
        this.tax_kpp = entity.tax_kpp || null;
        this.tax_psrn = entity.tax_psrn || null;
        this.tax_psrn_serial = entity.tax_psrn_serial || null;
        this.tax_psrn_issued_by = entity.tax_psrn_issued_by || null;
        this.tax_psrn_date = entity.tax_psrn_date || null;
        this.director_name = entity.director_name || null;
        this.director_surname = entity.director_surname || null;
        this.director_patronymic = entity.director_patronymic || null;
        this.aucneb = entity.aucneb || null;
        this.arceo = entity.arceo || null;
        this.arcfo = entity.arcfo || null;
        this.arclf = entity.arclf || null;
        this.registration_certificate_number = entity.registration_certificate_number || null;
        this.registration_certificate_date = entity.registration_certificate_date || null;

        this.new_banks = entity.new_banks || [];
        this.banks = entity.banks || [];

        entity.legal_entity_id ? this.hidden.push("new_banks") : null;
    }

    getRegions() {
        if (this.country_id) {
            this.regionsLoading = true;
            axios
                .get(`${process.env.MIX_APP_WORKER_URL}get/regions/${this.country_id}`)
                .then(response => {
                    this.regionsLoading = false;
                    this.regionsSelectable = response.data.regions;
                })
                .catch(() => {
                    this.regionsLoading = false;
                    this.regionsSelectable = [];
                });
        } else {
            this.regionsSelectable = [];
        }
    }

    getCities() {
        if (this.region_id) {
            this.citiesLoading = true;
            axios
                .get(`${process.env.MIX_APP_WORKER_URL}get/cities/${this.region_id}`)
                .then(response => {
                    this.citiesLoading = false;
                    this.citiesSelectable = response.data.cities;
                })
                .catch(() => {
                    this.citiesLoading = false;
                    this.citiesSelectable = [];
                });
        } else {
            this.citiesSelectable = [];
        }
    }
}
