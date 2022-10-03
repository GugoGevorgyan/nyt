/** @format */

import Form from '../../base/Form';
import Axios from 'axios';

export default class Login extends Form {
    rules = {
        name: {
            required: true,
            exists: {
                table: 'super_admin',
                col: 'name',
            },
            max: 255,
        },
        password: {
            required: true,
        },
    };

    hidden = ['name'];

    constructor(auth = {}) {
        super();

        this.name = auth.name || '';
        this.password = auth.password || '';
        this.remember = auth.remember || null;
    }

    send() {
        return Axios.post('url', this.build);
    }
}
