/** @format */

import axios from "axios";
import { mapMutations, mapState } from "vuex";

export default {
    computed: {
        ...mapState(["maps", "client"]),
    },

    methods: {
        ...mapMutations(["initMap"]),

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
                            status: <span style="height: 10px;width: 10px;  background-color: #1ebb18;border-radius: 50%;display: inline-block;"></span>
                        </div>
                    `,
                },
                {
                    iconLayout: "default#image",
                    iconRotate: driver.azimuth,
                    iconImageHref: "/storage/img/taxi/transport.svg",
                    iconImageSize: [45, 38],
                    draggable: false,
                    visible: true,
                },
            );
        },

        fromCoordinates(val, inMkad = false, dragged = false) {
            this.maps.map.geoObjects.remove(this.maps.from);

            if (inMkad) {
                let mark = new ymaps.Placemark(val, { iconContent: "A" }, { draggable: dragged });
                this.initMap({ from: mark });
            } else {
                let mark = new ymaps.Placemark(
                    val,
                    {
                        iconContent: "A",
                    },
                    {
                        preset: "islands#redIcon",
                        draggable: dragged,
                        useMapMarginInDragging: true,
                    },
                );
                this.initMap({ from: mark });
            }
            this.maps.map.geoObjects.add(this.maps.from);
            this.maps.map.setCenter(val);

            if (dragged) {
                this.maps.from.events.add(
                    "dragend",
                    e => {
                        let coords = this.maps.from.geometry.getCoordinates();
                        this.orderInit({ address_from_coordinates: coords });
                        let geo = ymaps.geocode(coords);
                        geo.then(res => {
                            let firstGeoObject = res.geoObjects.get(0);
                            let addressLine = firstGeoObject.getAddressLine();
                            this.orderInit({ address_from: addressLine });
                        });
                    },
                    this.maps.map.from,
                );
            }
        },

        toCoordinates(val, inMkad = false, dragged = false) {
            this.maps.map.geoObjects.remove(this.maps.to);

            if (inMkad) {
                let mark = new ymaps.Placemark(val, { iconContent: "B" }, { draggable: dragged });
                this.initMap({ to: mark });
            } else {
                let mark = new ymaps.Placemark(
                    val,
                    {
                        iconContent: "B",
                    },
                    {
                        preset: "islands#redIcon",
                        draggable: dragged,
                    },
                );
                this.initMap({ to: mark });
            }
            this.maps.map.geoObjects.add(this.maps.to);

            if (dragged) {
                this.maps.to.events.add(
                    "dragend",
                    e => {
                        let coords = this.maps.to.geometry.getCoordinates();
                        this.orderInit({ address_to_coordinates: coords });
                        let geo = ymaps.geocode(coords);
                        geo.then(res => {
                            let firstGeoObject = res.geoObjects.get(0);
                            let addressLine = firstGeoObject.getAddressLine();
                            this.orderInit({ address_to: addressLine });
                        });
                    },
                    this.maps.to,
                );
            }
        },

        getTransports() {
            axios.get("transports").then(r => {
                this.initTransports({
                    metros: r.data._payload.metros,
                    airports: r.data._payload.airports,
                    stations: r.data._payload.stations,
                });
            });
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
                field = "",
                msg = "";

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
    },
};
