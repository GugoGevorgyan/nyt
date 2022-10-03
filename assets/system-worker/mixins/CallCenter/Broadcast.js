/** @format */

export const broadcasting = {
    methods: {
        __updateOrderList(orders, updatedOrder) {
            let index = orders.findIndex(item => item.order_id === updatedOrder.order_id);
            if (~index) {
                orders.splice(index, 1, JSON.parse(JSON.stringify(updatedOrder)));
                this.rowColor = "pointer";
            }
            return orders;
        },
        __updateOrderListByStatus(orders, updatedOrder, status) {
            let index = orders.findIndex(item => item.order_id === updatedOrder.order_id);
            if (~index && updatedOrder.status.status !== status) {
                orders.splice(index, 1);
            } else if (!~index && updatedOrder.status.status === status) {
                orders.push(JSON.parse(JSON.stringify(updatedOrder)));
            }
            return orders;
        },
        __addToOrderList(orders, createdOrder, maxLength = 0) {
            let index = orders.findIndex(item => item.order_id === createdOrder.order_id);

            if (!~index) {
                orders.unshift(JSON.parse(JSON.stringify(createdOrder)));
                if (maxLength && orders.length > Number(maxLength)) {
                    orders.pop();
                }
            }

            return orders;
        },
        __updateOrderCommon(orders, orderCommon) {
            let index = orders.findIndex(item => item.order_id === orderCommon.order_id);
            if (~index) {
                orders[index].common = JSON.parse(JSON.stringify(orderCommon));
            }
            return orders;
        },
        __updateOrderShipped(orders, orderShipped) {
            let index = orders.findIndex(item => item.order_id === orderShipped.order_id);

            if (index >= 0) {
                if (orders[index].preorder) {
                    orders[index].current_shipped = JSON.parse(JSON.stringify(orderShipped.shipped));
                    orders[index].status = {
                        color: this.socketData.orderShipped.shipped.status.color,
                        name: this.socketData.orderShipped.shipped.status.name,
                        status: this.socketData.orderShipped.shipped.status.status,
                        text: this.socketData.orderShipped.shipped.status.text,
                    };
                }
            };
            if (~index) {
                orders[index].current_shipped = JSON.parse(JSON.stringify(orderShipped.shipped));
            };

            return orders;
        },

        __updateActiveBoardList(items, board, maxLength = 0) {
            let index = items.findIndex(item => item.driver_id === board.driver_id);
            if (~index && board.online) {
                items.splice(index, 1, JSON.parse(JSON.stringify(board)));
            } else if (!~index && board.online) {
                items.push(JSON.parse(JSON.stringify(board)));
                if (maxLength && items.length > Number(maxLength)) {
                    items.pop();
                }
            }
            return items;
        },
        __updateDriverShipped(drivers, driverShipped) {
            let index = drivers.findIndex(item => item.driver_id === driverShipped.driver_id);
            if (~index) {
                drivers[index].active_order_shipment = JSON.parse(JSON.stringify(driverShipped.shipped));
            }
            return drivers;
        },

        __addToList(items, newItem, key, maxLength = 0) {
            let index = items.findIndex(item => item[key] === newItem[key]);
            if (!~index) {
                items.unshift(JSON.parse(JSON.stringify(newItem)));
                if (maxLength && items.length > Number(maxLength)) {
                    items.pop();
                }
            }
            return items;
        },
        __updateList(items, updatedItem, key) {
            let index = items.findIndex(item => item[key] === updatedItem[key]);
            if (~index) {
                items.splice(index, 1, JSON.parse(JSON.stringify(updatedItem)));
            }
            return items;
        },
    },
};
