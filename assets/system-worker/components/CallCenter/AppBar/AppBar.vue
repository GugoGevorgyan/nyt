<!-- @format -->

<template>
    <div>
        <v-app-bar class="border-lg" dense :color="darkMode ? 'black' : 'quaternary'" :dark="darkMode">
            <v-row no-gutters>
                <v-col cols="10" class="d-flex align-center">
                    <v-btn @click="newDialog()" color="secondary" text rounded small dark class="mr-4">
                        <v-icon left v-text="'mdi-plus'" />
                        Форма заказа
                    </v-btn>
                    <v-divider vertical dark class="mr-3" />
                    <v-btn
                        v-for="(item, index) in dialogs"
                        :key="index"
                        height="100%"
                        class="pa-2 mr-2 dialog-btn rounded-0"
                        :class="index === activeDialog ? 'dialog-btn-active' : 'dialog-btn-deactivated'"
                        @click="showDialog(index)"
                        small
                        text
                        dark
                    >
                        {{ item.clientObj.client ? "Клиент: " + clientTitle(item.clientObj.client) : "Новый заказ" }}
                        <v-icon right dark v-text="'mdi-arrow-up'" />
                    </v-btn>
                </v-col>
                <v-col cols="2" class="d-flex justify-end align-center">
                    <v-divider vertical dark class="mr-3" />
                    <v-btn
                        small
                        rounded
                        :loading="atcLoginLoading"
                        @click="phoneLogin(!atcLogged)"
                        :color="atcLogged ? 'error' : 'success'"
                        class="mr-2"
                        text
                        dark
                    >
                        <v-icon left v-text="atcLogged ? 'mdi-pause' : 'mdi-play'" />
                        прием звонков
                    </v-btn>
                    <v-btn
                        small
                        rounded
                        @click="$emit('updatePhoneShow', !phoneShow)"
                        :color="phoneShow ? 'error' : 'success'"
                        class="mr-2"
                        text
                        dark
                    >
                        <v-icon left v-text="phoneShow ? 'mdi-phone' : 'mdi-phone-outline'" />
                        телефон
                    </v-btn>
                </v-col>
            </v-row>
        </v-app-bar>
        <v-scroll-y-reverse-transition>
            <div
                v-if="orderDialog"
                style="position: absolute; width: 100%; top: 0; left: 0; z-index: 2"
                class="pt-3 px-3 pb-2"
            >
                <div :style="{ height: height }">
                    <order-dialog
                        :socket-data="socketData"
                        v-if="dialogs.length"
                        class="elevation-10"
                        :airports="airports"
                        :stations="stations"
                        :metros="metros"
                        :car-classes="carClasses"
                        :car-options="carOptions"
                        :payment-methods="paymentMethods"
                        :height="height"
                        :dialog-tab="dialogs[activeDialog].tab"
                        :client-obj="dialogs[activeDialog].clientObj"
                        :order-obj="dialogs[activeDialog].orderObj"
                        @call="$emit('call', $event)"
                        @updateTab="dialogs[activeDialog].tab = $event"
                        @updateClientObj="updateDialogClient($event)"
                        @copyOrder="copyOrder($event, activeDialog)"
                        @orderCreated="orderCreated()"
                        @hideDialog="hideDialog"
                        @closeDialog="closeDialog"
                    />
                </div>
            </div>
        </v-scroll-y-reverse-transition>
    </div>
</template>

<script lang="js" src="./AppBar.main.js" />
<style lang="scss" src="./AppBar.style.scss" scoped />
