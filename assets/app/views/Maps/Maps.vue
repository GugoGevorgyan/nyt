<!-- @format -->

<template>
    <v-container class="pa-0" fill-height fluid>
        <navbar />

        <v-overlay :value="overlay" />

        <div id="map" style="width: 100%; height: 100%"></div>

        <div class="order-form">
            <keep-alive>
                <OrderTaxi />
            </keep-alive>
            <div v-if="orderForm.open" style="position: fixed; left: 928px; top: 77px; opacity: 0.9; width: 330px">
                <Transport />
            </div>
        </div>

        <v-dialog
            persistent
            max-width="450"
            width="450"
            overlay-opacity="0.8"
            v-if="paymentDialog || (!paymentCards.length && PAYMENT_TYPE.CARD === paymentType)"
            v-model="PAYMENT_TYPE.CARD === paymentType"
        >
            <keep-alive>
                <payment-card />
            </keep-alive>
        </v-dialog>

        <div class="order-button-additional">
            <v-tooltip v-if="order.address_from && !orderForm.open && order.phone" right>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" v-on="on" color="yellow darken-3" icon rounded x-large @click="makeOrder">
                        <v-icon v-text="'mdi-send'" />
                    </v-btn>
                </template>
                <span>Заказать</span>
            </v-tooltip>
        </div>

        <!--START ORDER ABORTED FEEDBACK-->
        <OrderFeedback v-if="orderFeedback.status" />
        <!--END ORDER ABORTED FEEDBACK-->
        <OrderProgress v-if="orderProgress.status" />
        <RepeatOrder v-if="orderRepeat.status" />
    </v-container>
</template>

<script lang="js" src="./Maps.main.js" />
<style lang="scss" scoped src="./Maps.style.scss" />
