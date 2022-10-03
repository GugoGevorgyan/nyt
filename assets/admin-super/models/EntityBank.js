/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class EntityBank extends Model {
    scope = 'entityBank';

    FormData = true;

    regionsSelectable = [];

    regionsLoading = false;

    citiesSelectable = [];

    citiesLoading = false;

    rules = {
        name: 'required|max:100|min:3',
        country_id: 'required|integer',
        region_id: 'required|integer',
        city_id: 'required|integer',
        bank_account_number: 'required|max:100|min:3',
        correspondent_account_number: 'required|max:100|min:3',
        bank_identification_account: 'required|max:100|min:3',
    };

    hidden = ['regionsSelectable', 'regionsLoading', 'citiesSelectable', 'citiesLoading'];

    constructor(bank = {}) {
        super('entityBanks', 'admin/super');

        this.name = bank.name;
        this.country_id = bank.country_id;
        this.region_id = bank.region_id;
        this.city_id = bank.city_id;
        this.bank_account_number = bank.bank_account_number;
        this.correspondent_account_number = bank.correspondent_account_number;
        this.bank_identification_account = bank.bank_identification_account;
    }

    getRegions() {
        if (this.country_id) {
            this.regionsLoading = true;
            axios
                .get(`/admin/super/get/regions/${this.country_id}`)
                .then(response => {
                    this.regionsLoading = false;
                    this.regionsSelectable = response.data;
                })
                .catch(error => {
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
                .get(`/admin/super/get/cities/${this.region_id}`)
                .then(response => {
                    this.citiesLoading = false;
                    this.citiesSelectable = response.data;
                })
                .catch(error => {
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
