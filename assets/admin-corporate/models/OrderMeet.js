/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class OrderMeet extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = 'meet';

    /**
     * @type {string}
     */
    primaryKey = 'order_meet_id';

    /**
     * @type {string[]}
     */
    hidden = ['baseUrl', 'adminUrl', '_method', 'airports', 'stations', 'travel'];

    /**
     * @type {*[]}
     */
    airports = [];

    /**
     * @type {*[]}
     */
    stations = [];

    /**
     * @type {*[]}
     */
    metros = [];

    /**
     * @type {string}
     */
    travel = 'none';

    /**
     * @type {object}
     */
    rules = {
        wagon_number: '',
        flight_number: '',
        from: '',
        text: '',
    };

    /**
     * @param meet
     */
    constructor(meet = {}) {
        super('', 'admin/corporate/', '', {
            create: 'create',
            update: 'update',
            delete: 'delete',
            deletes: 'deletes',
        });

        this.is_meet = meet.is_meet || false;
        this.airport = meet.airport || null;
        this.station = meet.station || null;
        this.metro = meet.metros || null;
        this.wagon_number = meet.wagon_number || null;
        this.flight_number = meet.flight_number || null;
        this.from = meet.from || null;
        this.text = meet.text || '';
    }

    get getAirports() {
        axios.get('airports').then(result => {
            this.airports = result.data._payload;
        });
    }

    get getStations() {
        axios.get('stations').then(result => {
            this.stations = result.data._payload;
        });
    }

    get getMetros() {
        axios.get('metros').then(result => {
            this.metros = result.data._payload;
        });
    }

    #findWord(word, str) {
        return str.split(' ').some(function (w) {
            return w === word;
        });
    }
}
