/** @format */

import moment from "moment";

const order = {
    client_id: null,
    driver_id: null,
    address_from: "",
    address_to: "",
    address_from_coordinates: localStorage.getItem("from_c") ? localStorage.getItem("from_c").split(",") : [],
    address_to_coordinates: localStorage.getItem("to_c") ? localStorage.getItem("to_c").split(",") : [],
    phone: "",
    payment_type: 1,
    payment_type_company: null,
    payment_type_card: null,
    car_option: [],
    passenger_phone: "",
    comment: "",
    car_class_id: 1,
    order_time: {
        create_time: moment().format("YYYY-MM-DD HH:mm:ss"),
        time: moment().format("YYYY-MM-DD HH:mm"),
        zone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        repeated_at: ''
    },
    meet: {
        is_meet: false,
        type: "",
        transport_id: null,
        number: null,
        text: "",
    },
    is_rent: false,
    rent_time: null,
};

const client = {
    client_id: null,
    in_order: false,
    name: "",
    surname: "",
    phone: "",
    companies: {
        id: null,
        name: "",
        car_class: [],
    },
    pay_cards: {
        id: null,
        number: "",
        expiration_date: "",
        cvc_number: "",
    },
};

const driver = {
    driver_id: null,
    car_id: null,
    current_franchise_id: null,
    lat: null,
    lut: null,
    name: "",
    surname: "",
    photo: "",
    phone: "",
};

const car = {
    car_id: null,
    driver_id: null,
    class: "",
    surname: "",
    mark: "",
    model: "",
    color: "",
    sts_number: "",
    license_plate: "",
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

const orderRepeat = {
    status: false,
    continue: false,
    cancel: false,
    title: "",
}

const orderProgress = {
    status: false,
    radius: true,
    pending: false,
    accept: false,
    onWay: false,
    inPlace: false,
    started: false,
    cancel: false,
    text: "",
    minute: "",
    searchDriverValue: 0,
    features: {
        free_wait_minute: '',
        paid_wait_minute: '',
    }
};

const orderForm = {
    open: false,
    priceText: "Для расчёта заполните поля «Откуда» и «Куда».",
    priceTextDefault: "Для расчёта заполните поля «Откуда» и «Куда».",
    optionsPrice: 0,
    coin: null,
    currency: "RUB",
    sitCoin: null,
    sitFee: null,
    initial: null,
    pricePending: false,
    distance: 0,
    time: 0,
    phoneMask: localStorage.getItem('mask'),
    demands: [],
    carClasses: [],
    paymentMethods: [],
    rent_times: [],
    displayFrom: localStorage.getItem("from") ? localStorage.getItem("from") : "",
    displayTo: localStorage.getItem("to") ? localStorage.getItem("to") : "",
    disablePreorder: false
};

const maps = {
    map: {},
    from: {},
    to: {},
    from_name: "",
    to_name: "",
    init_from: false,
    init_to: false,
};

const transports = {
    metros: {
        metro_id: null,
        name: "null",
        coordinate: {},
    },
    airports: {
        airport_id: null,
        name: "null",
        coordinate: {},
    },
    stations: {
        railway_station_id: null,
        name: "null",
        coordinate: {},
    },
};

const snackbar = {
    text: "",
    show: false,
    color: undefined,
    action: {},
};

export { order, orderForm, orderFeedback, client, driver, orderProgress, maps, transports, snackbar, car, orderRepeat };
