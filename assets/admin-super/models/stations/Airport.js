/** @format */

import Model from '../../base/Model';
import axios from 'axios';

export default class Airport extends Model {
    hidden = ['cityData', 'FormData'];

    cityData = [];

    FormData = false;

    rules = {
        name: 'required|min:3',
        terminal: 'min:3',
        address: 'required|min:3',
        cord: 'required|array',
    };

    constructor(airport = {}) {
        super('airport', 'station', 'admin/super', {
            create: '/create',
            update: '/update',
            delete: '/delete',
            deletes: '/deletes',
        });

        this.airport_id = airport.airport_id ?? null;
        this.name = airport.name;
        this.terminal = airport.terminal;
        this.city = airport.city;
        this.address = airport.address;
        this.cord = airport.cord ? [airport.cord[1], airport.cord[0]] : airport.cord;
        this.cities;
    }

    get cities() {
        axios
            .get('/admin/super/get/cities/1901')
            .then(response => {
                if (response.status === 200) {
                    this.cityData = response.data;
                }
            })
            .catch(error => {
                this.loading = false;
            });
    }
}
