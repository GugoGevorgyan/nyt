/** @format */

import OrderDialog from "../OrderDialog";
import Order from "../../../models/Order";
import moment from "moment";
import OrderMeet from "../../../models/OrderMeet";
import OrderPassenger from "../../../models/OrderPassenger";
import { mapState } from "vuex";
import { broadcasting } from "../../../mixins/CallCenter";
import Snackbar from "../../../facades/Snackbar";

export default {
    components: { OrderDialog },

    props: {
        socketData: {
            required: true,
        },
        height: {
            required: true,
        },
        call: {
            required: true,
        },
        phoneShow: {
            required: true,
        },
        atcLogged: {
            required: true,
        },
        atcPhoneLogin: {
            required: true,
        },
        type: {
            required: true,
        },
        carClasses: {
            required: true,
            type: Array,
        },
        carOptions: {
            required: true,
            type: Array,
        },
        paymentMethods: {
            required: true,
            type: Array,
        },
        stations: {
            required: true,
            type: Array,
        },
        metros: {
            required: true,
            type: Array,
        },
        airports: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            dialogs: [],
            activeDialog: null,
            orderDialog: false,
            atcLoginLoading: false,
        };
    },

    mixins: [broadcasting],

    watch: {
        atcLogged: function () {
            this.atcLoginLoading = false;
        },
        call: function () {
            if (this.call) {
                this.showCallDialog(this.call);
            }
        },

        "socketData.orderCreated": {
            deep: true,
            handler() {
                this.dialogs.forEach(dialog => {
                    if (this.socketData.orderUpdated.client_id === dialog.clientObj.client.client_id) {
                        dialog.clientObj.orders = this.__addToOrderList(
                            dialog.clientObj.orders,
                            this.socketData.orderCreated,
                            10,
                        );
                    }
                });
            },
        },
        "socketData.orderUpdated": {
            deep: true,
            handler() {
                this.dialogs.forEach(dialog => {
                    if (this.socketData.orderUpdated.client_id === dialog.clientObj.client.client_id) {
                        dialog.clientObj.orders = this.__updateOrderList(
                            dialog.clientObj.orders,
                            this.socketData.orderUpdated,
                        );
                    }
                });
            },
        },
        "socketData.orderCommon": {
            deep: true,
            handler() {
                this.dialogs.forEach(dialog => {
                    dialog.clientObj.orders = this.__updateOrderCommon(
                        dialog.clientObj.orders,
                        this.socketData.orderCommon,
                    );
                });
            },
        },
        "socketData.orderShipped": {
            deep: true,
            handler() {
                this.dialogs.forEach(dialog => {
                    dialog.clientObj.orders = this.__updateOrderShipped(
                        dialog.clientObj.orders,
                        this.socketData.orderShipped,
                    );
                });
            },
        },
    },

    computed: {
        ...mapState(["auth"]),
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },
    },

    methods: {
        clientTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic ? `${initials.join(" ").trim()}` : client.phone;
        },
        phoneLogin(val) {
            this.atcLoginLoading = true;
            this.$emit("updateAtcPhoneLogin", val);
        },

        /*new order dialog*/
        addDialog(orderObj, clientObj) {
            this.dialogs.unshift({
                orderObj: orderObj,
                clientObj: clientObj,
                tab: 0,
            });
            this.activeDialog = 0;
            8 < this.dialogs.length ? this.dialogs.pop() : null;
            this.dialogs.length === 1
                ? (this.orderDialog = true)
                : setTimeout(() => {
                      this.orderDialog = true;
                  }, 1);
        },
        newDialog() {
            if (this.dialogs.length >= 5) {
                Snackbar.info("У вас уже открыто 5 вкладок");
                return;
            }
            this.orderDialog = false;
            let orderObj = this.setOrderValues(null);
            let clientObj = this.setClientValues();
            this.addDialog(orderObj, clientObj);
        },
        showDialog(index) {
            this.orderDialog = false;
            this.activeDialog = index;
            1 === this.dialogs.length
                ? (this.orderDialog = true)
                : setTimeout(() => {
                      this.orderDialog = true;
                  }, 1);
        },
        showCallDialog(callObj) {
            let orderObj = this.setOrderValues(callObj.client.client_id);
            let clientObj = {
                client: callObj.client,
                orders: callObj.orders,
                companies: callObj.companies,
            };
            this.addDialog(orderObj, clientObj);
        },
        hideDialog() {
            this.activeDialog = null;
            this.orderDialog = false;
        },
        closeDialog() {
            this.dialogs.splice(this.activeDialog, 1);
            this.activeDialog = null;
            this.orderDialog = false;
        },
        updateDialogClient(values) {
            this.dialogs[this.activeDialog].clientObj = values;
            this.dialogs[this.activeDialog].orderObj.order.client_id = values.client.client_id;
        },
        orderCreated() {
            this.closeDialog();
        },

        setOrderValues(client_id) {
            let order = new Order({
                client_id: client_id,
                operator_id: this.type ? this.auth.user.system_worker_id : null,
            });
            let meet = new OrderMeet();
            let passenger = new OrderPassenger();

            order.start_time = moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
            order.create_time = moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
            order.time_zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            order.creating_type = this.type;

            return {
                order: order,
                meet: meet,
                passenger: passenger,
            };
        },

        setClientValues() {
            return {
                client: null,
                companies: null,
                orders: null,
            };
        },

        copyOrder(copyValues, activeDialog) {
            let order = new Order(copyValues);

            order.from_coordinates = copyValues.from_coordinates;
            order.to_coordinates = copyValues.to_coordinates;
            order.address_from = copyValues.address_from;
            order.address_to = copyValues.address_to;
            order.displayFrom = copyValues.address_from.split(", ").slice(1).join(", ");
            order.displayTo = copyValues.address_to ? copyValues.address_to.split(", ").slice(1).join(", ") : "";
            order.client_id = this.dialogs[activeDialog].clientObj.client.client_id;
            order.operator_id = this.type ? this.auth.user.system_worker_id : null;
            order.is_meet = !!copyValues.meet;
            order.is_passenger = !!copyValues.passenger;

            let meet = new OrderMeet(copyValues.meet || {});
            let passenger = new OrderPassenger(copyValues.passenger || {});

            let dateFormat = "YYYY-MM-DD HH:mm:ss";
            order.start_time = moment(new Date()).format(dateFormat);
            order.create_time = moment(new Date()).format(dateFormat);
            order.time_zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            order.creating_type = this.type;

            this.dialogs[activeDialog].orderObj = {
                order: order,
                meet: meet,
                passenger: passenger,
            };

            this.dialogs[activeDialog].tab = 1;
        },
    },
};
