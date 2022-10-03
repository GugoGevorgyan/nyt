/** @format */

import Form from './Form';

export default class ClientLoginByPhone extends Form {
    email = '';
    password = '';

    rules = {
        email: {
            required: true,
            max: 36,
            min: 3,
            exists: {
                table: 'clients',
                col: 'email',
            },
        },
        password: {
            required: true,
            max: 64,
            min: 6,
        },
    };

    register(recaptchaToken) {
        axios.post(this.loginClientByName, {
            email: this.email,
            password: this.password,
            captcha_token: recaptchaToken,
        });
    }

    constructor(clientLogin = {}) {
        super();

        this.email = clientLogin.email || '';
        this.password = clientLogin.password || '';
    }
}
