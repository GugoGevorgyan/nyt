/** @format */

import Vue from "vue";
import Storage from "../../app/facades/Storage";
import moment from "moment";

Vue.filter("storageUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.url(image);
});

Vue.filter("publicUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.publicUrl(image);
});

Vue.filter("formatDate", function (value) {
    return moment(value).format('DD-MM-YYYY');
});

Vue.filter("formTime", function (value) {
    let date = new Date(value);
    return `${date.getHours()}:${9 >= date.getMinutes() ? "0" + date.getMinutes() : date.getMinutes()} `;
});
