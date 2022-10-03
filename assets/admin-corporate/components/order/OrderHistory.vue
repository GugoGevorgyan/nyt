<!-- @format -->

<template>
    <div style="height: 100%">
        <v-data-table
            :headers="paginated.headers"
            :height="window.height"
            :items="paginated._payload"
            :items-per-page="Number(paginated.per_page)"
            :item-class="rowClasses"
            calculate-widths
            class="elevation-4"
            dense
            fixed-header
            hide-default-footer
            item-key="order.order_id"
            v-model="paginated.selected"
            :loading="paginated.loading"
            loader-height="2"
            selectable-key="order.order_id"
        >
            <!--TOP-->
            <template v-slot:top>
                <v-toolbar flat color="grey lighten-5" tile>
                    <v-layout>
                        <v-flex xs2>
                            <v-autocomplete
                                :items="orderTypes"
                                clearable
                                class="mt-3 rounded-1 border-white"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                item-text="text"
                                item-value="order_type_id"
                                label="Filter by Order Type"
                                background-color="white"
                                dense
                                outlined
                                v-model="paginated.type"
                            />
                        </v-flex>
                        <v-divider class="mx-4" inset vertical />
                        <v-flex xs2>
                            <v-autocomplete
                                class="mt-3 rounded-1"
                                :items="orderStatuses"
                                clearable
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                item-text="text"
                                item-value="order_status_id"
                                label="Filter by Order Status"
                                background-color="white"
                                outlined
                                dense
                                v-model="paginated.status"
                            />
                        </v-flex>
                        <v-divider class="mx-4" inset vertical />
                        <v-flex xs3>
                            <el-date-picker
                                style="max-width: 100%"
                                class="mt-3 white rounded-1"
                                size="large"
                                :picker-options="pickerOptions"
                                end-placeholder="End date"
                                range-separator="To"
                                start-placeholder="Start date"
                                type="daterange"
                                v-model="datePicker"
                            />
                        </v-flex>
                        <v-divider class="mx-4" inset vertical />
                        <v-flex xs3>
                            <div class="mt-2">
                                <small style="font-size: 1.6vw">Потраченная сумма: </small>
                                <span class="display-1 font-weight-light">
                                    <small>{{ paginated.sum ? paginated.sum : 0.0 }}</small>
                                    <v-icon small v-text="'mdi-currency-rub'" />
                                </span>
                            </div>
                        </v-flex>

                        <v-divider class="mx-4" inset vertical />

                        <v-flex xs1>
                            <div class="mt-sm-2 ml-2 mx-auto">
                                <v-menu offset-y>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            :disabled="!paginated._payload.length"
                                            height="48"
                                            tile
                                            icon
                                            block
                                            color="grey darken-5"
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                            Excel
                                            <v-icon small v-text="'mdi-arrow-down'" />
                                        </v-btn>
                                    </template>
                                    <v-list elevation="0" dense outlined>
                                        <v-list-item v-for="(item, index) in itemsExcellButton" :key="index">
                                            <v-btn
                                                v-if="index === 1"
                                                tile
                                                block
                                                class="full-width"
                                                color="white"
                                                elevation="0"
                                                @click="
                                                    paginated.generateExcell(
                                                        company.company_id,
                                                        paginated._payload,
                                                        company.name,
                                                    )
                                                "
                                            >
                                                <v-icon v-text="item.icon" />
                                                {{ item.title }}
                                            </v-btn>
                                            <v-btn
                                                v-else
                                                tile
                                                block
                                                class="full-width"
                                                color="white"
                                                elevation="0"
                                                @click="
                                                    printExcell(company.company_id, paginated._payload, company.name)
                                                "
                                            >
                                                <v-icon v-text="item.icon" />
                                                {{ item.title }}
                                            </v-btn>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-toolbar>
            </template>

            <!--PASSENGER PHONE-->
            <template v-slot:item.passenger.phone="{ item }">
                <small>{{ item.passenger ? item.passenger.phone : paginated.data.client.phone }}</small>
            </template>

            <!--     PRICE       -->
            <template v-slot:item.order.price="{ item }">
                <small>{{ item.order.price }} {{ item.order.currency }}</small>
            </template>
            <!--     END PRICE       -->

            <!--PASSENGER NAME-->
            <template v-slot:item.passenger_name="{ item }">
                <small>
                    {{
                        item.passenger
                            ? item.passenger.patronymic + " " + item.passenger.name + " " + item.passenger.surname
                            : item.clients.client_id
                    }}
                </small>
            </template>

            <!--STATUS-->
            <template v-slot:item.status="{ item }">
                <div class="d-flex">
                    <div class="d-flex align-center">
                        <div
                            :style="{ background: item.status.color }"
                            style="height: 10px; width: 10px; float: left; border-radius: 50%"
                        />
                    </div>
                    <small>
                        <span class="mx-1">{{ item.status.text }}</span>
                    </small>
                </div>
            </template>

            <!--PHONE-->
            <template v-slot:item.client.phone="{ item }">
                {{ item.client.phone | VMask(phoneMask) }}
            </template>

            <!--INFO-->
            <template v-slot:item.info="{ item }">
                <v-menu left nudge-left="35px" :close-on-click="true" tile v-if="item.driver">
                    <template v-slot:activator="{ on }">
                        <v-btn color="yellow darken-2" icon v-on="on">
                            <v-icon>mdi-information-outline</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <small>Водитель</small>
                                <v-divider />
                                <v-list-item-title>
                                    {{ item.driver.name }} {{ item.driver.patronymic }}
                                    {{ item.driver.surname }}
                                </v-list-item-title>
                                <v-divider />
                                <v-divider />
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>
                                <small>Телефон водителя</small>
                                <v-divider />
                                <v-list-item-title>
                                    {{ item.driver.phone }}
                                </v-list-item-title>
                                <v-divider />
                                <v-divider />
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>
                                <small>Номер машины</small>
                                <v-divider />
                                <v-list-item-title>
                                    {{ item.car.state_license_plate }}
                                </v-list-item-title>
                                <v-divider />
                                <v-divider />
                            </v-list-item-content>
                        </v-list-item>
                        <div v-if="item.stage">
                            <v-list-item>
                                <v-list-item-content>
                                    <small>Время начала поездки</small>
                                    <v-divider />
                                    <v-list-item-title>
                                        {{ item.stage.started }}
                                    </v-list-item-title>
                                    <v-divider />
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-content>
                                    <small>Время зацершения поездки</small>
                                    <v-divider />
                                    <v-list-item-title>
                                        {{ item.stage.ended }}
                                    </v-list-item-title>
                                    <v-divider />
                                    <v-divider />
                                </v-list-item-content>
                            </v-list-item>
                        </div>
                    </v-list>
                </v-menu>
            </template>

            <template v-slot:item.road="{ item }">
                <v-btn
                    v-if="item.status.id === ORDER_STATUS.COMPLETED"
                    color="green lighten-1"
                    icon
                    @click="
                        roadDialoge = true;
                        selectedRow = item.order.order_id;
                    "
                >
                    <v-icon v-text="'mdi-road'" />
                </v-btn>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            icon
                            small
                            v-if="whereClauseCancel(item.order.order_id)"
                            @click="cancelOrder(false, item.order.order_id, item.client.client_id)"
                        >
                            <v-icon small v-text="'mdi-close'" color="red" />
                        </v-btn>
                    </template>
                    <span v-if="whereClauseCancel(item.order.order_id)" v-text="'Отменить'" />
                </v-tooltip>
            </template>

            <!--FOOTER-->
            <template v-slot:footer>
                <div ref="footer">
                    <v-divider class="ma-0" />
                    <v-row no-gutters class="py-1">
                        <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                        <v-col cols="12" md="8" class="d-flex justify-center align-center">
                            <v-tooltip left>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-bind="attrs" v-on="on">
                                        <v-pagination
                                            :length="paginated.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="paginated.current_page"
                                        />
                                    </div>
                                </template>
                                <span>
                                    {{
                                        Number(paginated.total)
                                            ? `${paginated.from}-${paginated.to} из ${paginated.total}`
                                            : "Нет данных"
                                    }}
                                </span>
                            </v-tooltip>
                        </v-col>
                        <v-col cols="12" md="2" class="d-flex justify-center align-center">
                            <v-menu offset-y max-width="100">
                                <template v-slot:activator="{ on: menu, attrs }">
                                    <v-tooltip left>
                                        <template v-slot:activator="{ on: tooltip }">
                                            <v-btn
                                                fab
                                                small
                                                dark
                                                color="yellow darken-3"
                                                class="mb-1"
                                                v-bind="attrs"
                                                v-on="{ ...tooltip, ...menu }"
                                            >
                                                {{ paginated.per_page }}
                                            </v-btn>
                                        </template>
                                        <span>строк на странице</span>
                                    </v-tooltip>
                                </template>
                                <v-list>
                                    <v-list-item
                                        :disabled="paginated.per_page === item"
                                        color="yellow darken-3"
                                        v-for="(item, index) in paginated.perPages"
                                        :key="index"
                                        @click="paginated.per_page = item"
                                    >
                                        <v-list-item-title>{{ item }}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-col>
                    </v-row>
                </div>
            </template>
        </v-data-table>

        <!--Road Map Dialogue-->
        <v-dialog v-model="roadDialoge" max-width="1200" width="100%" persistent overlay-opacity="0.5">
            <road-map
                v-if="roadDialoge"
                :data="paginated._payload.filter(item => item.order.order_id === selectedRow)[0]"
                @closeDialogue="closeDialogue()"
            />
        </v-dialog>

        <v-dialog v-model="cancelDialog" v-if="cancelDialog" max-width="350px" width="100%">
            <v-card>
                <v-card-title class="justify-space-between">
                    Отменить заказ
                    <v-btn icon color="error" @click="cancelDialog = false">
                        <v-icon v-text="'mdi-close'" />
                    </v-btn>
                </v-card-title>
                <v-divider />
                <v-card-text>
                    <p class="my-2"> Вы уверены, что хотите отменить заказ </p>
                    <p>Пожалуйста введите пароль для этого действия.</p>
                    <v-form>
                        <input :value="$csrf" name="_token" type="hidden" />
                        <v-text-field
                            data-vv-as="пароль"
                            label="Пароль"
                            name="password"
                            type="password"
                            v-model="terminatePassword"
                            v-validate="'required|min:6|max:32'"
                            :error-messages="errors.collect('password')"
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn
                        color="error"
                        :disabled="errors.any() || !terminatePassword"
                        text
                        @click="cancelOrder(true)"
                        v-text="'Отменить'"
                    />
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="js" src="./OrderHistory.main.js" />
<style scoped>
header {
    height: 50px !important;
}
.vhr {
    border: none;
    border-left: 1px solid hsl(200, 83%, 7%);
    height: 100vh;
    width: 1px;
}
</style>
