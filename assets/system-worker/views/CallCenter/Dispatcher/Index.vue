<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <v-card class="mb-2" elevation="6" tile :dark="darkMode">
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
                    <v-tab :key="2">Карта</v-tab>
                    <v-divider vertical />
                    <v-tab :key="3">Операторы</v-tab>
                    <v-divider vertical />
                    <v-tab :key="4">Звонки</v-tab>
                    <v-divider vertical />
                    <v-tab :key="5">Борты</v-tab>
                </v-tabs>
            </div>

            <v-tabs-items v-model="tab" :dark="darkMode">
                <v-tab-item :key="1" class="pt-4" :eager="false">
                    <orders-table
                        :socket-data="socketData"
                        :types="orderTypes"
                        :statuses="orderStatuses"
                        :height="contentHeight + 15"
                        @call="callTo($event)"
                    />
                </v-tab-item>
                <v-tab-item :key="2" class="pt-4" :eager="false">
                    <orders-map
                        :socket-data="socketData"
                        :drivers="drivers"
                        :driver-statuses="driverStatuses"
                        :pending-orders="pendingOrders"
                        :height="contentHeight + 8"
                        @call="callTo($event)"
                    />
                </v-tab-item>
                <v-tab-item :key="3" class="pt-4" :eager="false">
                    <operators-table :socket-data="socketData" :height="contentHeight" />
                </v-tab-item>
                <v-tab-item :key="4" class="pt-4" :eager="false">
                    <calls-table :socket-data="socketData" :height="contentHeight" @call="callTo($event)" />
                </v-tab-item>
                <v-tab-item :key="5" class="pt-4" :eager="false">
                    <boards-table :socket-data="socketData" :height="contentHeight" @call="callTo($event)" />
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
            type="dispatcher"
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

<script lang="js" src="./index.main.js" />
<style lang="scss" scoped src="./index.style.scss"/>
