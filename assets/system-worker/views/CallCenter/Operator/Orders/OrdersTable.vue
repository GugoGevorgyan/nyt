<!-- @format -->

<template>
    <div>
        <v-data-table
            :loader-height="2"
            :height="tableHeight"
            :fixed-header="true"
            :headers="full ? paginated.headersFull : paginated.headers"
            :items="paginated.data"
            :items-per-page="Number(paginated.per_page)"
            :loading="paginated.loading"
            hide-default-footer
            item-key="order_id"
            dense
            :item-class="rowClasses"
            @click:row="showInfo"
            :dark="darkMode"
        >
            <template v-slot:no-data>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium mt-10">Заказов ещё нет !</h1>
                </div>
            </template>

            <template v-slot:loading>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium mt-10">Загрузка...</h1>
                </div>
            </template>

            <!--Header-->
            <template v-slot:top>
                <div ref="toolbar" class="py-1">
                    <v-row no-gutters class="mb-5">
                        <v-col cols="12" :md="full ? 2 : 3" :class="full ? 'px-2' : 'px-1'">
                            <v-text-field
                                clearable
                                append-icon="mdi-magnify"
                                color="yellow darken-3"
                                hide-details
                                label="Поиск"
                                dense
                                single-line
                                v-model="paginated.search"
                            />
                        </v-col>
                        <v-col cols="12" :md="full ? 2 : 3" :class="full ? 'px-2' : 'px-1'">
                            <v-select
                                color="yellow darken-3"
                                clearable
                                label="Статус заказа"
                                :items="statuses"
                                item-text="text"
                                item-value="order_status_id"
                                v-model="paginated.status"
                                item-color="yellow darken-3"
                                menu-props="offsetY"
                                dense
                                single-line
                                hide-details
                            />
                        </v-col>
                        <v-col cols="12" :md="full ? 2 : 3" :class="full ? 'px-2' : 'px-1'">
                            <v-select
                                color="yellow darken-3"
                                clearable
                                label="Тип заказа"
                                :items="types"
                                item-text="text"
                                item-value="order_type_id"
                                v-model="paginated.type"
                                item-color="yellow darken-3"
                                menu-props="offsetY"
                                dense
                                single-line
                                hide-details
                            />
                        </v-col>
                        <v-col cols="12" :md="full ? 6 : 3" :class="full ? 'px-2' : 'px-1'">
                            <div class="d-flex justify-end align-center">
                                <v-chip
                                    v-if="newItems.length"
                                    small
                                    class="mr-2 elevation-5"
                                    close
                                    color="error"
                                    text-color="white"
                                    @click:close="dischargeNewItems()"
                                >
                                    <v-avatar left>
                                        {{ newItems.length }}
                                    </v-avatar>
                                    новые заказы
                                </v-chip>
                                <v-btn small icon color="grey darken-2" @click="$emit('full', !full)">
                                    <v-icon
                                        v-text="
                                            full
                                                ? 'mdi-arrow-left-bold-circle-outline'
                                                : 'mdi-arrow-right-bold-circle-outline'
                                        "
                                    />
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </template>

            <!--Content-->
            <template v-slot:item.status="{ item }">
                <div class="d-flex" style="width: max-content">
                    <v-icon x-small :color="item.status.color" :dark="darkMode" class="mr-2" v-text="'mdi-record'" />
                    <span class="small" :class="darkMode ? 'white--text' : 'black--text'">{{ item.status.text }}</span>
                </div>
            </template>
            <template v-slot:item.car_class="{ item }">
                <small
                    v-if="item.car_class"
                    class="d-flex"
                    style="width: max-content"
                    :class="darkMode ? 'white--text' : 'black--text'"
                    >{{ item.car_class.class_name }}</small
                >
            </template>
            <template v-slot:item.payment_type="{ item }">
                <small v-if="item.payment_type" :class="darkMode ? 'white--text' : 'black--text'">{{
                    item.payment_type.name
                }}</small>
            </template>
            <template v-slot:item.order_type="{ item }">
                <small :class="darkMode ? 'white--text' : 'black--text'">{{ item.order_type.text }}</small>
            </template>
            <template v-slot:item.car_options="{ item }" :class="darkMode ? 'white--text' : 'black--text'">
                <v-menu
                    v-if="item.car_options.length"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <small
                                class="cut-text mr-1"
                                style="width: 70px"
                                :class="darkMode ? 'white--text' : 'black--text'"
                                >{{ commaJoin(item.car_options, "option") }}</small
                            >
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content :class="darkMode ? 'white--text' : 'black--text'">{{
                                commaJoin(item.car_options, "option")
                            }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.client="{ item }">
                <div class="d-flex align-center justify-space-between">
                    <small
                        :class="darkMode ? 'white--text' : 'black--text'"
                        v-if="item.client.surname || item.client.name || item.client.patronymic"
                    >
                        {{ item.client.surname }} {{ item.client.name }} {{ item.client.patronymic }}
                    </small>
                    <small :class="darkMode ? 'white--text' : 'black--text'" v-else>{{ item.client.phone }}</small>
                    <v-btn class="ml-1" small icon color="success" @click="$emit('call', item.client.phone)">
                        <v-icon small>mdi-phone</v-icon>
                    </v-btn>
                </div>
            </template>
            <template v-slot:item.passenger="{ item }">
                <div class="d-flex align-center" v-if="item.passenger">
                    <span :class="darkMode ? 'white--text' : 'black--text'" class="small mr-1">{{
                        item.passenger.phone
                    }}</span>
                    <v-btn small icon color="success" @click="$emit('call', item.passenger.phone)">
                        <v-icon small>mdi-phone</v-icon>
                    </v-btn>
                </div>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.meet="{ item }">
                <v-menu
                    v-if="item.meet"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <small class="mr-1">{{ item.meet.place.name }}</small>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon
                                    :dark="!darkMode"
                                    :color="darkMode ? 'white' : 'black'"
                                    v-text="'mdi-information-outline'"
                                />
                            </v-btn>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title v-if="item.meet.place.airport_id">
                                    Аэропорт {{ item.meet.place.name }}
                                </v-list-item-title>
                                <v-list-item-title v-if="item.meet.place.metro_id">
                                    Метро {{ item.meet.place.name }}
                                </v-list-item-title>
                                <v-list-item-title v-if="item.meet.place.railway_station_id">
                                    {{ item.meet.place.name }} Вокзал
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line v-if="item.meet.info">
                            <v-list-item-content>
                                <v-list-item-title>Дополнительная информация:</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ item.meet.info }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line v-if="item.meet.text">
                            <v-list-item-content>
                                <v-list-item-title>Текст таблички:</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ item.meet.text }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.preorder="{ item }">
                <v-menu
                    v-if="item.preorder"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center">
                            <v-chip
                                class="mr-1"
                                outlined
                                x-small
                                :color="getPreorderDate(item.preorder).started ? 'success' : 'error'"
                            >
                                {{ getPreorderDate(item.preorder).started ? "Начат" : "Ожидание" }}
                            </v-chip>
                            <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title>Начало:</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ getPreorderDate(item.preorder).time }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.address_from="{ item }">
                <v-menu
                    v-if="item.address_from"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <small class="cut-text mr-1" style="width: 70px">{{
                                    item.address_from | formatAdd(2)
                                }}</small>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content>{{ item.address_from | formatAdd(2) }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.address_to="{ item }">
                <v-menu
                    v-if="item.address_to"
                    transition="slide-x-transition"
                    bottom
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <small class="cut-text mr-1" style="width: 70px">{{
                                    item.address_to | formatAdd(2)
                                }}</small>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content>{{ item.address_to | formatAdd(2) }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
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
                        <div class="d-flex justify-center align-center" style="width: 100%">
                            <span class="small mr-1">
                                {{ item.driver.car.garage_number }}
                            </span>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <div style="background-color: white">
                        <div class="d-flex pa-2">
                            <v-list class="pa-0">
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
                            </v-list>
                            <v-img class="mt-2" width="100" height="100" :src="item.driver.driver_info.photo"></v-img>
                        </div>
                        <v-divider class="ma-0"></v-divider>
                        <v-list class="pa-0">
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
                                    <v-list-item-subtitle>{{
                                        item.driver.car.state_license_plate
                                    }}</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-menu>

                <template v-else-if="1 === item.status.status">
                    <v-chip v-if="item.common && item.common.emergency" class="blink" dark x-small color="#D32F2F">
                        Ожидание {{ item.created_at | leftTime(currentTime) }}
                    </v-chip>

                    <v-chip v-else-if="item.common" dark x-small color="#E65100">
                        Ожидание {{ item.created_at | leftTime(currentTime) }}
                    </v-chip>

                    <v-chip v-else dark x-small color="#F9A825">
                        Ожидание {{ item.created_at | leftTime(currentTime) }}
                    </v-chip>
                </template>

                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.price="{ item }">
                <v-chip small color="error" outlined v-if="item.process && item.process.price >= fatPrice">
                    {{ "₽ " + new Intl.NumberFormat("de-DE").format(item.process.price) }}
                </v-chip>
                <span v-else-if="item.process" style="white-space: nowrap">
                    {{ "₽ " + new Intl.NumberFormat("de-DE").format(item.process.price) }}
                </span>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.cost="{ item }">
                <span v-if="item.completed" style="white-space: nowrap">{{
                    "₽ " + new Intl.NumberFormat("de-DE").format(item.completed.cost)
                }}</span>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.more="{ item }">
                <v-menu transition="slide-x-transition" bottom right offset-x :close-on-content-click="false">
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-chevron-double-right</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-subtitle>Коментарий:</v-list-item-subtitle>
                                <div>
                                    <span v-if="item.comments" class="small">{{ item.comments }}</span>
                                    <div v-else class="text-center">
                                        <span>-</span>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-subtitle>Кто создал:</v-list-item-subtitle>
                                <v-list-item-title v-html="customer(item)"> </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-subtitle>Создан:</v-list-item-subtitle>
                                <v-list-item-title>
                                    <div v-if="item.created_at">
                                        <span class="font-weight-bold">{{ getDateShorted(item.created_at) }}</span>
                                    </div>
                                    <div v-else class="text-center">
                                        <span>-</span>
                                    </div>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>

            <!--Footer-->
            <template v-slot:footer>
                <table-footer :paginated="paginated" />
            </template>
        </v-data-table>

        <!--AllTransaction info dialog-->
        <v-dialog max-width="1500" width="100%" persistent v-model="infoOrderDialog" overlay-opacity="0.7">
            <order-info-dialog
                :order="infoOrder"
                :height="tableHeight"
                @call="$emit('call', $event)"
                @close="closeInfo()"
            />
        </v-dialog>
    </div>
</template>
<script lang="js" src="./OrdersTable.main.js" />

<style scoped>
.cut-text {
    text-overflow: ellipsis;
    overflow: hidden;
    width: 160px;
    height: 1.2em;
    white-space: nowrap;
}
</style>
