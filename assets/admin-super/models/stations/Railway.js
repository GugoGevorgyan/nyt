/** @format */

import Model from '../../base/Model';
import axios from 'axios';

export default class Railway extends Model {
    hidden = ['cityData', 'FormData'];

    cityData = [];

    FormData = false;

    rules = {
        name: 'required|min:3',
        input: 'min:3',
        address: 'required|min:3',
        cord: 'required|array',
    };

    constructor(railway = {}) {
        super('railway', 'station', 'admin/super', {
            create: '/create',
            update: '/update',
            delete: '/delete',
            deletes: '/deletes',
        });

        this.railway_id = railway.railway_id ?? null;
        this.name = railway.name;
        this.input = railway.input;
        this.city = railway.city;
        this.address = railway.address;
        this.cord = railway.cord ? [railway.cord[1], railway.cord[0]] : railway.cord;
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
