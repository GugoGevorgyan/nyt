/** @format */

import moment from 'moment';

const order = {
    client_id: null,
    driver_id: null,
    address_from: localStorage.getItem('from') ? localStorage.getItem('from') : '',
    address_to: localStorage.getItem('to') ? localStorage.getItem('to') : '',
    address_from_coordinates: localStorage.getItem('from_c') ? localStorage.getItem('from_c').split(',') : [],
    address_to_coordinates: localStorage.getItem('to_c') ? localStorage.getItem('to_c').split(',') : [],
    phone: '',
    payment_type: 1,
    payment_type_company: null,
    payment_type_card: null,
    car_option: [],
    passenger_phone: '',
    comment: '',
    car_class_id: 0,
    order_time: {
        create_time: moment().format('YYYY-MM-DD HH:mm:ss'),
        time: moment().format('YYYY-MM-DD HH:mm'),
        zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
    meet: {
        is_meet: false,
        type: '',
        transport_id: null,
        number: null,
        text: '',
    },
    is_rent: false,
    rent_time: null
};

const client = {
    client_id: null,
    in_order: false,
    name: '',
    surname: '',
    phone: '',
    companies: {
        id: null,
        name: '',
        car_class: [],
    },
    pay_cards: {
        id: null,
        number: '',
        expiration_date: '',
        cvc_number: '',
    }
};

const driver = {
    driver_id: null,
    car_id: null,
    current_franchise_id: null,
    lat: null,
    lut: null,
    name: '',
    surname: '',
    photo: '',
    phone: '',
};

const orderFeedback = {
    status: false,
    isRating: false,
    isCancelFee: false,
    cancelPrice: 0,
    abortedOrderId: null,
    completedOrderId: null,
    options_id: null,
    assessment: null,
    text: null,
    typeOptions: [],
};

const orderProgress = {
    status: false,
    onAccept: false,
    onWay: false,
    inPlace: false,
    radius: true,
    cancel: true,
    connection: false,
    searchDriverValueView: false,
    callDriver: '',
    showCordContent: true,
    showCord: false,
    text: 'Заказ выполняется',
    searchDriverValue: 0,
    price: '',
    distance: 0,
    duration: 0,
};

const orderForm = {
    open: true,
    priceText: 'Для расчёта заполните поля «Откуда» и «Куда».',
    priceTextDefault: 'Для расчёта заполните поля «Откуда» и «Куда».',
    coin: null,
    currency: 'RUB',
    sitCoin: null,
    sitFee: null,
    initial: null,
    pricePending: false,
    distance: 0,
    time: 0,
    phoneMask: '+#(###)-###-##-##',
    rent_times: []
};

const car = {
    car_id: null,
    car_class_id: null,
    mark: '',
    model: '',
    color: '',
    state_license_plate: '',
};

const maps = {
    map: {},
    from: {},
    to: {},
    from_name: '',
    to_name: '',
    init_from: false,
    init_to: false,
};

const snackbar = {
    text: '',
    show: false,
    color: undefined,
    action: {},
};

const notification = {
    show: false,
    count: 0,
    data: {},
};

const app = {
    fromDisable: false,
    toDisable: false,
    navigateCord: [],
    overlay: true,
    drawer: false,
    broadcast: {},
    orderType: 1,
};

export { order, orderForm, orderFeedback, client, driver, orderProgress, car, maps, snackbar, notification, app };
