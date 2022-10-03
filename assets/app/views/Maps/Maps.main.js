/** @format */
import { mapMutations, mapState } from 'vuex';
import OrderTaxi from '../../components/taximeter/OrderTaxi.component';
import OrderProgress from '../../components/taximeter/OrderProgress.component';
import OrderFeedback from '../../components/taximeter/OrderFeedback.component';
import RepeatOrder from '../../components/taximeter/RepeatOrder';
import PaymentCard from '../../components/taximeter/PaymentCard/PaymentCard.component';
import Transport from '../../components/taximeter/Transports.component';
import { Broadcast, Map, Order } from '../../services';
import { PAYMENT_TYPE } from '../../plugins/config';

// noinspection JSUnusedGlobalSymbols
export default {
    name: "maps",

    components: { OrderTaxi, OrderProgress, OrderFeedback, Transport, PaymentCard, RepeatOrder },

    props: { initialClient: {}, logoutRoute: {} },

    data() {
        return {
            fab: false,
            PAYMENT_TYPE: PAYMENT_TYPE,
            marks: [],
            placeMarks: [],
            carGeoObjects: false,
            markGeoObjects: false,
            driverOnWayPlacemark: {},
            newLat: 40.15374,
            newLong: 44.49045,
            cornerDegree: null,
            path: null,
            overlay: true,
        };
    },

    mixins: [Map, Broadcast, Order],

    computed: {
        ...mapState(["maps", "orderFeedback", "orderProgress", "order", "orderForm", "orderRepeat"]),

        driverLoc() {
            return [this.newLat, this.newLong];
        },

        inOrderStatus() {
            return this.$store.state.inOrder;
        },

        client: {
            get() {
                return this.$store.state.client;
            },
        },

        addressFrom() {
            return this.$store.state.order.address_from;
        },

        addressTo() {
            return this.$store.state.order.address_to;
        },

        paymentType: {
            get() {
                return this.$store.state.order.payment_type;
            },
        },

        paymentCards: {
            get() {
                return this.$store.state.client.pay_cards;
            },
        },

        paymentDialog: {
            get() {
                return this.$store.state.app.paymentDialog;
            },
            set(val) {
                return (this.$store.state.app.paymentDialog = val);
            },
        },
    },

    watch: {
        addressFrom(val) {
            if (val) {
                ymaps.geocode(val).then(res => {
                    const firstGeoObject = res.geoObjects.get(0);
                    const displayAddress = this.setDisplayName(firstGeoObject);
                    localStorage.setItem("from", displayAddress);
                    const coords = firstGeoObject.geometry.getCoordinates();
                    localStorage.setItem("from_c", coords);

                    if (!this.markGeoObjects) {
                        this.markGeoObjects = new ymaps.GeoObjectCollection();
                    }
                    const from = 'A';
                    const indexFrom = this.marks.findIndex(item => {
                        return from === item.id;
                    });
                    if (this.orderForm.open) {
                        this.markSet(from, coords, indexFrom, val);
                    }

                });
            } else {
                localStorage.removeItem("from_c");
                localStorage.removeItem("from");
                this.removeMark("A");
                this.removeDriverMarks();
                this.initOrderForm({ priceText: this.orderForm.priceTextDefault, coin: null });
            }
        },

        addressTo(val) {
            if (val) {
                ymaps.geocode(val).then(res => {
                    const firstGeoObject = res.geoObjects.get(0);
                    const displayAddress = this.setDisplayName(firstGeoObject, false);
                    localStorage.setItem('to', displayAddress);
                    const coords = firstGeoObject.geometry.getCoordinates();
                    localStorage.setItem('to_c', coords);

                    if (!this.markGeoObjects) {
                        this.markGeoObjects = new ymaps.GeoObjectCollection();
                    }
                    const to = 'B';
                    const indexTo = this.marks.findIndex(item => {
                        return item.id === to;
                    });
                    if (this.orderForm.open) {
                        this.markSet(to, coords, indexTo, val);
                    }
                });
            } else {
                localStorage.removeItem("to_c");
                localStorage.removeItem("to");
                this.removeMark("B");
                if (this.orderForm.open) {
                    this.getOrderPrice();
                }
            }
        },

        cornerDegree(val) {
            this.placemark.options.set({ iconRotate: val });
        },

        driverLoc(newVal, oldVal) {
            let newLat = newVal[0],
                newLong = newVal[1],
                currentLat = oldVal[0],
                currentLong = oldVal[1],
                a,
                b,
                c,
                sin;

            if (newLat === currentLat && newLong === currentLong) {
                this.cornerDegree = 0;
            } else if (newLat === currentLat) {
                if (newLong > currentLong) {
                    this.cornerDegree = 90;
                } else {
                    this.cornerDegree = 270;
                }
            } else if (newLong === currentLong) {
                if (newLat > currentLat) {
                    this.cornerDegree = 0;
                } else {
                    this.cornerDegree = 180;
                }
            } else if (newLat > currentLat) {
                if (newLong > currentLong) {
                    sin = this.getSinus(newLat, currentLat, newLong, currentLong);
                    this.cornerDegree = this.getCornerDegree(sin);
                } else {
                    sin = this.getSinus(newLat, currentLat, currentLong, newLong);
                    this.cornerDegree = -this.getCornerDegree(sin);
                }
            } else if (newLat < currentLat) {
                if (newLong > currentLong) {
                    sin = this.getSinus(currentLat, newLat, newLong, currentLong);
                    this.cornerDegree = 90 - this.getCornerDegree(sin) + 90;
                } else {
                    a = currentLat - newLat;
                    b = currentLong - newLong;
                    c = Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2));
                    sin = this.getSinus(currentLat, newLat, currentLong, newLong);
                    this.cornerDegree = -(90 - this.getCornerDegree(sin) + 90);
                }
            }
        },

        feedbackDialog(newVal) {
            if (newVal) {
                this.orderCancelFeedbackInterval = setTimeout(() => {
                    this.feedbackDialog = false;
                }, 300000);
            }
        },
    },

    mounted() {
        ymaps.ready(this.init).then(() => {
            this.overlay = false;
            document.onreadystatechange = () => {
                if ("complete" === document.readyState) {
                    if (this.order.address_from_coordinates.length) {
                        if (!this.markGeoObjects) {
                            this.markGeoObjects = new ymaps.GeoObjectCollection();
                        }
                        let cord = this.order.address_from_coordinates;
                        if (!localStorage.getItem('from')) {
                            let mark = this.createMark("A", cord);
                            this.addMarkGeoObject("A", mark, cord);
                        }
                    }

                    if (this.order.address_to_coordinates.length) {
                        if (!this.markGeoObjects) {
                            this.markGeoObjects = new ymaps.GeoObjectCollection();
                        }
                        let cord = this.order.address_to_coordinates;
                        if (!localStorage.getItem('to')) {
                            let mark = this.createMark("B", cord);
                            this.addMarkGeoObject("B", mark);
                        }

                    }
                }
            };
        });
    },

    methods: {
        ...mapMutations({
            initOrderAction: "INIT_IN_ORDER_ACTION",
            initOrderProgress: "initOrderProgress",
            initClientInOrderData: "initClientInOrderData",
            initMap: "initMap",
            orderInit: "orderInit",
            initDriver: "initDriver",
            initCar: "initCar",
            initClient: "initClient",
            initOrderForm: "initOrderForm",
        }),

        setDisplayName(geoObject, from = true) {
            let displayAddress = "";
            displayAddress += geoObject.getPremise() || "";
            displayAddress += geoObject.getPremiseNumber() ? geoObject.getPremiseNumber() + ", " : "";
            displayAddress += geoObject.getThoroughfare() ? geoObject.getThoroughfare() + ", " : "";
            displayAddress += geoObject.getLocalities() ? geoObject.getLocalities() : "";

            this.initOrderForm(from ? { displayFrom: displayAddress } : { displayTo: displayAddress });

            return displayAddress;
        },

        hasGeoObjectsAddDriver(driver) {
            if (!this.carGeoObjects) {
                this.carGeoObjects = new ymaps.GeoObjectCollection();
                this.addInGeoObject(driver);
                this.maps.map.geoObjects.add(this.carGeoObjects);
            }

            this.carGeoObjects.add(this.createDriverMark(driver));
        },

        getCornerDegree(sin) {
            return +((Math.asin(sin) / Math.PI) * 180).toFixed(2);
        },

        getSinus(newLat, oldLat, newLong, oldLong) {
            let a = newLat - oldLat;
            let b = newLong - oldLong;
            let c = Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2));
            return b / c;
        },

        goProfile() {
            location.href = `${process.env.MIX_APP_URL}/profile`;
        },

        init() {
            this.initMap({
                map: new ymaps.Map(
                    "map",
                    {
                        center: [this.location.lat, this.location.lut],
                        zoom: 15,
                        controls: [],
                        behaviors: ["scrollZoom", "drag", "multiTouch"],
                    },
                    {
                        minZoom: 10,
                    },
                ),
            });

            let trafficControl = new ymaps.control.TrafficControl({
                layout: "round#buttonLayout",
                options: {
                    maxWidth: "small",
                    size: "small",
                },
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

            this.maps.map.controls.add(trafficControl);
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

        clickMarkSet(id = "A", coords, initOrder = false, currentAddress) {
            let mark = this.createMark(id, coords);
            this.addMarkGeoObject(id, mark, coords);
            this.getGeoObjectGeocode(id, coords);


            ymaps.geocode(coords).then(res => {
                const firstGeoObject = res.geoObjects.get(0);

                if ("A" === id) {
                        if (initOrder && currentAddress !== localStorage.getItem('from')) {
                            this.orderInit({
                                address_from: firstGeoObject.getAddressLine(),
                                address_from_coordinates: coords,
                            });
                            this.setDisplayName(firstGeoObject);
                        }

                        this.initMap({ from: mark });

                        this.maps.from.events.add(
                            "dragend",
                            res => {
                                this.fromDrag(res);
                            },
                            this.maps.from,
                        );
                    }


                if ("B" === id) {
                    if (initOrder && currentAddress !== localStorage.getItem('to')) {
                        this.setDisplayName(firstGeoObject, false);
                        this.orderInit({ address_to: firstGeoObject.getAddressLine(), address_to_coordinates: coords });
                    }

                    this.initMap({ to: mark });

                    this.maps.to.events.add(
                        "dragend",
                        res => {
                            this.toDrag(res);
                        },
                        this.maps.to,
                    );
                }
            });
        },

        markSet(id = "A", coords, index, addressValue) {
            if (-1 === index) {
                this.clickMarkSet(id, coords, true, addressValue);
            } else {
                this.editMarkSet(id, coords, index);
            }
            if (this.order.address_from) {
                this.getOrderPrice();
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

                if ("A" === id) {
                    if (!this.maps.init_from) {
                        this.orderInit({ address_from: geoFromName, address_from_coordinates: coords });
                        this.setDisplayName(address);
                    }
                    this.maps.from.events.add(
                        "dragend",
                        res => {
                            this.fromDrag(res);
                        },
                        this.maps.from,
                    );
                }

                if ("B" === id) {
                    if (!this.maps.init_to) {
                        this.orderInit({ address_to: geoFromName, address_to_coordinates: coords });
                        this.setDisplayName(address, false);
                    }

                    this.maps.to.events.add(
                        "dragend",
                        res => {
                            this.toDrag(res);
                        },
                        this.maps.to,
                    );
                }
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
                    // iconLayout: 'default#image',
                    // // iconImageClipRect: [[34,0], [62, 46]],
                    // iconImageHref: 'A' === id ? '/storage/img/markers/marker-from.png' : '/storage/img/markers/marker-to.png',
                    // iconImageSize: [60, 60],
                    // // iconImageOffset: [-26, -46],
                    // zIndex: 99999,
                    // draggable: true,
                    preset: id === "A" ? "islands#redInfoIcon" : "islands#blueHomeIcon",
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

                this.setDisplayName(secondFromGeoObject);
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

                this.setDisplayName(secondToGeoObject, false);
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

            if (index >= 0) {
                this.markGeoObjects.splice(index, 1);
                this.marks.splice(index, 1);
            }

            if ("A" === mark) {
                this.orderInit({ address_from: "", address_from_coordinates: [] });
                this.initMap({ from: {}, from_name: "", init_from: false });
                this.initOrderForm({ displayFrom: "" });
            } else {
                this.orderInit({ address_to: "", address_to_coordinates: [] });
                this.initMap({ to: {}, to_name: "", init_to: false });
                this.initOrderForm({ displayTo: "" });
            }
        },

        /*================================================FROM TO MARKS========================================*/

        logout() {
            document.getElementById("logout-form").submit();
        },
    },

    beforeDestroy() {
        this.broadcast.leave(
            `${process.env.MIX_CHANNEL_CLIENT_WEB}-base-client-channel.${this.client.client_id}.${this.client.phone}`,
        );
    },

    created() {
        this.initOrderForm({ open:true });
        this.initClient(this.initialClient);
        this.broadcastEvents();
        this.clientInOrderData();
    },
};
