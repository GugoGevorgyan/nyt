<!-- @format -->

<template>
    <div>
        <v-data-table
            :loading="loading"
            :headers="headers"
            :items="orders"
            multi-sort
            hide-default-footer
            :single-expand="true"
            :expanded.sync="expanded"
            show-expand
            item-key="order_id"
            dense
        >
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" class="pa-0 elevation-10">
                    <v-simple-table dense class="rounded-0">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Параметры автомобиля</th>
                                    <th>Пасажир</th>
                                    <th>Платформа</th>
                                    <th>Предзаказ</th>
                                    <th>Комментарий</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span v-if="item.car_options.length">{{
                                            commaJoin(item.car_options, "option")
                                        }}</span>
                                        <div v-else class="text-center">-</div>
                                    </td>
                                    <td>
                                        <span v-if="item.passenger">{{ item.passenger | passengerTitle }}</span>
                                        <div v-else class="text-center">-</div>
                                    </td>
                                    <td>
                                        <span v-if="item.platform">{{ item.platform }}</span>
                                        <div v-else class="text-center">-</div>
                                    </td>
                                    <td>
                                        <span v-if="Number(item.preorder)">{{ item.preorder + " мин." }}</span>
                                        <div v-else class="text-center">-</div>
                                    </td>
                                    <td>
                                        <div style="max-width: 250px" class="pa-1" v-if="item.comments">{{
                                            item.comments
                                        }}</div>
                                        <div v-else class="text-center">-</div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </td>
            </template>

            <template v-slot:item.status="{ item }">
                <div class="d-flex align-center">
                    <v-icon small :color="item.status.color">mdi-record</v-icon>
                    <span class="ml-1 small">{{ item.status.text }}</span>
                </div>
            </template>
            <template v-slot:item.address_from="{ item }">
                <span class="small">{{ item.address_from | formatAdd }}</span>
            </template>
            <template v-slot:item.address_to="{ item }">
                <span class="small" v-if="item.address_to">{{ item.address_to | formatAdd }}</span>
                <div v-else class="text-center">-</div>
            </template>
            <template v-slot:item.board="{ item }">
                <v-menu
                    v-if="item.driver"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <span>
                                {{ item.driver.car.garage_number }}
                            </span>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon small>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title>Водитель:</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ item.driver.driver_info.patronymic }} {{ item.driver.driver_info.name }}
                                    {{ item.driver.driver_info.surname }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title>Телефон:</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ item.driver.phone }}
                                    <v-btn small icon color="success" @click="$emit('call', item.driver.phone)">
                                        <v-icon small>mdi-phone</v-icon>
                                    </v-btn>
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title>Автомобиль:</v-list-item-title>
                                <v-list-item-subtitle
                                    >{{ item.driver.car.mark }} {{ item.driver.car.model }}</v-list-item-subtitle
                                >
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title>Государственный номерной знак:</v-list-item-title>
                                <v-list-item-subtitle>{{ item.driver.car.state_license_plate }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">-</div>
            </template>
            <template v-slot:item.cost="{ item }">
                <span class="small" v-if="item.completed">{{
                    "₽ " + new Intl.NumberFormat("de-DE").format(item.completed.cost)
                }}</span>
                <div v-else class="text-center">-</div>
            </template>
            <template v-slot:item.created_at="{ item }">
                {{ getDateShorted(item.created_at) }}
            </template>
            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            @click="$emit('copy', item)"
                            :disabled="!!client.in_order"
                            v-bind="attrs"
                            v-on="on"
                            icon
                            color="primary"
                            x-small
                            class="mr-2"
                        >
                            <v-icon v-text="'mdi-content-copy'" small/>
                        </v-btn>
                    </template>
                    <small>Скопировать заказ</small>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-if="!item.canceled && !item.completed"
                            @click="showCancel(item)"
                            v-bind="attrs"
                            v-on="on"
                            icon
                            color="error"
                            x-small
                        >
                            <v-icon small>mdi-close</v-icon>
                        </v-btn>
                    </template>
                    <small>Отменить заказ</small>
                </v-tooltip>
            </template>
        </v-data-table>

        <!--Cancel dialog-->
        <v-dialog v-model="cancelDialog" max-width="400" width="100%">
            <v-card flat v-if="cancelingOrder">
                <v-card-text class="pa-4 text-center">
                    <span style="font-size: 20px">
                        Вы уверены, что хотите отменить заказ номер: {{ cancelingOrder.order_id }}?
                    </span>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn :disabled="cancelLoading" small color="primary" @click="closeCancel()">нет</v-btn>
                    <v-btn :loading="cancelLoading" small color="error" @click="cancelOrder(cancelingOrder.order_id)">
                        да
                    </v-btn>
                    <v-spacer />
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="js" src="./LastOrders.main.js" />
