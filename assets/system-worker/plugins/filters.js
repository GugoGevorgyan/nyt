/** @format */

import Vue from "vue";
import Storage from "../../app/facades/Storage";
import moment from "moment";
import { VueMaskFilter } from "v-mask";

Vue.filter("storageUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.url(image);
});

Vue.filter("publicUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.publicUrl(image);
});

Vue.filter("formatDate", function (value) {
    return moment(value).format("DD-MM-YYYY");
});

Vue.filter("formatAdd", function (string, rem = 1) {
    let parseString = string.split(", ").slice(rem).join(", ");

    if (!parseString.length) {
        return string;
    }

    return parseString;
});

Vue.filter("formTime", function (value) {
    let date = new Date(value);
    return `${date.getHours()}:${("0" + date.getMinutes()).slice(-2)} `;
});

Vue.filter("formatDateTime", function (value) {
    return moment(value).format("DD-MM-YYYY hh:mm");
});

Vue.filter("VMask", VueMaskFilter);
