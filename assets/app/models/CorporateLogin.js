/** @format */

import Form from './Form';

export default class CorporateLogin extends Form {
    email = '';
    password = '';

    rules = {
        email: {
            required: true,
            max: 64,
            min: 5,
            exists: {
                table: 'admin_corporates',
                col: 'email',
            },
        },
        password: {
            required: true,
            max: 64,
            min: 5,
        }
    };

    /**
     * @param corporateLogin
     */
    constructor(corporateLogin = {}) {
        super();

        this.email = corporateLogin.email || '';
        this.password = corporateLogin.password || '';
    }
}
