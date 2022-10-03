/** @format */

import Model from '../base/Model';

/** @format */

export default class Destination extends Model {
    constructor(dest = {}) {
        super('tariff', 'app/worker', '', {
            update: 'edit',
            delete: '../tariff',
            deletes: 'deletes',
        });

        this.destination_id = dest.destination_id || null;
        this.destination_area_id = dest.destination_area_id || null;
        // this.name = dest.name || "";
        this.address_from = dest.address_from || '';
        this.address_to = dest.address_to || '';
        this.free_wait = dest.free_wait || '';
        this.paid_wait = dest.paid_wait || '';
        this.car_class_id = dest.car_class_id || null;
        this.price = dest.price || null;
        // this.distance = dest.distance || null;
        // this.duration = dest.duration || null;
        // this.fromCoordinates = dest.fromCoordinates || []; // [40.5, 50.4] => { lat: fromCoordinates[0], lut: fromCoordinates[1] }
        // this.toCoordinates = dest.toCoordinates || [];
        this.locations = dest.locations || { from: [], to: [] };
    }
}
