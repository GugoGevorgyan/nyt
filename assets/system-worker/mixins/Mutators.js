/** @format */

import moment from "moment-timezone";

export const mutators = {
    methods: {
        /*date*/
        __getPreorderDate(preorder) {
            let timeOrder = moment().tz(preorder.time_zone).format("DD-MM-YYYY HH:mm:ss");
            let timeLocal = moment().format("DD-MM-YYYY HH:mm:ss");

            let dt1 = new Date(timeOrder);
            let dt2 = new Date(timeLocal);

            let minutesDiff = (dt1.getTime() - dt2.getTime()) / 60000;

            let localStartTme = moment(new Date(preorder.distribution_start))
                .add(minutesDiff, "m")
                .format("YYYY-MM-DD HH:mm:ss");

            return {
                started: new Date() > Date.parse(localStartTme),
                time: localStartTme,
            };
        },
        __isToday(someDate) {
            let today = new Date();
            return (
                someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear()
            );
        },
        __getDateShorted(date) {
            let itemDate = new Date(date);
            return this.__isToday(itemDate)
                ? moment(itemDate).format("HH:mm")
                : moment(itemDate).format("DD/MMM/YYYY") + " " + moment(itemDate).format("HH:mm");
        },
        __getTime(date) {
            return moment(new Date(date)).format("HH:mm");
        },

        /*join*/
        __commaJoin(arr, key) {
            let values = [];
            arr.forEach(item => {
                values.push(item[key]);
            });
            return values.join(", ");
        },

        /*price*/
        __priceFormat(price) {
            return "₽ " + new Intl.NumberFormat("de-DE").format(price);
        },

        /*assessment*/
        __assessmentColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },
    },
};
