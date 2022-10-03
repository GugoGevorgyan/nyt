/** @format */

import Model from '../base/Model';
import axios from 'axios';

export default class FranchiseSubPhone extends Model {
    scope = 'franchiseSubPhone';

    rules = {};

    hidden = [];

    constructor(franchiseSubPhone = {}) {
        super('franchise-sub-phones', 'admin/super');

        this.franchise_phone_id = franchiseSubPhone.franchise_sub_phone_id || null;
        this.number = franchiseSubPhone.number || null;
        this.sub_phones = franchiseSubPhone.sub_phones || [];
    }
}
