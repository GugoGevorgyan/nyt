/** @format */

import Model from "../base/Model";
import axios from "axios";

export default class EntityBank extends Model {
    /**
     * @type {string}
     */
    scope = "entityBank";

    /**
     * @type {string}
     */
    primaryKey = "entity_bank_id";

    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {[]}
     */
    regionsSelectable = [];

    /**
     * @type {boolean}
     */
    regionsLoading = false;

    /**
     * @type {[]}
     */
    citiesSelectable = [];

    /**
     * @type {boolean}
     */
    citiesLoading = false;

    /**
     * @type {{bank_account_number: string, name: string, region_id: string, bank_identification_account: string, correspondent_account_number: string, country_id: string, city_id: string}}
     */
    rules = {
        name: "required|max:100|min:3",
        country_id: "required|integer",
        region_id: "required|integer",
        city_id: "required|integer",
        bank_account_number: "required|max:100|min:3",
        correspondent_account_number: "required|max:100|min:3",
        bank_identification_account: "required|max:100|min:3",
    };

    /**
     * @type {[string, string, string, string]}
     */
    hidden = ["regionsSelectable", "regionsLoading", "citiesSelectable", "citiesLoading"];

    /**
     * @param bank
     */
    constructor(bank = {}) {
        super("legal-entity/bank", process.env.MIX_APP_WORKER_URL, "", {
            delete: "delete",
            create: "store",
            update: "update",
        });

        this.entity_bank_id = bank.entity_bank_id || null;
        this.entity_id = bank.entity_id || null;
        this.name = bank.name || null;
        this.country_id = bank.country_id || null;
        this.region_id = bank.region_id || null;
        this.city_id = bank.city_id || null;
        this.bank_account_number = bank.bank_account_number || null;
        this.correspondent_account_number = bank.correspondent_account_number || null;
        this.bank_identification_account = bank.bank_identification_account || null;
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

    data() {
        return {
            name: this.name,
            country_id: this.country_id,
            region_id: this.region_id,
            city_id: this.city_id,
            bank_account_number: this.bank_account_number,
            correspondent_account_number: this.correspondent_account_number,
            bank_identification_account: this.bank_identification_account,
        };
    }
}
