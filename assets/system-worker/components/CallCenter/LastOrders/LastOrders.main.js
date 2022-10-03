/** @format */

import moment from "moment-timezone";
import Snackbar from "../../../facades/Snackbar";

export default {
    props: {
        loading: {
            required: true,
        },
        orders: {
            required: true,
        },
        client: {
            required: true,
        },
    },

    data() {
        return {
            headers: [
                {
                    text: "Номер",
                    sortable: false,
                    value: "order_id",
                },
                {
                    text: "Статус",
                    sortable: false,
                    value: "status",
                },
                {
                    text: "Борт",
                    sortable: false,
                    value: "board",
                },
                {
                    text: "Откуда",
                    sortable: false,
                    value: "address_from",
                },
                {
                    text: "Куда",
                    sortable: false,
                    value: "address_to",
                },
                {
                    text: "Способ оплаты",
                    sortable: false,
                    value: "payment_type.name",
                },
                {
                    text: "Цена поездки",
                    sortable: false,
                    value: "cost",
                },
                {
                    text: "Создан",
                    sortable: false,
                    value: "created_at",
                },
                {
                    text: "Дейцтвия",
                    sortable: false,
                    value: "actions",
                },
                { text: "", value: "data-table-expand" },
            ],
            expanded: [],
            cancelLoading: false,
            cancelDialog: false,
            cancelingOrder: null,
        };
    },

    filters: {
        passengerTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic
                ? `${initials.join(" ").trim()}, телефон: ${client.phone}`
                : `Телефон: ${client.phone}`;
        },
    },

    methods: {
        commaJoin(arr, key) {
            let values = [];
            arr.forEach(item => {
                values.push(item[key]);
            });
            return values.join(", ");
        },
        isToday(someDate) {
            let today = new Date();
            return (
                someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear()
            );
        },
        getDateShorted(date) {
            let itemDate = new Date(date);
            return this.isToday(itemDate)
                ? "сегодня " + moment(itemDate).format("HH:mm")
                : moment(itemDate).format("DD/MMM/YYYY") + " " + moment(itemDate).format("HH:mm");
        },

        showCancel(order) {
            this.cancelingOrder = order;
            this.cancelDialog = true;
        },
        closeCancel() {
            this.cancelingOrder = null;
            this.cancelDialog = false;
        },
        cancelOrder(order_id) {
            this.cancelLoading = true;
            this.$http
                .put("call-center/order-cancel/" + order_id)
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.cancelLoading = false;
                    this.closeCancel();
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.cancelLoading = false;
                });
        },
    },
};
