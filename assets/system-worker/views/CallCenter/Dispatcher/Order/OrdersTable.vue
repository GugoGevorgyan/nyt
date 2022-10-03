<!-- @format -->

<template>
    <div>
        <v-data-table
            dense
            v-model="paginated.selected"
            :dark="darkMode"
            :loader-height="2"
            :height="tableHeight"
            :fixed-header="true"
            :headers="paginated.headers"
            :items="paginated._payload"
            :items-per-page="Number(paginated.per_page)"
            :loading="paginated.loading"
            hide-default-footer
            item-key="order_id"
            :item-class="rowClasses"
            @dblclick:row="dblClickRow"
            @contextmenu:row="rightClickRow"
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
                <div ref="toolbar" class="px-2">
                    <v-row>
                        <v-col cols="12" md="2">
                            <v-text-field
                                clearable
                                prepend-inner-icon="mdi-magnify"
                                color="yellow darken-3"
                                hide-details
                                label="Поиск"
                                dense
                                single-line
                                v-model="paginated.search"
                            />
                        </v-col>
                        <v-col cols="12" md="2">
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
                        <v-col cols="12" md="2">
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
                        <v-col cols="12" md="2">
                            <el-date-picker
                                class="custom-input"
                                start-placeholder="Дата от"
                                end-placeholder="Дата до"
                                type="daterange"
                                v-model="datePicker"
                            />
                        </v-col>
                        <v-spacer />
                        <v-col cols="12" md="3">
                            <div class="d-flex justify-end align-center">
                                <v-chip
                                    v-if="newItems.length"
                                    small
                                    class="mr-2"
                                    close
                                    color="error"
                                    text-color="white"
                                    @click:close="dischargeNewItems()"
                                >
                                    <v-avatar left>
                                        {{ newItems.length }}
                                    </v-avatar>
                                    Есть новые заказы
                                </v-chip>
                                <v-chip
                                    small
                                    @click="addOrders = !addOrders"
                                    :color="addOrders ? 'success' : 'error'"
                                    :dark="darkMode"
                                >
                                    <v-avatar class="pointer">
                                        <v-icon small>{{ addOrders ? "mdi-pause" : "mdi-play" }}</v-icon>
                                    </v-avatar>
                                    Новые заказы
                                </v-chip>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <v-divider />
            </template>

            <!--Content-->
            <template v-slot:item.order_id="{ item }">
                <span class="small">{{ item.order_id }}</span>
            </template>
            <template v-slot:item.status="{ item }">
                <div class="d-flex" style="width: max-content">
                    <v-icon x-small :color="item.status.color" class="mr-2">mdi-record</v-icon>
                    <span class="small">{{ item.status.text }}</span>
                </div>
            </template>
            <template v-slot:item.car_class="{ item }">
                <small v-if="item.car_class" class="d-flex" style="width: max-content">{{
                    item.car_class.class_name
                }}</small>
            </template>
            <template v-slot:item.payment_type="{ item }">
                <small v-if="item.payment_type">{{ item.payment_type.name }}</small>
            </template>
            <template v-slot:item.order_type="{ item }">
                <small>{{ item.order_type.text }}</small>
            </template>
            <template v-slot:item.car_options="{ item }">
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
                            <small class="cut-text mr-1" style="width: 70px">
                                {{ commaJoin(item.car_options, "option") }}
                            </small>
                            <v-btn x-small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <v-list max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content>{{ commaJoin(item.car_options, "option") }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <div v-else class="text-center">
                    <span>-</span>
                </div>
            </template>
            <template v-slot:item.client="{ item }">
                <div v-if="item.client" class="d-flex align-center justify-space-between">
                    <small v-if="item.client.surname || item.client.name || item.client.patronymic">
                        {{ item.client.surname }} {{ item.client.name }} {{ item.client.patronymic }}
                    </small>
                    <small v-else>{{ item.client.phone }}</small>
                    <v-btn class="ml-1" small icon color="success" @click.prevent="$emit('call', item.client.phone)">
                        <v-icon small>mdi-phone</v-icon>
                    </v-btn>
                </div>
            </template>
            <template v-slot:item.passenger="{ item }">
                <div class="d-flex align-center" v-if="item.passenger">
                    <span class="small mr-1">{{ item.passenger.phone }}</span>
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
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title v-if="item.meet.place.airport_id"
                                    >Аэропорт {{ item.meet.place.name }}</v-list-item-title
                                >
                                <v-list-item-title v-if="item.meet.place.metro_id"
                                    >Метро {{ item.meet.place.name }}</v-list-item-title
                                >
                                <v-list-item-title v-if="item.meet.place.railway_station_id"
                                    >{{ item.meet.place.name }} Вокзал</v-list-item-title
                                >
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
                                :color="getPreorderDate(item.preorder).distribution_start ? 'success' : 'error'"
                            >
                                {{ getPreorderDate(item.preorder).distribution_start ? "Начат" : "Ожидание" }}
                            </v-chip>
                            <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                <v-icon v-text="'mdi-information-outline'" />
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
                                <v-icon v-text="'mdi-information-outline'" />
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
                            <span class="small mr-1" v-if="item.driver && item.driver.car">
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
                                    <v-list-item-subtitle v-if="item.driver.car">
                                        {{ item.driver.car.mark }} {{ item.driver.car.model }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item two-line>
                                <v-list-item-content>
                                    <v-list-item-title>Государственный номерной знак:</v-list-item-title>
                                    <v-list-item-subtitle v-if="item.driver && item.driver.car">
                                        {{ item.driver.car.state_license_plate }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-menu>

                <template v-else-if="item.status.status === 1">
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
            <template v-slot:item.operator="{ item }">
                <div class="d-flex justify-space-between align-center">
                    <template>
                        <small v-if="item.operator"
                            >{{ item.operator.surname }} {{ item.operator.name }} {{ item.operator.patronymic }}</small
                        >
                        <v-chip v-else-if="!item.completed && !item.canceled" x-small outlined color="error">
                            нет оператора
                        </v-chip>
                        <div v-else class="text-center">-</div>
                    </template>
                    <v-menu v-if="!item.completed && !item.canceled" left offset-x transition="scale-transition">
                        <template v-slot:activator="{ on }">
                            <v-btn
                                @click="getOperators()"
                                :loading="operatorAttachLoading === item.order_id"
                                icon
                                small
                                color="grey darken-2"
                                v-on="on"
                            >
                                <v-icon small v-text="'mdi-cogs'" />
                            </v-btn>
                        </template>
                        <v-list height="350" width="300">
                            <v-list-item v-if="operatorsLoading">
                                <v-list-item-content>
                                    <div style="height: 300px" class="d-flex justify-center align-center">
                                        <v-progress-circular indeterminate color="primary" />
                                    </div>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item-group v-else-if="operators.length" style="background-color: white">
                                <v-list-item
                                    :disabled="operator.system_worker_id === item.operator_id"
                                    @click="changeOperator(item.order_id, operator.worker_oprator_id)"
                                    v-for="operator in operators"
                                    :key="operator.system_worker_id"
                                    :value="operator.system_worker_id"
                                >
                                    <v-list-item-content>
                                        <div class="d-flex align-center justify-space-between">
                                            <span class="mr-2">
                                                {{ operator.system_worker.surname }}
                                                {{ operator.system_worker.name }}
                                                {{ operator.system_worker.patronymic }}
                                            </span>
                                            <div
                                                class="elevation-5"
                                                style="height: 10px; width: 10px; border-radius: 50%"
                                                :style="{
                                                    'background-color': operator.system_worker.logged
                                                        ? '#00C853'
                                                        : '#EF5350',
                                                }"
                                            />
                                        </div>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                            <v-list-item v-else>
                                <v-list-item-content>
                                    <div style="height: 300px" class="d-flex justify-center align-center">
                                        <span>нет операторов</span>
                                    </div>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
            <template v-slot:item.price="{ item }">
                <v-chip x-small color="error" outlined v-if="item.process && item.process.price >= fatPrice">
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
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-subtitle>Отправить сообщение:</v-list-item-subtitle>
                                <div class="d-flex mt-1 flex-column">
                                    <v-text-field
                                        label="Сообщение"
                                        name="message"
                                        v-model="message"
                                        autocomplete="off"
                                        :error-messages="errors.collect('message')"
                                        v-validate="'required'"
                                    />
                                    <v-btn color="success" @click="sendMessage(item)">Отправить</v-btn>
                                </div>
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

        <!--   Context Menu     -->
        <v-menu v-if="showMenu" v-model="showMenu" :position-x="menuX" :position-y="menuY" absolute offset-y>
            <v-list dense :rounded="false" tile color="#4a6572" dark class="pointer" style="opacity: 0.95">
                <v-list-item dense @click="openMenuDialog('details')">
                    <v-list-item-icon>
                        <v-icon v-text="'mdi-details'" />
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title v-text="'Детали'" />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    :disabled="
                        actionOrder.status_id === orderStatus.COMPLETED ||
                        actionOrder.status_id === orderStatus.CANCELED
                    "
                    dense
                    @click="openMenuDialog('actions')"
                >
                    <v-list-item-icon>
                        <v-icon v-text="'mdi-comment-edit-outline'" />
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title v-text="'Редактировать'" />
                    </v-list-item-content>
                </v-list-item>
                <v-divider />
                <v-list-item
                    @click="cancelDialog = true"
                    dense
                    :disabled="
                        actionOrder.status_id === orderStatus.COMPLETED ||
                        actionOrder.status_id === orderStatus.CANCELED
                    "
                >
                    <v-list-item-icon>
                        <v-icon color="warning darken-1" v-text="'mdi-close'" />
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title v-text="'Отменить'" />
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-menu>

        <!--AllTransaction info dialog-->
        <v-dialog
            v-if="infoOrderDialog"
            max-width="1500"
            width="100%"
            persistent
            v-model="infoOrderDialog"
            overlay-opacity="0.7"
        >
            <order-info-dialog
                :height="tableHeight + 30"
                :order="infoOrder"
                @call="$emit('call', $event)"
                @close="closeInfo()"
            />
        </v-dialog>

        <!--    Order Edit Dialog    -->
        <v-dialog
            v-if="actionsOrderDialog"
            max-width="950"
            width="100%"
            persistent
            v-model="actionsOrderDialog"
            content-class="search-dialog"
        >
            <order-actions-dialog :height="tableHeight - 100" :order="actionOrder" @close="closeAction" />
        </v-dialog>

        <!--    Order Cancel dialog    -->
        <v-dialog v-if="cancelDialog" v-model="cancelDialog" max-width="400" width="100%">
            <v-card flat v-if="inProcess">
                <v-card-text class="pa-4 text-center">
                    <span style="font-size: 20px">
                        Вы уверены, что хотите отменить заказ номер: {{ actionOrder.order_id }}?
                    </span>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn :disabled="cancelLoading" small color="primary" @click="cancelDialog = false">нет</v-btn>
                    <v-btn :loading="cancelLoading" small text color="error" @click="cancelOrder()">да</v-btn>
                    <v-spacer />
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="js" src="./OrdersTable.main.js" />
<style scoped lang="scss" src="./OrdersTable.main.scss" />
