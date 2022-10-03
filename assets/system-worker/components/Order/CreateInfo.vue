<!-- @format -->

<template>
    <div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Поездка"></info-subtitle>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Откуда
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">{{
                    order.address_from
                }}</v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Куда </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.address_to">{{ order.address_to }}</span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Дополнительные настройки"></info-subtitle>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Опции </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.car_options.length">{{ __commaJoin(order.car_options, "option") }}</span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Пасажир
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.passenger">{{ order.passenger | passengerTitle }}</span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Встреча
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
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
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Предзаказ
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <template v-if="order.preorder">
                        <small class="mr-1">Начало:</small>
                        <span class="mr-2">{{ getPreorderDate(order.preorder).time }}</span>
                        <v-chip
                            outlined
                            x-small
                            :color="__getPreorderDate(order.preorder).started ? 'success' : 'error'"
                        >
                            {{ __getPreorderDate(order.preorder).started ? "начат" : "ожидание" }}
                        </v-chip>
                    </template>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Комментарий для водителя
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.comments">{{ order.comments }}</span>
                    <v-chip v-else color="error" outlined x-small>нет комментария</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Оплата"></info-subtitle>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Тип оплаты
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    {{ order.payment_type.name }}
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)"> Цена </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <span v-if="order.completed">
                        {{ __priceFormat(order.completed.cost) }}
                    </span>
                    <v-chip v-else color="error" outlined x-small>не установлено</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Имформация создания"></info-subtitle>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Кто создал
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    {{ order.customer | customerTitle }}
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-1 mt-0">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Создан
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 d-flex align-center">
                    <small class="mr-1" v-if="__isToday(new Date(order.created_at))">Сегодня:</small>
                    <span>{{ __getDateShorted(order.created_at) }}</span>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Оператор"></info-subtitle>
            <div>
                <span v-if="order.operator" class="font-weight-medium">
                    {{ order.operator.surname }}
                    {{ order.operator.name }}
                    {{ order.operator.patronymic }}
                </span>
                <v-chip v-else color="error" outlined x-small>не назначен</v-chip>
            </div>
        </div>
    </div>
</template>

<script>
import { mutators } from "../../mixins/Mutators";
import InfoSubtitle from "./InfoSubtitle";

export default {
    name: "CreateInfo",
    components: { InfoSubtitle },
    props: {
        order: {
            required: true,
        },
    },
    mixins: [mutators],
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
                return `Клинет ${name}`;
            }
        },
    },
};
</script>
