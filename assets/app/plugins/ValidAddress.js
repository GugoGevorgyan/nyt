/** @format */

import { Validator } from 'vee-validate';
import axios from 'axios';
import state from '../store/state';

const y_address_validate = {
    getMessage(field, args) {
        return 'Неверный или Некорректный адрес';
    },
    validate(value, args) {
        return ymaps.geocode(value).then(res => {
            let obj = res.geoObjects.get(0),
                error,
                hint;

            if (obj) {
                switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                    case 'exact':
                        break;
                    case 'number':
                    case 'near':
                    case 'range':
                        error = 'Неточный адрес, требуется уточнение';
                        hint = 'Уточните номер дома';
                        break;
                    case 'street':
                        error = 'Неполный адрес, требуется уточнение';
                        hint = 'Уточните номер дома';
                        break;
                    case 'other':
                    default:
                        error = 'Неточный адрес, требуется уточнение';
                        hint = 'Уточните адрес';
                }
            } else {
                return false;
            }

            return !error;
        });
    },
};

const company_has_region = {
    getMessage(field, args) {
        return 'Неверный или Некорректный адрес';
    },
    validate(value, args) {
        if (state.order.address_from_coordinates.length > 0) {
            let resultResponse = true;

            axios
                .post('validate/company_region_valid', {
                    company_id: state.order.payment_type_company,
                    from: state.order.address_from_coordinates,
                })
                .then(result => {
                    if (200 === result.data.code) {
                        resultResponse = true;
                    }
                });

            return resultResponse;
        }
        return true;
    },
};

let instance = new Validator();
instance.extend('y_validate', y_address_validate);
instance.extend('company_has_region', company_has_region);
