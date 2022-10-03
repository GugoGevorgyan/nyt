<!-- @format -->

<template>
    <v-container fluid>
        <v-card tile elevation="6">
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
                    <v-container fluid ref="header" class="pt-0 grey lighten-5">
                        <v-row no-gutters>
                            <v-col cols="12" md="3" class="d-flex align-center">
                                <v-toolbar-title>Отзывы</v-toolbar-title>
                            </v-col>
                            <v-col cols="12" md="3" class="d-flex align-center">
                                <form style="width: 100%" @submit.prevent="search()">
                                    <v-text-field
                                        v-model="searchText"
                                        label="Поиск"
                                        color="yellow darken-3"
                                        item-color="yellow darken-3"
                                        single-line
                                        hide-details
                                        clearable
                                    />
                                </form>
                                <v-btn
                                    :disabled="!searchText"
                                    depressed
                                    x-small
                                    fab
                                    @click="search()"
                                    color="yellow darken-3"
                                    class="ml-2 mb-0 mt-3"
                                >
                                    <v-icon color="white">mdi-magnify</v-icon>
                                </v-btn>
                                <v-divider class="mx-4" vertical />
                            </v-col>
                            <v-col cols="12" md="2" class="d-flex">
                                <v-select
                                    v-model="paginated.writer"
                                    menu-props="auto"
                                    hide-details
                                    :items="writers"
                                    item-text="text"
                                    item-value="value"
                                    label="Кто оставил"
                                    color="yellow darken-3"
                                    item-color="yellow darken-3"
                                    single-line
                                    clearable
                                    multiple
                                    small-chips
                                    deletable-chips
                                />
                                <v-divider class="mx-4" vertical />
                            </v-col>
                            <v-col cols="12" md="2" class="d-flex">
                                <v-select
                                    :items="types"
                                    item-text="text"
                                    item-value="value"
                                    label="Тип отзыва"
                                    v-model="paginated.type"
                                    color="yellow darken-3"
                                    item-color="yellow darken-3"
                                    menu-props="auto"
                                    clearable
                                    single-line
                                    hide-details
                                    multiple
                                    small-chips
                                    deletable-chips
                                />
                                <v-divider class="mx-4" vertical />
                            </v-col>
                            <v-col cols="12" md="2" class="d-flex">
                                <v-select
                                    :items="orderStatuses"
                                    item-text="text"
                                    item-value="value"
                                    label="Статус заказа"
                                    v-model="paginated.status"
                                    color="yellow darken-3"
                                    item-color="yellow darken-3"
                                    menu-props="auto"
                                    clearable
                                    single-line
                                    hide-details
                                >
                                </v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-divider />
                </template>

                <!--Content-->
                <template v-slot:item.order="{ item }">
                    <div class="d-flex justify-space-between align-center">
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
                                        <v-icon small v-text="'mdi-information-outline'" />
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
                </template>
                <template v-slot:item.client="{ item }">
                    <div class="d-flex justify-space-between align-center">
                        <small
                            v-if="item.order.client.name || item.order.client.surname || item.order.client.patronymic"
                        >
                            {{ item.order.client.name }}
                            {{ item.order.client.patronymic }}
                            {{ item.order.client.surname }}
                        </small>
                        <small v-else>{{ item.order.client.phone }}</small>
                        <template>
                            <v-menu offset-x :close-on-content-click="false">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>ФИО:</small>
                                            <v-divider></v-divider>
                                            <span
                                                v-if="
                                                    item.order.client.name ||
                                                    item.order.client.surname ||
                                                    item.order.client.patronymic
                                                "
                                            >
                                                {{ item.order.client.surname }}
                                                {{ item.order.client.name }}
                                                {{ item.order.client.patronymic }}
                                            </span>
                                            <span v-else>-</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Телефон:</small>
                                            <v-divider></v-divider>
                                            <span>
                                                {{ item.order.client.phone }}
                                            </span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Эл. адрес:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.client.email || "-" }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Завершенных заказов:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.client.completed_orders_count }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Отмененных заказов:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.order.client.canceled_orders_count }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.driver="{ item }">
                    <div v-if="item.driver" class="d-flex justify-space-between align-center">
                        <small>
                            {{ item.driver.driver_info.name }}
                            {{ item.driver.driver_info.patronymic }}
                            {{ item.driver.driver_info.surname }}
                        </small>
                        <template>
                            <v-menu offset-x :close-on-content-click="false">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item v-if="item.driver.driver_info.photo">
                                        <v-img
                                            max-height="200"
                                            max-width="300"
                                            contain
                                            :src="item.driver.driver_info.photo"
                                        ></v-img>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>ФИО:</small>
                                            <v-divider></v-divider>
                                            <span>
                                                {{ item.driver.driver_info.name }}
                                                {{ item.driver.driver_info.patronymic }}
                                                {{ item.driver.driver_info.surname }}
                                            </span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Телефон:</small>
                                            <v-divider></v-divider>
                                            <span>
                                                {{ item.driver.phone }}
                                            </span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Эл. адрес:</small>
                                            <v-divider></v-divider>
                                            <span v-if="item.driver.driver_info.email">
                                                {{ item.driver.driver_info.email }}
                                            </span>
                                            <span v-else>-</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Рейтинг:</small>
                                            <v-divider></v-divider>
                                            <v-rating
                                                dense
                                                small
                                                readonly
                                                :value="item.driver.mean_assessment"
                                                :color="assessmentColor(item.driver.mean_assessment)"
                                                :background-color="assessmentColor(item.driver.mean_assessment)"
                                            />
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                    <div v-else class="text-center">-</div>
                </template>
                <template v-slot:item.board="{ item }">
                    <div v-if="item.car" class="d-flex justify-space-between align-center">
                        <small>
                            {{ item.car.mark }}
                            {{ item.car.model }}
                        </small>
                        <template>
                            <v-menu offset-x :close-on-content-click="false">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Автомобиль:</small>
                                            <v-divider></v-divider>
                                            <div class="d-flex align-center">
                                                <span class="mr-2">
                                                    {{ item.car.mark }}
                                                    {{ item.car.model }}
                                                </span>
                                                <div
                                                    :style="{ 'background-color': item.car.color }"
                                                    class="elevation-7 mr-2"
                                                    style="height: 13px; width: 13px; border-radius: 50%"
                                                ></div>
                                                <span>{{ item.car.color }}</span>
                                            </div>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Номер борта:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.car.garage_number }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Парк:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.car.park.name }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Государственный номерной знак:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.car.state_license_plate }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <small>Дата выпуска:</small>
                                            <v-divider></v-divider>
                                            <span>{{ item.car.year }}</span>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                    <div v-else class="text-center">-</div>
                </template>
                <template v-slot:item.type="{ item }">
                    <v-chip v-if="item.type" outlined x-small :color="item.type.color">{{ item.type.text }}</v-chip>
                    <small v-else>без оценки</small>
                </template>
                <template v-slot:item.text="{ item }">
                    <div v-if="item.text" class="d-flex justify-space-between align-center" style="max-width: 250px">
                        <small
                            style="max-width: 250px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis"
                            >{{ item.text }}</small
                        >
                        <template>
                            <v-menu offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list max-width="300">
                                    <v-list-item>{{ item.text }}</v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                    <small v-else-if="item.option">{{ item.option.name }}</small>
                </template>
                <template v-slot:item.assessment="{ item }">
                    <v-rating
                        dense
                        small
                        readonly
                        :value="item.assessment"
                        :color="item.assessment ? assessmentColor(item.assessment) : 'gray'"
                        :background-color="item.assessment ? assessmentColor(item.assessment) : 'gray'"
                    />
                </template>
                <template v-slot:item.writer="{ item }">
                    <small v-if="item.writable.client_id">Клиент</small>
                    <small v-else-if="item.writable.driver_id">Водитель</small>
                    <div v-else-if="item.writable.system_worker_id" class="d-flex justify-space-between align-center">
                        <small>Работник</small>
                        <template>
                            <v-menu offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-information-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-list max-width="300">
                                    <v-list-item>
                                        {{ item.writable.surname }}
                                        {{ item.writable.name }}
                                        {{ item.writable.patronymic }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.reader="{ item }">
                    <template v-if="item.readable">
                        <small v-if="item.readable.client_id">Клиент</small>
                        <small v-else-if="item.readable.driver_id">Водитель</small>
                        <small v-else-if="item.readable.system_worker_id">
                            Работник: {{ item.readable.surname }}
                            {{ item.readable.name }}
                            {{ item.readable.patronymic }}
                        </small>
                    </template>
                    <div v-else class="text-center">-</div>
                </template>
                <template v-slot:item.cost="{ item }">
                    <span v-if="item.order.completed">
                        {{ "₽ " + new Intl.NumberFormat("de-DE").format(item.order.completed.cost) }}
                    </span>
                    <div v-else class="text-center">-</div>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span class="mr-2">{{ item.created_at | formatDate }}</span>
                    <small>{{ item.created_at | formatTime }}</small>
                </template>
                <template v-slot:item.status="{ item }">
                    <v-chip outlined x-small :color="item.order_status.color">{{ item.order_status.text }}</v-chip>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script src="./Index.main.js" />
