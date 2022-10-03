/** @format */

import { mapMutations, mapState } from 'vuex';
import Vue from 'vue';

export const Map = Vue.util.mergeOptions('', {
    data() {
        return {
            marks: [],
            placemarks: [],
            geoObjects: false,
            carGeoObjects: false,
        };
    },

    computed: {
        ...mapState(['maps']),
    },

    methods: {
        ...mapMutations(['initMap']),

        createDriverMark: function (driver) {
            return new ymaps.Placemark(
                [driver.current_coordinate.lat, driver.current_coordinate.lut],
                {
                    driverId: driver.driver_id,
                    balloonContent: `
                        <div>
                            name: ${driver.name} ${driver.surname}
                            <br />
                            phone: ${driver.phone}
                            <br />
                            status: <span style='height: 10px;width: 10px;  background-color: #1ebb18;border-radius: 50%;display: inline-block;'></span>
                        </div>
                    `,
                },
                {
                    iconLayout: 'default#image',
                    iconRotate: this.cornerDegree,
                    iconImageHref: '/storage/img/taxi/taxi_2.svg',
                    iconImageSize: [35, 42],
                    draggable: false,
                    visible: true,
                },
            );
        },

        parseCoinResult(data) {
            let message = "";

            let index = data.findIndex((item, index) => {
                if (item.car_class_id === this.carClassPrice) {
                    this.$emit('demand-change', item.car_options)
                    return index;
                }

                if (item.car_class_id === item.selected) {
                    return index;
                }
            });

            if (-1 === index) {
                index = 0;
            }

            if (data[index].initial && data[index].sitting_fee) {
                message = `Цена за поездку от ${data[index].coin} ${data[index].currency}`;
            }
            if (!data[index].initial && data[index].sitting_fee) {
                message = `Цена за поездку ${data[index].coin} ${data[index].currency}`;
            }
            if (!data[index].initial && !data[index].sitting_fee) {
                message = `Цена за поездку ${data[index].coin} ${data[index].currency}`;
            }
            if (data[index].initial && !data[index].sitting_fee) {
                message = `Цена за поездку от ${data[index].coin} ${data[index].currency}`;
            }
            let rent_times = data[index].rent_times.map(item => {
                return { id: item, name: item + " ч." };
            });

            if (this.order.address_from) {
                this.initOrderForm({
                    carClasses: data,
                    priceText: message,
                    rent_times: rent_times,
                    coin: data[index].coin,
                    time: data[index].time,
                    initial: data[index].initial,
                    distance: data[index].distance,
                    sitFee: data[index].sitting_fee,
                    sitCoin: data[index].sitting_coin,
                });
            }
            if (-1 === Object.values(data[index].rent_times).indexOf(this.order.rent_time)) {
                this.order.rent_time = rent_times[0].id;
            }
        },

        parseCoordinate(cords) {
            // if (cords.) {
            // }
        },

        /**
         * @param response
         * @returns {[]}
         */
        errorHandler(response) {
            if (!response) {
                return [];
            }

            let err = [],
                field = '',
                msg = '';

            if (422 === response.status) {
                let errors = response.data.errors;

                for (let key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        field = key;
                        msg = errors[key][0];

                        err.push({ field, msg });
                    }
                }
            }

            return err;
        },

        /**
         * ClientMessage View Taxi radius 3000M after open Page
         */
        removeDriverMarks() {
            if (false !== this.carGeoObjects) {
                this.carGeoObjects.removeAll();
            }

            this.carGeoObjects = new ymaps.GeoObjectCollection();
        },

        createUpdateDriver(driver) {
            let index = this.placemarks.findIndex(item => {
                return item.driver.driver_id === driver.driver_id;
            });

            if (-1 === index) {
                this.hasGeoObjectsAddDriver(driver);
            } else {
                this.carGeoObjects.splice(index, 1, this.createDriverMark(driver));
            }
        },

        /**
         * Add to geo object
         *
         * @param driver
         */
        addInGeoObject(driver) {
            let placeMark = this.createDriverMark(driver);

            this.placemarks.push({ driver: driver, placeMark: placeMark });

            this.carGeoObjects.add(placeMark);
        },

        /**
         * ClientMessage View Taxi radius 3000M after open Page
         *
         * @param driver
         */
        deleteDriver(driver) {
            let index = this.placemarks.findIndex(item => {
                return item.driver.driver_id === driver.driver_id;
            });

            if (-1 !== index) {
                this.carGeoObjects.splice(index, 1);
            }
        },
    },
});
