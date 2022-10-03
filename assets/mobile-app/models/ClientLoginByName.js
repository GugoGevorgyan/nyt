/** @format */
import Form from '../base/Form';

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

    constructor(clientLogin = {}) {
        super();

        this.email = clientLogin.email || '';
        this.password = clientLogin.password || '';
    }
}
