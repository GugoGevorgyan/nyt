<!-- @format -->

<template>
    <div>
        <system-bar class="mb-5" />
        <drawer style="z-index: 9999999" v-if="drawer" />

        <map-component />
        <order-component
            v-if="!orderProgress.status"
            :carClasses="carClasses"
            :demands="demands"
            @demand-change="demands = $event"
            :paymentMethods="paymentMethods"
            @suggestFrom="suggestFrom"
            @suggestTo="suggestTo"
        />
        <order-progress v-if="orderProgress.status" />
        <order-feedback v-if="orderFeedback.status" />
    </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import { Broadcast, Map, Order } from "../mixins";
import OrderFormComponent from "../components/view-component/form/OrderForm.component";
import MobileMapComponent from "../components/view-component/map/MobileMap.component";
import OrderProgressComponent from "../components/view-component/OrderProgressComponent";
import OrderFeedbackComponent from "../components/view-component/OrderFeedbackComponent";
import DialogueComponent from "../components/view-component/DialogueComponent";

export default {
    name: "MobileIndex",

    mixins: [Broadcast, Map, Order],

    data() {
        return {
            suggestViewFrom: undefined,
            suggestViewTo: undefined,

            carClasses: [],
            paymentMethods: [],

            demands: [],

            window: {
                width: 0,
                height: window.innerHeight - 30,
            },
        };
    },

    props: { initialClient: undefined, logoutRoute: undefined },

    components: {
        orderComponent: OrderFormComponent,
        mapComponent: MobileMapComponent,
        OrderProgress: OrderProgressComponent,
        OrderFeedback: OrderFeedbackComponent,
        dialogue: DialogueComponent,
    },

    computed: {
        ...mapState({
            maps: "maps",
            order: "order",
            client: "client",
            orderProgress: "orderProgress",
            orderFeedback: "orderFeedback",
        }),

        drawer: {
            get() {
                return this.$store.state.drawer;
            },
            set(val) {
                this.$store.state.drawer = val;
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

        broadcast: {
            get() {
                return this.$store.state.broadcast;
            },
            set(val) {
                this.$store.state.broadcast = val;
            },
        },
    },

    methods: {
        ...mapMutations({
            orderInit: "orderInit",
            initClient: "initClient",
            initMap: "initMap",
            initOrderProgress: "initOrderProgress",
            initOrderForm: "initOrderForm",
            initDriver: "initDriver",
            initCar: "initCar",
        }),

        suggestFrom() {
            if (!this.suggestViewFrom) {
                this.suggestViewFrom = new ymaps.SuggestView("from");
            }

            this.suggestViewFrom.events.add("select", e => {
                if (this.suggestViewFrom) {
                    this.suggestViewFrom.destroy();
                }

                this.suggestViewFrom = null;
                this.orderInit({ address_from: e.get("item").value });

                ymaps.geocode(e.get("item").value).then(res => {
                    this.orderInit({ address_from_coordinates: res.geoObjects.get(0).geometry._coordinates });
                });
            });
        },

        suggestTo() {
            if (!this.suggestViewTo) {
                this.suggestViewTo = new ymaps.SuggestView("to");
            }

            this.suggestViewTo.events.add("select", e => {
                this.suggestViewTo ? this.suggestViewTo.destroy() : null;
                this.suggestViewTo = null;
                this.orderInit({ address_to: e.get("item").value });

                ymaps.geocode(e.get("item").value).then(res => {
                    this.orderInit({ address_to_coordinates: res.geoObjects.get(0).geometry._coordinates });
                });
            });
        },

        initSuggest() {
            this.suggestFrom();
            this.suggestTo();
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 30;
        },

        hasGeoObjectsAddDriver(driver) {
            if (!this.carGeoObjects) {
                this.carGeoObjects = new ymaps.GeoObjectCollection();
                this.addInGeoObject(driver);
                this.maps.map.geoObjects.add(this.carGeoObjects);
            }

            this.carGeoObjects.add(this.createDriverMark(driver));
        },
    },

    created() {
        this.initialize();
        window.addEventListener("resize", this.handleResize);
        ymaps.ready(this.initSuggest);
        this.broadcastEvents();
    },
};
</script>

<style lang="scss" scoped>
/*#window-slide {*/
/*    height: 100% !important;*/
/*}*/
</style>
