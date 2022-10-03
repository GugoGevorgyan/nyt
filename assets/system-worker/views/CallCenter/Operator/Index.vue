<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <v-card elevation="6" tile class="mb-2" style="position: relative" :dark="darkMode">
            <div ref="tabs">
                <v-tabs
                    grow
                    :dark="darkMode"
                    :color="darkMode ? 'white' : 'secondary'"
                    :active-class="darkMode ? 'grey darken-3' : 'white'"
                    :background-color="darkMode ? 'black' : 'grey lighten-3'"
                    height="35px"
                    right
                    v-model="tab"
                >
                    <v-tab :key="1">Заказаы</v-tab>
                    <v-divider vertical />
                    <v-tab :key="2">Звонки</v-tab>
                    <v-divider vertical />
                    <v-tab :key="3">Борты</v-tab>
                </v-tabs>
            </div>

            <v-tabs-items v-model="tab" :dark="darkMode">
                <!--orders-->
                <v-tab-item :key="1" class="pt-4" :eager="false">
                    <v-row no-gutters>
                        <v-col cols="12" :md="ordersTableFull ? 12 : 6" :xl="ordersTableFull ? 12 : 6" class="px-1">
                            <orders-table
                                :socket-data="socketData"
                                :statuses="orderStatuses"
                                :types="orderTypes"
                                :height="contentHeight - 67"
                                :full="ordersTableFull"
                                @full="ordersTableFull = $event"
                                @call="callTo($event)"
                            />
                        </v-col>

                        <!--map-->
                        <v-col class="px-1" v-show="!ordersTableFull" cols="12" md="6">
                            <orders-map
                                :socket-data="socketData"
                                :drivers="drivers"
                                :driver-statuses="driverStatuses"
                                :pending-orders="pendingOrders"
                                :height="contentHeight - 47"
                                @call="callTo($event)"
                            />
                        </v-col>
                    </v-row>
                </v-tab-item>

                <v-tab-item :key="2" class="pt-4" :eager="false">
                    <calls-table :socket-data="socketData" :height="contentHeight - 52" @call="callTo($event)" />
                </v-tab-item>

                <v-tab-item :key="3" class="pt-4" :eager="false">
                    <boards-table :socket-data="socketData" :height="contentHeight - 52" @call="callTo($event)" />
                </v-tab-item>
            </v-tabs-items>
        </v-card>

        <!--app bar-->
        <app-bar
            :socket-data="socketData"
            :car-classes="carClasses"
            :car-options="carOptions"
            :payment-methods="paymentTypes"
            :metros="metros"
            :airports="airports"
            :stations="railwayStations"
            type="operator"
            :call="call"
            :atc-logged="atcLogged"
            :atc-phone-login="atcPhoneLogin"
            :phone-show="phoneShow"
            :height="dialogHeight"
            @call="callToNumber = $event"
            @updateAtcPhoneLogin="atcPhoneLogin = $event"
            @updatePhoneShow="phoneShow = $event"
        />

        <!--phone-->
        <sip-call
            :country-code="countryCode"
            :call-to-number="callToNumber"
            :sub-phone="subPhone"
            :phone-show="phoneShow"
            :login="atcPhoneLogin"
            @ended="callToNumber = undefined"
            @phoneShow="phoneShow = $event"
            @logged="atcLogged = $event"
            @call="call = $event"
        />
    </v-container>
</template>

<script lang="js" src="./Index.main.js" />
