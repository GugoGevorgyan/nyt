/** @format */

import { mapMutations, mapState } from "vuex";
import { PAYMENT_TYPE } from "../../../plugins/config";
import { Broadcast, Map, Order } from "../../../mixins";
import AcceptComponent from "../AcceptPhoneComponent";

// noinspection JSUnusedGlobalSymbols
export default {
    name: "OrderFormComponent",

    mixins: [Broadcast, Map, Order],

    props: { carClasses: {}, demands: {}, paymentMethods: {} },

    data() {
        return {
            acceptDialog: false,
            disabledPhone: true,
            mainLoader: false,
            window: {
                width: window.innerWidth,
                height: window.innerHeight - 30,
            },
            rentOrderTitle: "Заказ",
        };
    },

    components: {
        accept: AcceptComponent,
    },

    computed: {
        ...mapState(["orderForm", "broadcast"]),

        validateDisabledButton() {
            return this.order.address_from && this.client.phone && this.order.payment_type && this.order.car_class_id;
        },

        rentTimes() {
            return this.$store.state.orderForm.rent_times;
        },

        orderType: {
            get() {
                return this.$store.state.app.orderType;
            },
            set(type) {
                this.$store.state.app.orderType = type;
            },
        },

        fromDisable: {
            get() {
                return this.$store.state.app.fromDisable;
            },
            set(value) {
                this.$store.state.app.fromDisable = value;
            },
        },

        toDisable: {
            get() {
                return this.$store.state.app.toDisable;
            },
            set(value) {
                this.$store.state.app.toDisable = value;
            },
        },

        date: {
            get() {
                return { date: this.$store.state.date, time: this.$store.state.time };
            },
        },

        maps: {
            get() {
                return this.$store.state.maps;
            },
        },

        order: {
            get() {
                return this.$store.state.order;
            },
        },

        client: {
            get() {
                return this.$store.state.client;
            },
        },

        carOptionPrice() {
            return this.$store.state.order.car_option;
        },

        carClassPrice() {
            return this.$store.state.order.car_class_id;
        },

        paymentTypePrice() {
            return this.$store.state.order.payment_type;
        },

        paymentTypeCompany: {
            get() {
                return this.$store.state.order.payment_type_company;
            },
        },

        carClassess: {
            get() {
                return this.$store.state.orderForm.carClasses ?? this.carClasses;
            },
        },

        rentTime: {
            get() {
                return this.$store.state.order.rent_time;
            },
            set(value) {
                this.$store.state.order.rent_time = value;
            },
        },

        isRent: {
            get() {
                return this.$store.state.order.is_rent;
            },
            set(value) {
                this.$store.state.order.is_rent = value;
            },
        },
    },

    watch: {
        carClassPrice() {
            if (this.order.address_from && !this.mainLoader) {
                this.getOrderPrice();
            }
        },

        carOptionPrice() {
            if (this.order.address_from && !this.mainLoader) {
                this.getOrderPrice();
            }
        },

        paymentTypePrice(val) {
            if (this.order.address_from && !this.mainLoader) {
                this.getOrderPrice();
            }

            if (val !== PAYMENT_TYPE.COMPANY && !this.mainLoader) {
                this.orderInit({ payment_type_company: null });
            }
        },

        paymentTypeCompany(val) {
            if (this.order.address_from && !this.mainLoader) {
                this.getOrderPrice();
            }
        },

        rentTime() {
            if (this.order.address_from && this.order.rent_time && !this.mainLoader) {
                this.getOrderPrice();
            }
        },

        isRent(val) {
            if (val) {
                if (this.order.address_from && this.rentTime) {
                    this.getOrderPrice();
                }
            } else {
                if (this.order.address_from) {
                    this.getOrderPrice();
                }
            }
        },
    },

    methods: {
        ...mapMutations([
            "orderInit",
            "initClient",
            "initMap",
            "initOrderForm",
            "initDriver",
            "initCar",
            "initOrderProgress",
        ]),

        orderRentToggle() {
            if (this.isRent) {
                this.orderType = 1;
                this.isRent = false;
            } else {
                this.orderType = 2;
                this.isRent = true;
                !this.rentTime ? (this.rentTime = this.orderForm.rent_times[0]) : null;
            }

            if (this.rent) {
                this.clearToField();
            }
        },

        subscribeFromInput(val) {
            if (!val) {
                this.clearFromField();
            }
        },

        subscribeToInput(val) {
            if (!val) {
                this.clearToField();
            }
        },

        changeFrom() {
            this.initMap({ init_from: true });
        },

        changeTo() {
            this.initMap({ init_to: true });
        },

        clearFromField() {
            this.fromDisable = true;
            this.orderInit({ address_from: "", address_from_coordinates: [] });
            this.initMap({ from: {}, from_name: "" });
            this.maps.map.geoObjects.remove(this.maps.from);
            this.fromDisable = false;
        },

        clearToField() {
            this.toDisable = true;
            this.orderInit({ address_to: "", address_to_coordinates: [] });
            this.initMap({ to: {}, to_name: "" });
            this.maps.map.geoObjects.remove(this.maps.to);
            this.toDisable = false;
        },

        createSuggestFrom() {
            this.fromDisable = false;
            this.$emit("suggestFrom");
        },

        createSuggestTo() {
            this.toDisable = false;
            this.$emit("suggestTo");
        },

        detectFromLocation(event) {
            this.fromDisable = true;
            let location = ymaps.geolocation.get();

            location.then(result => {
                let userAddress = result.geoObjects.get(0).properties.get("text");
                let userCoordinates = result.geoObjects.get(0).geometry.getCoordinates();
                this.orderInit({ address_from: userAddress, address_from_coordinates: userCoordinates });
                this.fromDisable = false;
            });
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 30;
        },
    },

    created() {
        window.addEventListener("resize", this.handleResize);
        if (!this.client.phone) {
            this.disabledPhone = false;
        }

        Echo.channel("free").listen(`free`, data => {
            console.log(data);
        });
    },
};
