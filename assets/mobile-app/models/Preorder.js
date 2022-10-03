/** @format */

import $http from 'axios';
import Model from '../base/Model';

export default class Preorder extends Model {
    contentLoad = false;

    constructor() {
        super('mobile');
    }

    get preorders() {
        return $http.get('get_preorders');
    }

    deletePreorder(order_id) {
        return $http.delete(`preorder/${order_id}`);
    }
}
