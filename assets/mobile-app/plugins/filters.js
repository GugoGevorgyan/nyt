/** @format */

import Vue from 'vue';
import Storage from '../facades/Storage';
import moment from 'moment';

Vue.filter('storageUrl', function (value, path) {
    let image = `${path}/${value}`;
    return Storage.url(image);
});

Vue.filter('publicUrl', function (value, path) {
    let image = `${path}/${value}`;
    return Storage.publicUrl(image);
});

Vue.filter('momento', function (value, format = 'MM/D/YY, h:mm') {
    return moment(date).format(format);
});
