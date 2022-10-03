<!-- @format -->

<template>
    <div v-if="order" class="px-4 pt-4" :style="{ height: height + 'px' }" style="overflow-y: auto">
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">Поездка</h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Откуда
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    {{ order.address_from | formatAdd }}
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Куда </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.address_to">{{ order.address_to | formatAdd }}</span>
                    <span v-else-if="order.completed && order.completed.destination_address">
                        {{ order.completed.destination_address | formatAdd }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">Дополнительные настройки</h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Опции </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.car_options.length">{{ commaJoin(order.car_options, "option") }}</span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Пасажир
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.passenger">{{ order.passenger | passengerTitle }}</span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Встреча
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <div v-if="order.meet">
                        <p class="mb-1">{{ order.meet | meetTitle }}</p>
                        <p class="mb-1">
                            <small class="mr-1">Дополнительная информация:</small>
                            {{ order.meet.info }}
                        </p>
                        <p class="mb-1">
                            <small class="mr-1">Текст таблички:</small>
                            {{ order.meet.text }}
                        </p>
                    </div>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Предзаказ
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <template v-if="order.preorder">
                        <small class="mr-1">Начало:</small>
                        <span class="mr-2">{{ getPreorderDate(order.preorder).time }}</span>
                        <v-chip outlined x-small :color="getPreorderDate(order.preorder).started ? 'success' : 'error'">
                            {{ getPreorderDate(order.preorder).started ? "начат" : "ожидание" }}
                        </v-chip>
                    </template>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Комментарий для водителя
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <small v-if="order.comments">{{ order.comments }}</small>
                    <v-chip v-else color="error" outlined x-small>нет комментария</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">Оплата</h4>
            <v-divider class="mx-0 mb-2 mt-0"></v-divider>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Тип оплаты
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    {{ order.payment_type.name }}
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Цена </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.completed">
                        {{ "₽ " + new Intl.NumberFormat("de-DE").format(order.completed.cost) }}
                    </span>
                    <!--                    <span v-else-if="!order.completed">-->
                    <!--                        {{ "₽ " + new Intl.NumberFormat("de-DE").format(order.completed.cost) }}-->
                    <!--                    </span>-->
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0">Имформация создания</h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Кто создал
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    {{ order.customer | customerTitle }}
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="2" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Создан
                </v-col>
                <v-col cols="12" md="10" class="font-weight-medium pl-2 d-flex align-center">
                    <small class="mr-1" v-if="isToday(new Date(order.created_at))">Сегодня:</small>
                    <span>{{ getDateShorted(order.created_at) }}</span>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6">
            <h4 class="font-weight-light mt-0 d-flex align-center"> Оператор </h4>
            <v-divider class="mx-0 mb-2 mt-0" />
            <div>
                <span v-if="order.operator" class="font-weight-medium">
                    {{ order.operator.surname }} {{ order.operator.name }} {{ order.operator.patronymic }}
                </span>
                <v-chip v-else color="error" outlined x-small>не назначен</v-chip>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment-timezone";
export default {
    props: {
        height: {
            required: true,
        },
        order: {
            required: true,
        },
        history: {
            required: true,
        },
    },

    data() {
        return {};
    },

    filters: {
        passengerTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic
                ? `${initials.join(" ").trim()}, телефон: ${client.phone}`
                : `Телефон: ${client.phone}`;
        },
        meetTitle(meet) {
            if (meet.place.airport_id) {
                return `Аэропорт ${meet.place.name}`;
            } else if (meet.place.metro_id) {
                return `Метро ${meet.place.name}`;
            } else if (meet.place.railway_station_id) {
                return `${meet.place.name} Вокзал`;
            }
        },
        customerTitle(customer) {
            if (customer.system_worker_id) {
                return `Работник ${customer.surname} ${customer.name} ${customer.patronymic}`;
            } else if (customer.admin_corporate_id) {
                return `Корпоративный админстратор ${customer.surname} ${customer.name} ${customer.patronymic}`;
            } else if (customer.client_id) {
                let name =
                    customer.surname || customer.name || customer.patronymic
                        ? `${customer.surname} ${customer.name} ${customer.patronymic}`
                        : customer.phone;
                return `Клиент ${name}`;
            }
        },
    },
    methods: {
        /*data transform*/
        getPreorderDate(preorder) {
            let timeOrder = moment().format("DD-MM-YYYY HH:mm");
            let timeLocal = moment().format("DD-MM-YYYY HH:mm");
            let dt1 = new Date(timeOrder);
            let dt2 = new Date(timeLocal);
            let minutesDiff = (dt1.getTime() - dt2.getTime()) / 60000;

            let localStartTme = moment(new Date(preorder.time))
                .add(minutesDiff, "m")
                .format("YYYY-MM-DD HH:mm");

            return {
                started: new Date() > Date.parse(localStartTme),
                time: localStartTme,
            };
        },
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
                ? moment(itemDate).format("HH:mm")
                : moment(itemDate).format("DD/MMM/YYYY") + " " + moment(itemDate).format("HH:mm");
        },
        /*___________*/
    },
};
</script>
