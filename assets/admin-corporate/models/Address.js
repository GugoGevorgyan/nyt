/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class Address extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = 'address';

    /**
     * @type {string}
     */
    primaryKey = 'client_address_id';

    /**
     * @type {{}}
     */
    rules = {
        displayName: 'required',
        value: 'required',
        namespace: 'required',
        porch: '',
        driverHint: '',
        type: '',
        lat: 'required',
        lut: 'required',
    };

    /**
     * @type {boolean}
     */
    loadingCoordinates = false;

    constructor(address = {}) {
        super('company/client/address', 'admin/corporate/', '', {
            create: 'create',
            update: 'update',
            delete: 'delete',
            deletes: 'deletes',
        });

        this.client_address_id = address.client_address_id || null;
        this.client_id = address.client_id || null;
        this.address = address.address || null;
        this.name = address.name || null;
        this.porch = address.porch || null;
        this.driverHint = address.driverHint || null;
        this.type = address.type || null;
        this.lat = address.lat || null;
        this.lut = address.lut || null;
        this.icon = address.icon || 'mdi-plus';
    }

    getAddressCoords() {
        this.loadingCoordinates = true;
        return axios.get(`get-coordinates/${this.address}`).then(response => {
            if (response.data) {
                this.lat = response.data.lat;
                this.lut = response.data.lut;
            }

            this.loadingCoordinates = false;
        });
    }
}
