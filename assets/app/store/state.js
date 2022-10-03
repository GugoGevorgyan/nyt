/** @format */
import moment from 'moment';
import {
    snackbar,
    maps,
    orderProgress,
    orderForm,
    client,
    driver,
    order,
    orderFeedback,
    transports,
    car,
    orderRepeat,
} from './initial';

export default {
    client: client,
        order: order,
        driver: driver,
        car: car,
        maps: maps,
        orderForm: orderForm,
        snackbar: snackbar,
        orderFeedback: orderFeedback,
        orderRepeat: orderRepeat,
        orderProgress: orderProgress,
        transports: transports,

        orderStatuses: [],
        orderTypes: [],
        inOrder: false,
        inOrderData: [],
        responseOrderData: {},
        today: moment(),
        date: null,
        time: null,
        broadcast: {},
        notification: {
            show: false,
            count: 0,
            data: {},
    },
    clientInOrder: {
        status: undefined,
        order: {},
        driver: {},
    },

    app: {
        navigateCord: [],
        paymentDialog: false,
    },
    };
