/** @format */
import { mapMutations, mapState } from "vuex";

export default {
    computed: { ...mapState(["maps"]) },

    methods: {
        ...mapMutations({ initMap: "initMap" }),

        init() {
            this.initMap({
                map: new ymaps.Map(
                    "create-map",
                    {
                        center: [
                            this.station.cord ? (this.station.cord[1], this.station.cord[0]) : 40.203058,
                            44.499646,
                        ],
                        zoom: 16,
                        controls: [],
                        behaviors: ["scrollZoom", "drag", "multiTouch"],
                    },
                    {
                        minZoom: 6,
                    },
                ),
            });

            let zoomControl = new ymaps.control.ZoomControl({
                options: {
                    layout: "round#zoomLayout",
                    maxWidth: "large",
                    size: "large",
                    position: {
                        right: 10,
                        top: 100,
                    },
                },
            });

            this.maps.map.controls.add(zoomControl);

            this.markerMaster();
        },

        markerMaster() {
            this.maps.map.events.add("click", e => {
                let coords = e.get("coords");

                if (!this.markGeoObjects) {
                    this.markGeoObjects = new ymaps.GeoObjectCollection();
                }

                let from = "A";
                let indexFrom = this.marks.findIndex(item => {
                    return item.id === from;
                });

                let fromTo = "";
                if (-1 === indexFrom) {
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

                if (initOrder) {
                    this.station.address = firstGeoObject.getAddressLine();
                    this.station.cord = coords;
                }

                this.initMap({ from: mark });

                this.maps.from.events.add(
                    "dragend",
                    res => {
                        this.fromDrag(res);
                    },
                    this.maps.from,
                );
            });
        },

        markSet(id = "A", coords, index) {
            if (-1 === index) {
                this.clickMarkSet(id, coords, true);
            } else {
                this.editMarkSet(id, coords, index);
            }
        },

        editMarkSet(id, coords, index) {
            ymaps.geocode(coords).then(res => {
                let address = res.geoObjects.get(0);
                let geoFromName = address.getAddressLine();
                let addressName = address.properties._data.name;
                let geoObjects = this.markGeoObjects.get(index);

                geoObjects.properties.set({
                    iconContent:
                        "A" === id
                            ? this.orderForm.open
                                ? "A"
                                : "A) " + addressName
                            : this.orderForm.open
                            ? "B"
                            : "B) " + addressName,
                });

                if (!this.maps.init_from) {
                    this.station.address = geoFromName;
                    this.station.cord = coords;
                }

                this.maps.from.events.add(
                    "dragend",
                    res => {
                        this.fromDrag(res);
                    },
                    this.maps.from,
                );
            });
        },

        getGeoObjectGeocode(id, coords) {
            let geoObject = this.markGeoObjects.get(
                this.marks.findIndex(item => {
                    return item.id === id;
                }),
            );

            ymaps.geocode(coords).then(res => {
                let address = res.geoObjects.get(0).properties._data.name;
                geoObject.properties.set({
                    iconContent:
                        "A" === id
                            ? this.orderForm.open
                                ? "A"
                                : "A) " + address
                            : this.orderForm.open
                            ? ""
                            : "B) " + address,
                });
            });
        },

        addMarkGeoObject(id, mark, coords) {
            let marks = this.markGeoObjects.add(mark);
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
                    preset: "islands#bluestationIcon",
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

                this.station.address = secondFromGeoObject.getAddressLine();
                this.station.cord = newFromCords;
            });
        },

        removeMark(mark = "A") {
            let index = this.marks.findIndex(item => {
                return item.id === mark;
            });

            this.markGeoObjects.splice(index, 1);
            this.marks.splice(index, 1);

            if ("A" === mark) {
                this.station.address = "";
                this.station.cord = [];
                this.initMap({ from: {}, from_name: "", init_from: false });
            }
        },
    },
};
