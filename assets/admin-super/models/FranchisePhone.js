/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class FranchisePhone extends Model {
    scope = 'franchisePhone';

    rules = {};

    hidden = [];

    constructor(franchisePhone = {}) {
        super('franchise-phones', 'admin/super');

        this.franchise_phone_id = franchisePhone.franchise_phone_id || null;
        this.number = franchisePhone.number || null;
        this.sub_phones = franchisePhone.sub_phones || [];
    }
}
