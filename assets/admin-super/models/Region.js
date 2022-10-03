/** @format */

import Model from '../base/Model';

export default class Region extends Model {
    static MODE_CREATE = 1;
    static MODE_UPDATE = 2;
    static MODE_DELETE = 0;
    static MODE_CANCEL = -1;

    scope = 'region';

    rules = {
        country_id: { required: true, integer: true, exists: { table: 'countries', col: 'country_id' } },
        iso_2: 'required|string|max:10',
        name: 'required|string|max:255',
    };

    constructor(region = {}) {
        super('regions', 'admin/super');

        this.region_id = region.region_id || null;
        this.country_id = region.country_id || null;
        this.iso_2 = region.iso_2 || undefined;
        this.name = region.name || '';
    }

    setCountry(id) {
        this.country_id = id;
    }

    reset() {
        this.iso_2 = '';
        this.name = '';
    }
}
