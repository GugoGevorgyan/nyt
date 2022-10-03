/** @format */

import {serialize} from 'object-to-formdata';

export default class Area {
    rules = {
        title: 'required',
        description: '',
        area: 'required',
    };

    constructor(area = {}) {
        this.area_id = area.area_id || null;
        this.title = area.title || null;
        this.description = area.description || null;
        this.area = area.area || [];
    }

    createFormData() {
        let data = {
            title: this.title,
            description: this.description,
            area: this.area,
        };
        return serialize(data, { indices: true });
    }
}
