/** @format */

import Model from '../../base/Model';
import axios from 'axios';

export default class Metro extends Model {
    hidden = ['cityData', 'FormData'];

    cityData = [];

    FormData = false;

    rules = {
        name: 'required|min:3',
        input: 'min:3',
        address: 'required|min:3',
        cord: 'required|array',
    };

    constructor(metro = {}) {
        super('metro', 'station', 'admin/super', {
            create: '/create',
            update: '/update',
            delete: '/delete',
            deletes: '/deletes',
        });

        this.metro_id = metro.metro_id ?? null;
        this.name = metro.name;
        this.input = metro.input;
        this.city = metro.city;
        this.address = metro.address;
        this.cord = metro.cord ? [metro.cord[1], metro.cord[0]] : metro.cord;
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
