/** @format */

import moment from 'moment';
import { app, orderFeedback, order, orderForm, client, driver, orderProgress, car, maps, snackbar, notification } from './initial';

export default {
    // MULTI DATA
    app: app,
    client: client,
    order: order,
    driver: driver,
    car: car,
    maps: maps,
    orderForm: orderForm,
    snackbar: snackbar,
    orderFeedback: orderFeedback,
    orderProgress: orderProgress,
    notification: notification,

    // SINGLE DATA
    overlay: true,
    today: moment(),
    date: null,
    time: null,
    drawer: false,
    broadcast: {},
};
