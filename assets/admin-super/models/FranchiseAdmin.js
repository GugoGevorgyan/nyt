/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class FranchiseAdmin extends Model {
    scope = 'franchiseAdmin';

    nicknameLoading = false;
    emailLoading = false;

    rules = {
        name: 'required|string|min:3|max:36',
        surname: 'string|min:3|max:36',
        patronymic: 'string|min:3|max:36',
        nickname: {
            required: true,
            string: true,
            min: 3,
            max: 36,
        },
        email: {
            string: true,
            email: true,
            max: 255,
        },
        phone: 'string|max:255',
        password: 'required|min:8|max:100',
    };

    hidden = ['nicknameLoading', 'emailLoading'];

    constructor(franchiseAdmin = {}) {
        super('franchise-admins', 'admin/super');

        this.system_worker_id = franchiseAdmin.system_worker_id || null;
        this.franchise_id = franchiseAdmin.franchise_id || null;
        this.name = franchiseAdmin.name || null;
        this.surname = franchiseAdmin.surname || null;
        this.patronymic = franchiseAdmin.patronymic || null;
        this.nickname = franchiseAdmin.nickname || null;
        this.email = franchiseAdmin.email || null;
        this.phone = franchiseAdmin.phone || null;
        this.change_password = franchiseAdmin.change_password || false;
        this.password = franchiseAdmin.password || null;
    }

    data() {
        return {
            name: this.name,
            surname: this.surname,
            patronymic: this.patronymic,
            nickname: this.nickname,
            email: this.email,
            phone: this.phone,
            password: this.password,
        };
    }

    checkUniqueNickname() {
        let data = {
            table: 'system_workers',
            col: 'nickname',
            id: this.system_worker_id,
            idCol: 'system_worker_id',
            nickname: this.nickname,
        };

        return axios.post('/validate/custom-unique', data);
    }

    checkUniqueEmail() {
        let data = {
            table: 'system_workers',
            col: 'email',
            id: this.system_worker_id,
            idCol: 'system_worker_id',
            email: this.email,
        };

        return axios.post('/validate/custom-unique', data);
    }
}
