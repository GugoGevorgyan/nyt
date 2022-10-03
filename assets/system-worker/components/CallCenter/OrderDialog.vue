<!-- @format -->

<template>
    <div ref="box" style="background: white">
        <div ref="tabs">
            <v-tabs
                ref="title"
                grow
                right
                v-model="tab"
                height="30px"
                :dark="darkMode"
                :color="darkMode ? 'white' : 'secondary'"
                :active-class="darkMode ? 'grey darken-3' : 'white'"
                :background-color="darkMode ? 'black' : 'grey lighten-4'"
            >
                <v-tab :key="1">
                    <span>{{ clientObj.client ? "Клиент: " + clientTitle(clientObj.client) : "Новый клиент" }}</span>
                </v-tab>
                <v-divider vertical />
                <v-tab :key="2">
                    <div class="d-flex align-center">
                        <span>Создать новый заказ</span>
                    </div>
                </v-tab>
                <v-divider vertical />
                <v-system-bar
                    light
                    height="30"
                    :dark="darkMode"
                    :color="darkMode ? 'black' : 'grey lighten-3'"
                    class="pa-0"
                >
                    <v-spacer />
                    <v-btn icon class="create-order-panel-btn" @click="$emit('hideDialog')">
                        <v-icon small color="accent" v-text="'mdi-window-minimize'" />
                    </v-btn>
                    <v-btn icon class="create-order-panel-btn" @click="closeDialog">
                        <v-icon small color="warning" v-text="'mdi-close'" />
                    </v-btn>
                </v-system-bar>
            </v-tabs>
        </div>

        <v-tabs-items v-model="tab">
            <!--Client-->
            <v-tab-item :key="1">
                <client-info
                    :client-obj="clientObj"
                    :height="contentHeight"
                    @call="$emit('call', $event)"
                    @updateClientObj="$emit('updateClientObj', $event)"
                    @copyOrder="$emit('copyOrder', $event)"
                />
            </v-tab-item>

            <!--AllTransaction-->
            <v-tab-item :key="2">
                <order-form
                    :socket-data="socketData"
                    :tab="tab"
                    :client="clientObj.client"
                    :airports="airports"
                    :stations="stations"
                    :metros="metros"
                    :car-classes="carClasses"
                    :car-options="carOptions"
                    :payment-methods="paymentMethods"
                    :client-companies="clientObj.companies"
                    :order="orderObj.order"
                    :meet="orderObj.meet"
                    :passenger="orderObj.passenger"
                    :height="contentHeight"
                    @created="orderCreated()"
                />
            </v-tab-item>

            <v-dialog v-model="closeAccept" v-if="closeAccept" width="300" max-width="300" overlay-opacity="0.3">
                <v-card>
                    <v-card-title class="d-flex align-center justify-center red-text">! Закрыт окно</v-card-title>
                    <v-card-text>
                        Есть активный клиент
                        <b>{{ clientObj.client.phone }}</b>
                    </v-card-text>
                    <v-divider />
                    <v-card-actions>
                        <v-spacer />
                        <v-btn small class="primary" @click="closeAccept = false" v-text="'Отмена'" />
                        <v-btn small outlined color="warning" @click="$emit('closeDialog')" v-text="'Закрыть'" />
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-tabs-items>
    </div>
</template>

<script>
import OrderForm from "./OrderForm/OrderForm";
import LastOrders from "./LastOrders/LastOrders";
import ClientInfo from "./ClientInfo";

export default {
    components: { ClientInfo, LastOrders, OrderForm },

    props: {
        clientObj: {
            required: true,
        },
        orderObj: {
            required: true,
        },
        height: {
            required: true,
        },
        dialogTab: {
            required: true,
        },
        carClasses: {
            required: true,
        },
        carOptions: {
            required: true,
        },
        paymentMethods: {
            required: true,
        },
        metros: {
            required: true,
        },
        stations: {
            required: true,
        },
        airports: {
            required: true,
        },
        socketData: {
            required: true,
        },
    },

    data() {
        return {
            tab: this.dialogTab,
            tabHeight: 0,
            closeAccept: false,
        };
    },
    watch: {
        dialogTab: function () {
            this.tab = this.dialogTab;
        },
        tab: function () {
            this.$emit("updateTab", this.tab);
        },
    },
    computed: {
        contentHeight() {
            return this.height - this.tabHeight - 30;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },
    methods: {
        clientTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic ? `${initials.join(" ").trim()}` : client.phone;
        },

        orderCreated() {
            this.$emit("orderCreated");
        },

        closeDialog() {
            if (this.clientObj.client) {
                this.closeAccept = true;
            } else {
                this.$emit("closeDialog");
            }
        },
    },

    mounted() {
        this.tabHeight = this.$refs.tabs.clientHeight;
    },
};
</script>
<style scoped>
.theme--light .v-tabs:not(.v-tabs--vertical) .v-tab {
    min-width: 0;
    padding: 0;
    margin: 0 0;
}
.create-order-panel-btn:hover {
    border-radius: 0 !important;
    background-color: rgba(177, 177, 177, 0.54);
}
</style>
