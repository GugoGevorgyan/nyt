/** @format */

import axios from 'axios';

export const unique = (value, args) => {
    let route = `/validate/unique?table=${args.table}&col=${args.col}`;
    let data = {};
    data[args.col] = value;
    data['mode'] = args.mode;

    return axios.post(route, data).then(response => response.data);
};

export const exists = (value, args) => {
    let route = `/validate/exists?table=${args.table}&col=${args.col}`;
    let data = {};
    data[args.col] = value;

    return axios.post(route, data).then(response => response.data);
};

export const matchEmail = (value, args) => {
    return axios.post(`/validate/match?email=${value}`, args).then(response => {
        console.log(response);
    });
};

export const checkPassword = value => {
    return axios
        .post('/validate/password', { old_password: value })
        .then(response => {
            if (200 === response.status) {
                return {
                    valid: response.data.valid,
                    data: {
                        message: response.data.message,
                    },
                };
            }
            return {
                valid: false,
                data: {
                    message: 'Server Error!',
                },
            };
        })
        .catch(error => console.log(error));
};

export const isArray = value => {
    return Array.isArray(value);
};

export const isBoolean = value => {
    switch (typeof value) {
        case 'boolean':
            return true;
        case 'string':
            return !!('0' === value || '1' === value);
        case 'number':
            return !!(0 === value || 1 === value);
        default:
            return false;
    }
};

export const isString = value => {
    return value.constructor === String;
};