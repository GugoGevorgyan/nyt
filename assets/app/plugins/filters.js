/** @format */

import Vue from "vue";
import Storage from "../facades/Storage";
import { VueMaskFilter } from "v-mask";

Vue.filter("storageUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.url(image);
});

Vue.filter("publicUrl", function (value, path) {
    let image = `${path}/${value}`;
    return Storage.publicUrl(image);
});

Vue.filter("formatAdd", function (string, rem = 1) {
    return string.split(", ").slice(rem).join(", ");
});

Vue.filter("VMask", VueMaskFilter);
