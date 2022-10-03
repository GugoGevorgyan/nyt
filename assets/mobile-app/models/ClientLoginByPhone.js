/** @format */

import $http from 'axios';
import Form from '../base/Form';

export default class ClientLoginByPhone extends Form {
    rules = {
        phone: {
            required: true,
            min: 17,
            max: 17,
        },
        accept_code: {
            required: true,
            max: 6,
            min: 6,
        },
    };

    /**
     * @param clientLogin
     */
    constructor(clientLogin = {}) {
        super();

        this.phone = clientLogin.phone || '';
        this.acceptCode = clientLogin.acceptCode || '';
    }

    /**
     * @returns {Promise<AxiosResponse<T>>}
     */
    get sendSmsAcceptCode() {
        return $http.post('/send_sms_code_login', { phone: this.phone });
    }

    /**
     * @returns {Promise<AxiosResponse<T>>}
     */
    get sendPhoneAcceptCode() {
        return $http.post('/validate/accept-code', { phone: this.phone, accept_code: this.acceptCode });
    }
}
