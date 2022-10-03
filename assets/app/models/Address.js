import axios from 'axios';

/** @format */

export default class Address {

    /**
     * @type {boolean}
     */
    loadingCoordinates = false;

    rules = {
        name: 'required',
        value: 'required',
        address: 'required',
        porch: '',
        driverHint: '',
    };

    constructor(address = {}) {
        this.client_address_id = address.client_address_id || null;
        this.client_id = address.client_id || null;
        this.address = address.address || '';
        this.value = address.address || '';
        this.name = address.name || '';
        this.porch = address.porch || '';
        this.driver_hint = address.driver_hint || '';
        this.lat = address.lat || null;
        this.lut = address.lut || null;
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
