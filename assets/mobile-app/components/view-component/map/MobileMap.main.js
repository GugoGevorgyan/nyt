/** @format */

import { mapMutations, mapState } from "vuex";
import { Broadcast, Map, Order } from "../../../mixins";

export default {
    name: "MobileMapComponent",

    mixins: [Broadcast, Map, Order],

    computed: {
        ...mapState(["maps", "client", "order", "orderForm"]),

        overlay: {
            get() {
                return this.$store.state.overlay;
            },

            set(value) {
                this.$store.state.overlay = value;
            },
        },

        addressFrom() {
            return this.$store.state.order.address_from;
        },

        addressTo() {
            return this.$store.state.order.address_to;
        },

        location() {
            return this.$store.state.app.navigateCord;
        },
    },

    watch: {
        addressFrom(val) {
            if (val) {
                ymaps.geocode(val).then(res => {
                    let firstGeoObject = res.geoObjects.get(0);
                    let coords = firstGeoObject.geometry.getCoordinates();
                    localStorage.setItem("from_c", coords);
                    localStorage.setItem("from", val);

                    if (!this.geoObjects) {
                        this.geoObjects = new ymaps.GeoObjectCollection();
                    }

                    let from = "A";
                    let indexFrom = this.marks.findIndex(item => {
                        return item.id === from;
                    });

                    this.markSet(from, coords, indexFrom);
                });
            } /*else {
                localStorage.removeItem('from_c');
                localStorage.removeItem('from');
                this.removeMark('A');
            }*/
        },

        addressTo(val) {
            if (val) {
                ymaps.geocode(val).then(res => {
                    let firstGeoObject = res.geoObjects.get(0);
                    let coords = firstGeoObject.geometry.getCoordinates();
                    localStorage.setItem("to", val);
                    localStorage.setItem("to_c", coords);

                    if (!this.geoObjects) {
                        this.geoObjects = new ymaps.GeoObjectCollection();
                    }

                    let to = "B";
                    let indexTo = this.marks.findIndex(item => {
                        return item.id === to;
                    });

                    this.markSet(to, coords, indexTo);
                });
            } else {
                localStorage.removeItem("to_c");
                localStorage.removeItem("to");
                this.removeMark("B");
                this.getOrderPrice();
            }
        },
    },

    methods: {
        ...mapMutations([
            "orderInit",
            "initClient",
            "initMap",
            "initOrderProgress",
            "initOrderForm",
            "initDriver",
            "initCar",
        ]),

        init() {
            this.initMap({
                map: new ymaps.Map("map", {
                    center: [this.location.lat, this.location.lut],
                    zoom: 15,
                    controls: [],
                    behaviors: ["scrollZoom", "drag", "multiTouch"],
                }),
            });

            this.maps.map.controls.add("zoomControl", {
                size: "large",
                layout: "round#zoomLayout",
                position: {
                    right: 10,
                    bottom: 150,
                    size: "large",
                },
            });

            this.markerMaster();
        },

        markerMaster() {
            this.maps.map.events.add("click", e => {
                let coords = e.get("coords");

                if (!this.geoObjects) {
                    this.geoObjects = new ymaps.GeoObjectCollection();
                }

                let from = "A";
                let indexFrom = this.marks.findIndex(item => {
                    return item.id === from;
                });

                let to = "B";
                let indexTo = !this.order.is_rent
                    ? this.marks.findIndex(item => {
                        return item.id === to;
                    })
                    : null;

                let fromTo = "";
                if (-1 === indexFrom && -1 !== indexTo) {
                    fromTo = from;
                } else if (-1 !== indexFrom && -1 === indexTo) {
                    fromTo = to;
                } else if (-1 === indexFrom && -1 === indexTo) {
                    fromTo = from;
                }

                if ("" !== fromTo) {
                    this.clickMarkSet(fromTo, coords, true);
                }
            });
        },

        clickMarkSet(id = "A", coords, initOrder = false) {
            let mark = this.createMark(id, coords);
            this.addMarkGeoObject(id, mark, coords);
            this.getGeoObjectGeocode(id, coords);

            ymaps.geocode(coords).then(res => {
                let firstGeoObject = res.geoObjects.get(0);

                if ("A" === id) {
                    if (initOrder) {
                        this.orderInit({
                            address_from: firstGeoObject.getAddressLine(),
                            address_from_coordinates: coords,
                        });
                    }
                    this.initMap({ from: mark });

                    this.maps.from.events.add(
                        "dragend",
                        res => {
                            this.fromDrag(res);
                        },
                        this.maps.map.from,
                    );
                }

                if ("B" === id) {
                    if (initOrder) {
                        this.orderInit({ address_to: firstGeoObject.getAddressLine(), address_to_coordinates: coords });
                    }

                    this.initMap({ to: mark });

                    this.maps.to.events.add(
                        "dragend",
                        res => {
                            this.toDrag(res);
                        },
                        this.maps.map.to,
                    );
                }
            });
        },

        markSet(id = "A", coords, index) {
            if (-1 === index) {
                this.clickMarkSet(id, coords, true);
                if (this.order.address_from) {
                    this.getOrderPrice();
                }
            } else {
                this.editMarkSet(id, coords, index);
                if (this.order.address_from) {
                    this.getOrderPrice();
                }
            }
        },

        editMarkSet(id, coords, index) {
            ymaps.geocode(coords).then(res => {
                let address = res.geoObjects.get(0);
                let geoFromName = address.getAddressLine();
                let addressName = address.properties._data.name;

                let geoObjects = this.geoObjects.get(index);

                geoObjects.properties.set({
                    iconContent: "A" === id ? "A" /*+ addressName*/ : "B" /*+ addressName*/,
                });

                if ("A" === id) {
                    if (!this.maps.init_from) {
                        this.orderInit({ address_from: geoFromName, address_from_coordinates: coords });
                    }

                    this.maps.from.events.add(
                        "dragend",
                        res => {
                            this.fromDrag(res);
                        },
                        this.maps.map.from,
                    );
                }

                if ("B" === id) {
                    if (!this.maps.init_to) {
                        this.orderInit({ address_to: geoFromName, address_to_coordinates: coords });
                    }

                    this.maps.to.events.add(
                        "dragend",
                        res => {
                            this.toDrag(res);
                        },
                        this.maps.map.to,
                    );
                }
            });
        },

        getGeoObjectGeocode(id, coords) {
            let geoObject = this.geoObjects.get(
                this.marks.findIndex(item => {
                    return item.id === id;
                }),
            );

            ymaps.geocode(coords).then(res => {
                let address = res.geoObjects.get(0).properties._data.name;
                geoObject.properties.set({
                    iconContent: "A" === id ? "A" /*+ address*/ : "B" /*+ address*/,
                });
            });
        },

        addMarkGeoObject(id, mark, coords) {
            let marks = this.geoObjects.add(mark);
            this.marks.push({ id: id, mark: mark });
            this.maps.map.geoObjects.add(marks);

            if ("A" === id) {
                this.maps.map.setCenter(coords);
            }
        },

        createMark(id, coords) {
            return new ymaps.Placemark(
                coords,
                { id: id },
                {
                    preset: id === "A" ? "islands#redHomeIcon" : "islands#blueInfoIcon",
                    draggable: true,
                    zIndex: 99999,
                },
            );
        },

        fromDrag(drag) {
            let coords = drag.get("target").geometry._coordinates;

            ymaps.geocode(coords).then(res => {
                let secondFromGeoObject = res.geoObjects.get(0);
                let newFromCords = secondFromGeoObject.geometry._coordinates;

                this.orderInit({
                    address_from: secondFromGeoObject.getAddressLine(),
                    address_from_coordinates: newFromCords,
                });
            });
        },

        toDrag(drag) {
            let coords = drag.get("target").geometry._coordinates;

            ymaps.geocode(coords).then(res => {
                let secondToGeoObject = res.geoObjects.get(0);
                let newToCords = secondToGeoObject.geometry._coordinates;

                this.orderInit({
                    address_to: secondToGeoObject.getAddressLine(),
                    address_to_coordinates: newToCords,
                });
            });
        },

        removeMark(mark = "A") {
            let index = this.marks.findIndex(item => {
                return item.id === mark;
            });

            this.geoObjects ? this.geoObjects.splice(index, 1) : null;

            this.marks.splice(index, 1);

            if ("A" === mark) {
                this.orderInit({ address_from: "", address_from_coordinates: [] });
                this.initMap({ from: {}, from_name: "", init_from: false });
            } else {
                this.orderInit({ address_to: "", address_to_coordinates: [] });
                this.initMap({ to: {}, to_name: "", init_to: false });
            }
        },
    },

    beforeCreate() {
        document.onreadystatechange = () => {
            if ("complete" === document.readyState) {
                this.overlay = false;
            }
        };
    },

    mounted() {
        ymaps.ready(this.init);
    },
};
