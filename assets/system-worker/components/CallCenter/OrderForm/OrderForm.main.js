/** @format */

import MultiModel from "../../../base/MultiModel";
import Snackbar from "../../../facades/Snackbar";
import PassengerDialog from "./PassengerDialog";
import Route from "../../../models/Route";
import DateTime from "../../../../shared/components/form/DateTime";
import moment from "moment";

export default {
    components: { "passenger-dialog": PassengerDialog, "v-datetime-picker": DateTime },

    props: {
        client: {
            required: true,
        },
        height: {
            required: true,
        },
        tab: {
            required: true,
        },
        order: {
            required: true,
        },
        meet: {
            required: true,
        },
        passenger: {
            required: true,
        },
        carClasses: {
            required: true,
        },
        carOptions: {
            required: true,
        },
        paymentMethods: {
            required: true,
        },
        clientCompanies: {
            required: true,
        },
        metros: {
            required: true,
        },
        stations: {
            required: true,
        },
        airports: {
            required: true,
        },
        socketData: {
            required: true,
        },
    },

    data() {
        return {
            addMinute: 0,
            preOrderMinutesDisabled: false,
            form: null,

            map: null,
            placeMarks: [],
            carGeoObjects: null,
            markGeoObjects: null,
            route: null,

            loading: false,
            priceLoading: false,
            carClass: this.carClasses,
            rentTimes: [4, 6, 8, 12],
            nowTime: null,
            thisTab: 1,
            meetTab: 0,
            companies: this.clientCompanies || [],
            findCompany: null,
            findCompanyCode: null,
            findCompanyLoading: false,
            passengerDialog: false,
        };
    },

    watch: {
        clientCompanies() {
            this.companies = this.clientCompanies || [];
        },
        payCompany() {
            if (this.payCompany) {
                this.clearPassenger();
            } else {
                this.order.company_id = null;
            }
        },
        findCompanyCode() {
            if (this.findCompanyCode && 4 <= this.findCompanyCode.length && 8 > this.findCompanyCode.length) {
                this.findCompaniesByCode();
            } else {
                this.findCompany = null;
            }
        },
        findCompany() {
            this.order.company_id = this.findCompany ? this.findCompany.company_id : null;
        },
        addMinute(val) {
            this.order.start_time = moment().add(val, "minutes").format("YYYY-MM-DD HH:mm");
        },
        "order.start_time": function (val) {
            if (this.order.address_from) {
                this.getPrice();
            }
        },
        "order.address_from": {
            deep: true,
            handler() {
                if (!this.order.displayFrom) {
                    this.order.displayFrom = this.order.address_from;
                }
                if (!this.order.address_from || !this.order.address_from.trim().length) {
                    this.order.from_coordinates = null;
                    this.clearErrFrom();
                    this.removeDriverMarks();
                }
            },
        },
        "order.address_to": {
            deep: true,
            handler() {
                if (!this.order.displayTo) {
                    this.order.displayTo = this.order.address_to;
                }
                if (!this.order.address_to || !this.order.address_to.trim().length) {
                    this.order.to_coordinates = null;
                    this.clearErrTo();
                }
            },
        },
        "order.from_coordinates": function () {
            this.getPrice();
            this.setRoute();
        },
        "order.to_coordinates": function () {
            this.getPrice();
            this.setRoute();
        },
        "order.payment_type_id": function () {
            this.getPrice();
        },
        "order.company_id": function () {
            this.getPrice();
        },
        "order.car_class_id": function () {
            this.getPrice();
        },
        "order.car_option": function () {
            let meetOption = this.carOptions.find(item => "meet" === item.value);
            if (meetOption) {
                this.order.is_meet = !!this.order.car_option.find(item => item === meetOption.car_option_id);
            }
            this.getPrice();
        },
        "order.rent_time": function () {
            if (this.order.address_from) {
                this.getPrice();
            }
        },
        "order.is_rent": function (val) {
            if (val) {
                if (this.order.address_from && this.order.address_from) {
                    this.getPrice();
                }
            } else {
                if (this.order.address_from) {
                    this.getPrice();
                }
            }
        },
        "order.client_id": function () {
            this.map = null;
            if (this.order.client_id) {
                ymaps.ready(this.initSuggest);
            }
        },
        "order.is_meet": function () {
            this.meetOption(this.order.is_meet);
        },
        "meet.airport_id": function () {
            if (this.meet.airport_id) {
                this.meet.railway_station_id = null;
                this.meet.metro_id = null;
                let meetPlace = this.airports.find(item => {
                    return item.airport_id === this.meet.airport_id;
                });
                this.setMeetAddress(meetPlace);
            }
        },
        "meet.railway_station_id": function () {
            if (this.meet.railway_station_id) {
                this.meet.airport_id = null;
                this.meet.metro_id = null;
                let meetPlace = this.stations.find(item => {
                    return item.railway_station_id === this.meet.railway_station_id;
                });
                this.setMeetAddress(meetPlace);
            }
        },
        "meet.metro_id": function () {
            if (this.meet.metro_id) {
                this.meet.airport_id = null;
                this.meet.railway_station_id = null;
                let meetPlace = this.metros.find(item => {
                    return item.metro_id === this.meet.metro_id;
                });
                this.setMeetAddress(meetPlace);
            }
        },
        "socketData.radiusDriver": {
            deep: true,
            handler() {
                this.removeDriverMarks();

                for (let [key, driver] of Object.entries(this.socketData.radiusDriver)) {
                    this.addInGeoObject(driver);
                }

                this.map.geoObjects.add(this.carGeoObjects);
            },
        },
    },

    computed: {
        errFrom() {
            return this.thisTab === this.tab && this.order.errFrom.value;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                localStorage.setItem("color_mode", mode);
                this.$store.state.dark = mode;
            },
        },
        errTo() {
            return this.thisTab === this.tab && this.order.errTo.value;
        },

        today() {
            return new Date().toISOString();
        },

        targets() {
            let result = [];
            this.order.from_coordinates
                ? result.push(this.order.from_coordinates.lat + " " + this.order.from_coordinates.lut)
                : null;
            this.order.to_coordinates
                ? result.push(this.order.to_coordinates.lat + " " + this.order.to_coordinates.lut)
                : null;
            return result;
        },

        payCompany() {
            if (this.order.payment_type_id && this.paymentMethods.length) {
                let payment = this.paymentMethods.find(item => {
                    return item.payment_type_id === this.order.payment_type_id;
                });
                return 2 === payment.type;
            } else {
                return false;
            }
        },

        contentHeight() {
            return this.height - 72;
        },

        passengerText() {
            return this.passenger.name || this.passenger.surname || this.passenger.patronymic
                ? `${this.passenger.surname ? this.passenger.surname : ""}${
                      this.passenger.name ? " " + this.passenger.name : ""
                  }${this.passenger.patronymic ? " " + this.passenger.patronymic : ""},<br>
                    Телефон: ${this.passenger.phone}`.trim()
                : `Телефон: ${this.passenger.phone}`;
        },
    },

    methods: {
        /*passenger*/
        clearPassenger() {
            this.order.is_passenger = false;
            this.passenger.client_id = null;
            this.passenger.name = null;
            this.passenger.surname = null;
            this.passenger.patronymic = null;
            this.passenger.phone = null;
        },
        /*------------------------------*/

        /**
         * ClientMessage View Taxi radius 1000M after open Page
         */
        removeDriverMarks() {
            if (this.carGeoObjects) {
                this.carGeoObjects.removeAll();
            }

            this.carGeoObjects = new ymaps.GeoObjectCollection();
        },

        addInGeoObject(driver) {
            let placeMark = this.createDriverMark(driver);
            this.placeMarks.push({ driver: driver, placeMark: placeMark });
            this.carGeoObjects.add(placeMark);
        },

        createDriverMark: function (driver) {
            return new ymaps.Placemark(
                [driver.current_coordinate.lat, driver.current_coordinate.lut],
                {
                    rotation: driver.azimuth,
                    driverId: driver.driver_id,
                    balloonContent: `
                        <div>
                            ФИ: ${driver.name} ${driver.surname}
                            <br />
                            телефон: ${driver.phone}
                            <br />
                            состояние: <span style="height: 10px;width: 10px;  background-color: #1ebb18;border-radius: 50%;display: inline-block;"></span>
                        </div>
                    `,
                },
                {
                    iconLayout: "default#image",
                    iconRotate: driver.azimuth,
                    iconImageHref: "/storage/img/taxi/transport.svg",
                    iconImageSize: [50, 43],
                    draggable: false,
                    visible: true,
                },
            );
        },

        /*map*/
        suggestFrom: function (request, options) {
            delete options["provider"];
            return ymaps.suggest(request, options).then(items => {
                items.length ? this.clearErrFrom() : this.setErrFrom();
                return ymaps.vow.resolve(items);
            });
        },

        suggestTo: function (request, options) {
            delete options["provider"];
            return ymaps.suggest(request, options).then(items => {
                items.length ? this.clearErrTo() : this.setErrTo();
                return ymaps.vow.resolve(items);
            });
        },

        initSuggest() {
            let fromView = new ymaps.SuggestView("from-address", {
                provider: { suggest: (...args) => this.suggestFrom(...args) },
            });
            fromView.events.add("select", e => {
                this.order.address_from = e.get("item").value;
                this.order.displayFrom = e.get("item").displayName.replace(/,[^,]+$/, "");
                this.getFromCoords();
                fromView.destroy();
            });

            let toView = new ymaps.SuggestView("to-address", {
                provider: { suggest: (...args) => this.suggestTo(...args) },
            });
            toView.events.add("select", e => {
                this.order.address_to = e.get("item").value;
                this.order.displayTo = e.get("item").displayName.replace(/,[^,]+$/, "");
                this.getToCoords();
                toView.destroy();
            });

            if (this.order.from_coordinates && this.order.from_coordinates.length && this.order.to_coordinates.length) {
                this.setRoute();
            } else if (this.order.from_coordinates.length || this.order.to_coordinates.length) {
                let id = this.order.from_coordinates ? "A" : "B";
                let mark = this.createMark(id, this.order.from_coordinates);
                this.addMarkGeoObject(id, mark);
            }
        },

        addMarkGeoObject(id, mark) {
            if (!this.markGeoObjects) {
                this.markGeoObjects = new ymaps.GeoObjectCollection();
            }
            let marks = this.markGeoObjects.add(mark);
            this.placeMarks.push({ id: id, mark: mark });
            this.map.geoObjects.add(marks);
        },

        createMark(id, coords) {
            return new ymaps.Placemark(
                [coords["lat"], coords["lut"]],
                { id: id },
                {
                    preset: id === "A" ? "islands#redInfoIcon" : "islands#blueHomeIcon",
                    draggable: false,
                    zIndex: 99999,
                },
            );
        },

        setErrFrom() {
            if (this.order.address_from && this.order.address_from.trim()) {
                this.order.errFrom = {
                    value: true,
                    msg: "Адрес не действителен!",
                };
            }
        },
        clearErrFrom() {
            this.order.errFrom = {
                value: false,
                msg: null,
            };
        },

        setErrTo() {
            this.order.errTo = {
                value: true,
                msg: "Адрес не действителен!",
            };
        },

        clearErrTo() {
            this.order.errTo = {
                value: false,
                msg: null,
            };
        },

        setRoute() {
            if (this.map) {
                let self = this;

                if (this.route) {
                    this.map.geoObjects.removeAll();
                }

                this.route = new ymaps.multiRouter.MultiRoute(
                    {
                        referencePoints: self.targets,
                    },
                    {
                        wayPointStartIconColor: "#FFFFFF",
                        wayPointStartIconFillColor: "#00C853",
                        routeActiveStrokeWidth: 4,
                        routeActiveStrokeStyle: "solid",
                        routeActiveStrokeColor: "#16791f",
                        routeStrokeStyle: "dot",
                        routeStrokeWidth: self.targets.length,
                        boundsAutoApply: true,
                    },
                );
                this.map.geoObjects.add(self.route);
            }
        },

        makeOrder() {
            if (!this.order.from_coordinates) {
                this.setErrFrom();
            }

            this.$validator.validate().then(valid => {
                if (valid && !this.order.errFrom.value && !this.order.errTo.value) {
                    let route = new Route({ from: [this.order.from_coordinates.lat, this.order.from_coordinates.lut] });
                    this.loading = true;
                    this.form = new MultiModel([route, this.order, this.passenger, this.meet]);
                    this.loading = true;
                    this.form
                        .send(`call-center/order/create`)
                        .then(response => {
                            this.loading = false;
                            this.$emit("created");
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                } else {
                    Snackbar.error("Данные не полноценны");
                }
            });
        },

        getFromCoords() {
            if (this.order.address_from && this.order.address_from.trim()) {
                this.order.loadingFromCoordinates = true;
                this.$http
                    .get(`call-center/get-coordinates/${encodeURIComponent(this.order.address_from.trim())}`)
                    .then(response => {
                        this.order.loadingFromCoordinates = false;
                        this.order.from_coordinates = this.coordsProcessing(response.data.coords);
                        this.clearErrFrom();
                    })
                    .catch(() => {
                        this.setErrFrom();
                        this.order.loadingFromCoordinates = false;
                    });
            }
        },

        getToCoords() {
            if (this.order.address_to && this.order.address_to.trim()) {
                this.priceLoading = true;
                this.loadingToCoordinates = true;
                this.$http
                    .get(`call-center/get-coordinates/${encodeURIComponent(this.order.address_to.trim())}`)
                    .then(response => {
                        this.order.loadingToCoordinates = false;
                        this.priceLoading = false;
                        this.order.to_coordinates = this.coordsProcessing(response.data.coords);
                        this.clearErrTo();
                    })
                    .catch(() => {
                        this.order.loadingToCoordinates = false;
                        this.priceLoading = false;
                        this.setErrTo();
                    });
            }
        },

        coordsProcessing(coords) {
            return {
                lat: coords[0] ?? coords["lat"],
                lut: coords[1] ?? coords["lut"],
            };
        },
        /*________________________*/

        /*requests*/
        setMeetAddress(place) {
            this.order.loadingFromCoordinates = false;
            this.order.from_coordinates = { lat: place.lat, lut: place.lut };
            this.order.address_from = place.address;
        },

        getPriceData() {
            if (
                this.order.from_coordinates &&
                this.order.address_from &&
                this.order.payment_type_id &&
                this.order.client_id
            ) {
                return {
                    client_id: this.order.client_id,
                    is_rent: this.order.is_rent,
                    rent_time: this.order.rent_time,
                    payment: {
                        type: this.order.payment_type_id,
                        company: this.order.company_id,
                    },
                    route: {
                        from_address: this.order.address_from,
                        to_address: this.order.address_to,
                        from: [this.order.from_coordinates.lat, this.order.from_coordinates.lut],
                        to: this.order.to_coordinates
                            ? [this.order.to_coordinates.lat, this.order.to_coordinates.lut]
                            : null,
                    },
                    car: {
                        options: this.order.car_option,
                        comment: this.order.comments,
                        class: this.order.car_class_id,
                    },
                    time: {
                        create_time: this.order.create_time,
                        time: this.order.start_time,
                        zone: this.order.time_zone,
                    },
                };
            }
        },

        getPrice() {
            let data = this.getPriceData();

            if (data) {
                this.priceLoading = true;
                this.$http
                    .post("call-center/get-order-price", data)
                    .then(response => {
                        this.carClass = response.data;

                        response.data.map(item => {
                            if (item.class_id === data.car.class || item.car_class_id === data.car.class) {
                                this.order.price = item.coin;
                                this.rentTimes = item.rent_times;
                            }
                        });

                        this.priceLoading = false;
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.priceLoading = false;
                    });
            } else {
                this.order.price = null;
            }
        },

        findCompaniesByCode() {
            this.findCompanyLoading = true;
            this.$http
                .post("call-center/find-companies", { code: this.findCompanyCode })
                .then(response => {
                    this.findCompanyLoading = false;
                    this.findCompany = response.data;
                })
                .catch(error => {
                    this.findCompanyLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        /*________________________*/

        /*meet option*/
        meetOption(value) {
            let meetOption = this.carOptions.find(item => "meet" === item.value);
            if (meetOption) {
                if (value) {
                    if (!this.order.car_option.find(item => item === meetOption.car_option_id)) {
                        this.order.car_option.push(meetOption.car_option_id);
                    }
                } else {
                    let index = this.order.car_option.findIndex(item => item === meetOption.car_option_id);
                    if (~index) {
                        this.order.car_option.splice(index, 1);
                    }
                }
            }
        },

        initMap() {
            this.map = new ymaps.Map("order-map", {
                center: [55.73, 37.75],
                zoom: 10,
                controls: [],
            });
        },

        clearToField() {
            this.order.displayTo = "";
            this.order.address_to = "";
            this.order.to_coordinates = [];
            ymaps.ready(this.initSuggest);
        },

        clearFromField() {
            this.order.displayFrom = "";
            this.order.address_from = "";
            this.order.from_coordinates = [];
            this.removeDriverMarks();
            ymaps.ready(this.initSuggest);
        },
    },

    mounted() {
        if (this.order.client_id) {
            ymaps.ready(this.initMap);
            ymaps.ready(this.initSuggest);
        }
    },
};
