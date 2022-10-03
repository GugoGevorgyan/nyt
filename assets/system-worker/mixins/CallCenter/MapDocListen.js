/** @format */

export const orderMapListen = {
    methods: {
        orderPlaceMarkCallAction(self, order) {
            return document.getElementById("order-phone-" + order.order_id).addEventListener(
                "click",
                function () {
                    self.call(order.client.phone);
                },
                false,
            );
        },

        orderPlaceMarkAttachAction(self, order) {
            return document.getElementById("order-attach-" + order.order_id).addEventListener(
                "click",
                function () {
                    self.map.balloon.close();
                    self.map.setCenter([order.from_coordinates.lat, order.from_coordinates.lut]);
                    self.placeMarkOptions.order.attached = true;
                    self.placeMarkOptions.driver.attach = true;
                    self.placeMarkOptions.driver.attachOrder = order;
                    self.showAttachPlaceMarks(order);
                    self.addCancelOrderAttachBtn(self);
                },
                false,
            );
        },

        orderPlaceMarkCancelAttachAction(self, order) {
            return document.getElementById("order-attach-" + order.order_id).addEventListener(
                "click",
                function () {
                    self.mapSetObjects();
                },
                false,
            );
        },

        driverPlaceMarkAttachAction(self, driver) {
            return document.getElementById("driver-attach-" + driver.driver_id).addEventListener(
                "click",
                function () {
                    self.driverAddOrder(driver.driver_id, self.placeMarkOptions.driver.attachOrder.order_id);
                },
                false,
            );
        },

        driverPlaceMarkCallAction(self, driver) {
            return document.getElementById("driver-phone-" + driver.driver_id).addEventListener(
                "click",
                function () {
                    self.call(driver.phone);
                },
                false,
            );
        },

        orderToManualy(self, order) {
            return document.getElementById("order-manualy-" + order.order_id).addEventListener(
                "click",
                function () {
                    self.orderChangeManual(order.order_id);
                },
                false,
            );
        },

        unpinOrderToDriver(self, driver_id) {
            return document.getElementById(`order-driver-${driver_id}`).addEventListener(
                "click",
                () => {
                    self.orderUnpinInDriver(driver_id);
                },
                false,
            );
        },
    },
};
