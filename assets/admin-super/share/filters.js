/** @format */

import Vue from 'vue';
import Storage from '../../app/facades/Storage';

Vue.filter('storageUrl', function (value, path) {
    let image = `${path}/${value}`;
    return Storage.url(image);
});

Vue.filter('publicUrl', function (value, path) {
    let image = `${path}/${value}`;
    return Storage.publicUrl(image);
});
