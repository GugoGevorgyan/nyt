/** @format */
import moment from "moment";

export default {
    name: "OrderActions",

    props: {
        height: {
            required: true,
        },
        order: {
            required: true,
        },
    },

    data() {
        return {
            type: 0,
            radius: 0,
            filtersDriver: [],
            filtersDistance: [],

            drivers: {},
            loadDriver: false,
            selected: [],
            acceptDriver: {},
            selectedDriver: {},
            orderInitialDate: moment(),
            orderDate: moment(),
            disableDate: false,
            justNow: true,
            resultMsg: "",
            sendListLoading: false,
            sendPinLoading: false,
        };
    },

    watch: {
        type: function () {
            this.getDrivers();
        },

        radius: function () {
            this.getDrivers();
        },

        justNow: function (val) {
            this.disableDate = !!val;
        },
    },

    computed: {
        validationSend() {
            return (
                !Object.keys(this.drivers).length ||
                !(Object.keys(this.acceptDriver).length || Object.keys(this.selectedDriver).length)
            );
        },
    },

    methods: {
        getDrivers() {
            this.loadDriver = true;
            this.$http
                .get(`call-center/drivers-for-edit/${this.order.order_id}/${this.type}/${this.radius}`)
                .then(result => {
                    if (!this.filtersDriver.length && !this.filtersDistance.length) {
                        this.filtersDistance = result.data.payload.radius;
                        this.filtersDriver = result.data.payload.types;
                    } else {
                        this.drivers = result.data;
                        this.loadDriver = false;
                        setTimeout(() => {
                            for (let driver of this.drivers) {
                                if (driver.accepted) {
                                    this.acceptDriver = driver;
                                    let el = document.getElementById("row-" + driver.driver.driver_id);

                                    if (el) {
                                        el.style.backgroundColor = "#91b392";
                                    }
                                } else if (
                                    Object.keys(this.selectedDriver).length &&
                                    this.selectedDriver.driver.driver_id === driver.driver.driver_id
                                ) {
                                    let el = document.getElementById("row-" + driver.driver.driver_id);
                                    if (el) {
                                        el.style.backgroundColor = "#6c777e";
                                    }
                                } else {
                                    let el = document.getElementById("row-" + driver.driver.driver_id);
                                    if (el) {
                                        el.style.backgroundColor = "";
                                    }
                                }
                            }
                        }, 50);
                    }
                });
        },

        rowHandler(driver) {
            let result = true;

            for (let selected of this.selected) {
                let el = document.getElementById("row-" + selected);

                if (el) {
                    el.style.backgroundColor = "";
                }

                if (selected === driver.driver.driver_id) {
                    this.selected = [];
                    this.selectedDriver = {};
                    result = false;

                    if (driver.accepted) {
                        document.getElementById("row-" + selected).style.backgroundColor = "#91b392";
                    } else {
                        document.getElementById("row-" + selected).style.backgroundColor = "";
                    }
                }
            }

            if (result) {
                this.selected = [];
                this.selected.push(driver.driver.driver_id);
                document.getElementById("row-" + driver.driver.driver_id).style.backgroundColor = "#6c777e";
                this.selectedDriver = driver;
            }
        },

        clickRow(driver) {
            this.rowHandler(driver);
        },

        changeOrder() {
            this.sendPinLoading = true;
            let driver_id, order_id, date, now;
            [driver_id, order_id, date, now] = this.getChangePayload();
            this.$http
                .put(`call-center/dist-order/${order_id}/${driver_id}/${date}/${now}`)
                .then(response => {
                    this.resultMsg = response.data.message;
                    this.sendPinLoading = false;
                    setTimeout(() => {
                        this.resultMsg = "";
                    }, 4000);
                })
                .catch(error => {
                    this.resultMsg = error.response.data.message;
                    this.sendPinLoading = false;
                    setTimeout(() => {
                        this.resultMsg = "";
                    }, 4000);
                });
        },

        getChangePayload() {
            let driver_id = this.selectedDriver.driver
                ? this.selectedDriver.driver.driver_id
                : this.acceptDriver.driver.driver_id;
            let order_id = this.order.order_id;
            let date = this.justNow ? null : this.orderDate;
            let now = this.justNow ? 1 : 0;

            return [driver_id, order_id, date, now];
        },

        sendDriverList() {
            this.sendListLoading = true;
            this.$http
                .post("call-center/send-list", {
                    order_id: this.order.order_id,
                    drivers: this.drivers.map(item => item.driver.driver_id),
                    radius: this.radius,
                    type: this.type,
                })
                .then(result => {
                    this.resultMsg = result.data.message;
                    this.sendListLoading = false;
                    setTimeout(() => {
                        this.resultMsg = "";
                    }, 4000);
                })
                .catch(error => {
                    this.resultMsg = error.response.data.message;
                    this.sendListLoading = false;
                    setTimeout(() => {
                        this.resultMsg = "";
                    }, 4000);
                });
        },
    },

    created() {
        this.getDrivers();
        this.type = 6;
        if (this.order.preorder) {
            this.orderDate = this.order.preorder.time;
            this.orderInitialDate = this.order.preorder.time;
        } else {
            this.disableDate = true;
            this.orderDate = this.order.created_at;
            this.orderInitialDate = this.order.created_at;
        }
    },
};
