/** @format */

import {serialize} from 'object-to-formdata';
import Model from '../base/Model';

/** @format */

export default class CopyTariff extends Model {
    rules = {
        name: 'required',
        car_class_id: 'required',
        minimal_price: 'required',
        price_min: 'required|numeric',
        price_km: 'required|numeric',
    };

    constructor(copyTariff = {}) {
        super('tariffs', 'admin/super');

        this.tariff_id = copyTariff.tariff_id || null;
        this.tariff_type = copyTariff.tariff_type || null;
        this.name = null;
        this.old_name = copyTariff.name || null;
        this.car_class_id = copyTariff.car_class_id || null;
        this.minimal_price = copyTariff.minimal_price || null;
        this.price_min = (copyTariff.current_tariff && copyTariff.current_tariff.price_min) || null;
        this.price_km = (copyTariff.current_tariff && copyTariff.current_tariff.price_km) || null;
    }

    typeFields() {
        if (this.tariff_type) {
            switch (this.tariff_type.type) {
                case 1:
                    return ['price_min'];
                case 2:
                    return ['price_km'];
                case 3:
                    return ['price_min', 'price_km'];
                default:
                    return [];
            }
        } else {
            return ['price_min', 'price_km'];
        }
    }

    createFormData() {
        let data = {
            name: this.name,
            car_class_id: this.car_class_id,
            minimal_price: this.minimal_price,
        };

        this.typeFields().includes('price_min') ? (data.price_min = this.price_min) : null;
        this.typeFields().includes('price_km') ? (data.price_km = this.price_km) : null;

        return serialize(data, { indices: true });
    }
}
