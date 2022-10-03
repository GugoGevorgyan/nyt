<!-- @format -->

<template>
    <v-container fluid>
        <v-card tile elevation="4">
            <v-data-table
                loader-height="2"
                dense
                :fixed-header="true"
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="car_id"
                :calculate-widths="true"
                :height="window.height"
                disable-sort
            >
                <!--HEADER-->
                <template v-slot:top>
                    <v-toolbar flat color="grey lighten-3">
                        <v-toolbar-title class="mr-5">Жалобы работников</v-toolbar-title>
                        <v-spacer />
                        <v-text-field
                            append-icon="mdi-magnify"
                            clearable
                            color="yellow darken-3"
                            background-color="white"
                            hide-details
                            label="Поиск"
                            single-line
                            solo
                            v-model="paginated.search"
                        />
                        <v-spacer />
                        <v-select
                            style="max-width: 300px"
                            :items="statuses"
                            clearable
                            color="yellow darken-3"
                            background-color="white"
                            item-text="text"
                            item-value="complaint_status_id"
                            label="Статус"
                            v-model="paginated.status"
                            dense
                            outlined
                            hide-details
                        >
                        </v-select>
                    </v-toolbar>
                </template>

                <!--Content-->
                <template v-slot:item.writer="{ item }">
                    <div class="d-flex justify-space-between">
                        <small>{{ item.writer.name }} {{ item.writer.patronymic }} {{ item.writer.surname }}</small>
                        <template>
                            <v-menu v-if="item.writer.photo" offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon color="grey darken-2" v-bind="attrs" v-on="on">
                                        <v-icon small dark>mdi-camera-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-img width="200" :src="item.writer.photo"></v-img>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.recipient="{ item }">
                    <div class="d-flex justify-space-between">
                        <small
                            >{{ item.recipient.name }} {{ item.recipient.patronymic }}
                            {{ item.recipient.surname }}</small
                        >
                        <template>
                            <v-menu v-if="item.recipient.photo" offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon color="grey darken-2" v-bind="attrs" v-on="on">
                                        <v-icon small dark>mdi-camera-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-img width="200" :src="item.recipient.photo"></v-img>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.order="{ item }">
                    <div v-if="item.order" class="d-flex justify-space-between align-center">
                        <div class="d-flex align-center">
                            <div style="white-space: nowrap" class="mr-1 pr-1"># {{ item.order.order_id }}</div>
                            <small style="white-space: nowrap; width: 150px; overflow: hidden; text-overflow: ellipsis">
                                Из {{ item.order.address_from
                                }}{{ item.order.address_to ? " до " + item.order.address_to : "" }}
                            </small>
                        </div>
                        <template>
                            <v-menu offset-x :close-on-content-click="false" max-width="300" max-height="500">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Откуда:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.address_from }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Куда:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.address_to || "-" }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Тип оплаты:</small>
                                            <v-divider />
                                            <span v-if="item.order.payment_type">{{
                                                item.order.payment_type.name
                                            }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Цена:</small>
                                            <v-divider></v-divider>
                                            <span>
                                                {{
                                                    item.order.completed
                                                        ? "₽ " +
                                                          new Intl.NumberFormat("de-DE").format(
                                                              item.order.completed.cost,
                                                          )
                                                        : "-"
                                                }}
                                            </span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Класс:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.car_class.class_name }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Опции:</small>
                                            <v-divider></v-divider>
                                            <span>{{
                                                item.order.car_options
                                                    ? commaJoin(item.order.car_options, "option")
                                                    : "-"
                                            }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Встреча:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.meet ? item.order.meet.place.name : "-" }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Пасажир:</small>
                                            <v-divider></v-divider>
                                            <div v-if="item.order.passenger" class="d-flex align-center">
                                                <span class="mr-2">
                                                    {{ item.order.passenger.surname }}
                                                    {{ item.order.passenger.name }}
                                                    {{ item.order.passenger.patronymic }}
                                                </span>
                                                <span>Тел. {{ item.order.passenger.phone }}</span>
                                            </div>
                                            <span v-else>-</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Оператор:</small>
                                            <v-divider></v-divider>
                                            <span v-if="item.order.customer_type === 'workerOperator'"> Оператор </span>
                                            <span v-else>-</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Комментарий:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.comments || "-" }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                    <v-chip v-else color="error" outlined x-small>не звязана с заказом</v-chip>
                </template>
                <template v-slot:item.status="{ item }">
                    <v-chip outlined x-small :color="item.status.color">{{ item.status.text }}</v-chip>
                </template>
                <template v-slot:item.subject="{ item }">
                    <div class="d-flex justify-space-between align-center">
                        <small
                            style="max-width: 160px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis"
                            >{{ item.subject }}</small
                        >
                        <template>
                            <v-menu offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list max-width="300">
                                    <v-list-item>{{ item.subject }}</v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.complaint="{ item }">
                    <div class="d-flex justify-space-between align-center">
                        <small
                            style="max-width: 360px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis"
                            >{{ item.complaint }}</small
                        >
                        <template>
                            <v-menu offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list max-width="300">
                                    <v-list-item>{{ item.complaint }}</v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span class="mr-2">{{ item.created_at | formatDate }}</span>
                    <small>{{ item.created_at | formTime }}</small>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn small icon v-on="on" @click="showComplaint(item)">
                                <v-icon small color="primary">mdi-eye</v-icon>
                            </v-btn>
                        </template>
                        <span>Показать</span>
                    </v-tooltip>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated"/>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog max-width="1400" width="100%" v-model="dialog" persistent>
            <complaint :complaint="complaint" :statuses="statuses" :manager="true" @close="closeComplaint()" />
        </v-dialog>
    </v-container>
</template>
<script lang="js" src="./index.main.js" />
